<?php
namespace App\Services;

use Validator;
use App\Models\User;
use App\Models\WalletOtp;
use App\Models\Transaction;
use App\Models\DepositsTracking;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionTracking;
use Illuminate\Support\Facades\Hash;

class TransactionsService{
    public function editWalletAddress($request)
    {
        $validator = Validator::make($request->all(), [
            'wallet_address' => 'required',
            'type' => 'required',
            'otp' => 'required_unless:skip_otp,1',
            'google_2fa' => 'required_unless:skip_otp,1',
        ], [
            'otp.required_unless' => 'OTP is required',
            'google_2fa.required_unless' => 'Google Authenticator code is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $skip_otp = ($request->has('skip_otp') && $request->skip_otp == 1) ? true : false;
        $user = User::find(authUser()->id);

        if ($skip_otp == false) {
            // 1. Verify Email OTP
            $varifiedResponse = checkOtp($request->otp);
            if ($varifiedResponse['status'] == false) {
                return response()->json(['errors' => ['otp' => [$varifiedResponse['message']]]], 422);
            }

            // 2. Verify Google 2FA
            $valid = \PragmaRX\Google2FALaravel\Facade::verifyKey($user->google2fa_secret, $request->google_2fa);
            if (!$valid) {
                return response()->json(['errors' => ['google_2fa' => ['Invalid Google Authenticator code']]], 422);
            }
        }

        $address = $request->wallet_address;
        $type = $request->type;

        // Restriction: Ensure the wallet address is unique across all users (excluding current user)
        $isUsed = User::where(function($query) use ($type, $address) {
            $query->where('wallet_addresses->' . $type, $address)
                  ->orWhere('bep20_wallet_address', $address) // Check flat column as well
                  ->orWhere('wallet_addresses', 'LIKE', '%"' . $address . '"%'); // Extra fallback for JSON strings
        })->where('id', '!=', $user->id)->exists();

        if ($isUsed) {
            return response()->json(['status' => false, 'message' => 'This wallet address is already used. Please try another one', 'code' => 400]);
        }

        DB::beginTransaction();
        try {
            if ($address != null && $address != '' && $user != null) {
                $existingAddresses = $user->wallet_addresses ?? [];

                // Decode if stored as JSON string
                if (is_string($existingAddresses)) {
                    $existingAddresses = json_decode($existingAddresses, true);
                }

                // Update the address for the specific type
                $existingAddresses[$type] = $address;
                $updateData = ['wallet_addresses' => $existingAddresses];
                if (strtoupper($type) === 'BEP-20') {
                    $updateData['bep20_wallet_address'] = $address;
                }

                if (!$user->google2fa_setup_at && $user->google2fa_secret) {
                    $updateData['google2fa_setup_at'] = now();
                }

                if ($user->update($updateData)) {
                    if ($skip_otp == false) {
                        $otp = WalletOtp::where('otp', $request->otp)->first();
                        if ($otp) {
                            $otp->update(['is_used' => 1]);
                        }
                    }
                    DB::commit();
                    return response()->json(['status' => true, 'message' => 'Wallet Address added successfully', 'refresh' => true, 'code' => 200], 200);
                }
            }
            throw new \Exception("Error Processing Request", 1);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $th->getMessage(), 'code' => 400], 400);
        }
    }
    public function addTransaction($request){
        $validator = Validator::make($request->all(),[
            'amount'    =>   'required|numeric|min:50',
            'email_otp' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = authUser();
        
        // 1. Verify Email OTP
        $otpResponse = checkOtp($request->email_otp);
        if ($otpResponse['status'] == false) {
            return response()->json(['status' => false, 'message' => $otpResponse['message'], 'code' => 400]);
        }

        // 2. Fetch Bank Details
        $bankDetails = \App\Models\UserBankDetail::whereUserId($user->id)->first();
        if (!$bankDetails || empty($bankDetails->account_number) || empty($bankDetails->ifsc_code)) {
            return response()->json(['status' => false, 'message' => 'Bank Details are missing. Please add them first.', 'code' => 400]);
        }
        $formattedBankDetails = "A/C: {$bankDetails->account_number} | IFSC: {$bankDetails->ifsc_code} | Bank: {$bankDetails->bank_name} | Name: {$bankDetails->account_holder_name}";

        // 3. Check Balance with Trading Lock & $50 Lock
        $totalBalance = $user->walletIncomesByKey();
        $lockedTrading = $user->getTradingLockedAmount();
        $availableBalance = round($totalBalance - $lockedTrading - 50, 2);

        if($request->amount > $availableBalance){
            return response()->json(['status' => false,'message' => 'Insufficient Balance. $50 must stay in your wallet and your trading amount ($' . $lockedTrading . ') is also locked.','code' => 400]);
        }

        // 3.0.1 Check Max Withdrawal Limit (2x Deposit + Referrals)
        $maxLimit = $user->getWithdrawalLimit();
        $totalWithdrawn = $user->getTotalWithdrawals();
        $remainingLimit = $user->getRemainingWithdrawalLimit();

        if ($request->amount > $remainingLimit) {
            return response()->json([
                'status' => false, 
                'message' => 'Withdrawal Limit Exceeded! Your total limit is $' . number_format($maxLimit, 2) . '. Remaining limit is $' . number_format($remainingLimit, 2) . '. Please deposit more or refer direct members to increase your limit.', 
                'code' => 400
            ]);
        }

        // 3.0.2 Check Per-Transaction Category Limit
        $activeCategory = $user->agentCategory();
        if ($activeCategory) {
            $categoryUnlockBalance = $activeCategory->unlock_balance;
            $maxSingleLimit = ($categoryUnlockBalance == 50) ? 200 : $categoryUnlockBalance;

            if ($request->amount > $maxSingleLimit) {
                return response()->json([
                    'status' => false, 
                    'message' => "Maximum withdrawal limit for your current level ({$activeCategory->name}) is $" . number_format($maxSingleLimit, 2) . " per transaction.", 
                    'code' => 400
                ]);
            }
        }

        // 3.1 Check Activation Date (3 days wait from Agent Category Activation)
        $latestAgentCategoryRecord = $user->firstUserAgentCategory;
        $activationDate = $latestAgentCategoryRecord ? $latestAgentCategoryRecord->created_at : ($user->joiningKit?->used_at ?? $user->created_at);
        $daysSinceActivation = \Carbon\Carbon::parse($activationDate)->diffInDays(\Carbon\Carbon::now());

        if ($daysSinceActivation < 3) {
            return response()->json(['status' => false, 'message' => 'Withdrawal can be done after 3 days of activation/upgrade.', 'code' => 400]);
        }



        // 3.1.5 Check 72 hours since last withdrawal
        $lastWithdrawal = Transaction::where('user_id', $user->id)
                            ->where('type', 'withdrawl')
                            ->latest()
                            ->first();

        if ($lastWithdrawal && \Carbon\Carbon::parse($lastWithdrawal->created_at)->addHours(72)->isFuture()) {
            return response()->json(['status' => false, 'message' => 'You can only make another withdrawal 72 hours after your previous withdrawal.', 'code' => 400]);
        }



        // 4. Monthly Transaction Limit (Max 5)
        $monthlyLimit = 5;
        $monthlyCount = Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawl')
            ->whereIn('status', ['pending', 'success', 'Completed']) // Count non-rejected ones
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        if ($monthlyCount >= $monthlyLimit) {
            return response()->json(['status' => false, 'message' => "Monthly withdrawal limit reached. You can only perform {$monthlyLimit} withdrawals per month.", 'code' => 400]);
        }

        // 5. Calculate 6% TDS
        $tds_percentage = 0.06;
        $tds_amount = round($request->amount * $tds_percentage, 2);
        $net_amount = round($request->amount - $tds_amount, 2);

        $data = [
            'wallet_address'   => $formattedBankDetails,
            'amount'           => $request->amount,
            'transaction_fees' => $tds_amount,
            'net_amount'       => $net_amount,
            'user_id'          => $user->id,
            'status'           => 'pending',
            'type'             => 'withdrawl',
        ];

        try {
            DB::beginTransaction();
            $transaction = Transaction::create($data);
            TransactionTracking::create([
                'user_id'     => $user->id,
                'type'        => 'withdrawl',
                'keyword'     => 'Transaction',
                'amount'      => $data['amount'],
                'transaction_fee'  => $tds_amount,
                'net_amount'  => $net_amount,
                'related_id'  => $transaction->id,
                'remark'      => 'Withdrawal Income (6% TDS Deducted)',
            ]);

            \App\Models\UnifiedTransaction::create([
                'user_id'          => $user->id,
                'amount'           => $data['amount'],
                'transaction_type' => 'Debit',
                'category'         => 'Withdrawal',
                'related_id'       => $transaction->id,
                'status'           => 'Pending',
                'description'      => 'Withdrawal Request initiated for $' . number_format($data['amount'], 2) . ' (TDS: $' . $tds_amount . ')',
            ]);

            // Mark OTP as used
            $otp = WalletOtp::where('user_id', $user->id)->where('otp', $request->email_otp)->first();
            if($otp){
                $otp->update(['is_used' => 1]);
            }

            // Check if Agent Category needs to be reset (Low Balance)
            $remainingBalance = round($totalBalance - $request->amount, 2);
            $activeCategory = $user->agentCategory();
            if ($activeCategory && $remainingBalance < $activeCategory->unlock_balance) {
                $user->latestUserAgentCategory()->update(['is_active' => 0]);

                // Find the highest category the user qualifies for with the remaining balance
                $eligibleCategory = \App\Models\AgentCategory::where('unlock_balance', '<=', $remainingBalance)
                    ->orderBy('unlock_balance', 'desc')
                    ->first();

                if ($eligibleCategory) {
                    \App\Models\UserAgentCategory::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'agent_category_id' => $eligibleCategory->id,
                        ],
                        [
                            'is_active' => 1,
                        ]
                    );
                }
            }

            DB::commit();
            return response()->json(['status' => true,'message' => 'Withdrawal request placed successfully. The process will take 24-72 hours.','refresh' => true,'code' => 200],200);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => false,'message' => $th->getMessage(),'code' => 400]);
        }
    }
    public function setTransactionPin($request){
        $validator = Validator::make($request->all(),[
            'current_password' => 'required',
            'wallet_pin' => 'required|min:4|max:4',
            'confirm_pin' => 'required|min:4|max:4',
            'otp' => 'required_unless:skip_otp,1',
        ],[
            'otp.required_unless' => 'OTP is required',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors() ],422);
        }
         $skip_otp = ($request->has('skip_otp') && $request->skip_otp == 1) ? true : false;
        if((!$request->has('skip_otp') || $request->skip_otp != 1) && $request->otp == null){
            return response()->json(['errors' => ['otp' => ['OTP is required']]], 422);
        }
        $user = authUser();
        $confirmed = Hash::check($request->current_password,$user->password);
        if(!$confirmed){
            return response()->json(['status' => false,'message' => 'Incorrect Password','code' => 400]);
        }
        if($request->wallet_pin != $request->confirm_pin){
            return response()->json(['status' => false,'message' => 'Pin confirm does not match','code' => 400]);
        }
        if($skip_otp == false){
            $varifiedResponse = checkOtp($request->otp);
            if ($varifiedResponse['status'] == false) {
                return response()->json(['errors' => ['otp' => [$varifiedResponse['message']]]], 422);
            }
        }

        try {
            if($user->update(['wallet_pin' => Hash::make($request->wallet_pin)])){
                if($skip_otp == false){
                    $otp = WalletOtp::where('otp', $request->otp)->first();
                    if($otp){
                        $otp->update(['is_used' => 1]);
                    }
                }
                return response()->json(['status' => true,'message' => 'Pin set successfully','refresh' => true,'code' => 200],200);
            }
            throw new \Exception("Error Processing Request", 1);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => $th->getMessage(),'code' => 400]);
        }
    }
    public function addDepositTransaction($request){
        $validator = Validator::make($request->all(),[
            'paid_from_address' => 'required',
            'type' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = authUser();
        $data = $request->only('transaction_hash','type','paid_from_address');
        $data['user_id'] = $user->id;
        try {
            $transaction = DepositsTracking::create($data);
            if($transaction){
                return response()->json(['status' => true,'message' => 'Transaction added successfully','refresh' => true,'code' => 200]);
            }
            throw new \Exception("Error Processing Request", 1);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => $th->getMessage(),'code' => 400]);
        }
    }
}
?>
