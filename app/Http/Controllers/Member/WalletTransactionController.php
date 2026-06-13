<?php

namespace App\Http\Controllers\Member;

use DB;
use Carbon\Carbon;
use App\Models\Epin;
use App\Models\User;
use App\Models\WalletOtp;
use App\Models\JoiningKit;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Http\Controllers\Controller;
use App\Models\TransactionTracking;

class WalletTransactionController extends Controller
{
    public function walletIndex(){
        $adminCharge    = \App\Models\AdminCharge::first();
        $user = authUser('member');
        if($user == null){
            abort(403,'User Not Found');
        }
        // $userTransations =  Transaction::where('user_id', authUser('member')->id)
        //                     ->orderBy('created_at', 'desc')
        //                     ->get();
        $userTransations =  \App\Models\UnifiedTransaction::where('user_id', authUser('member')->id)
                            ->where(function($query) {
                                $query->where('category', '!=', 'Deposit')
                                      ->where(function($q) {
                                          $q->where('category', '!=', 'Fund Transfer')
                                            ->orWhere('transaction_type', '!=', 'Credit');
                                      });
                            })
                            ->orderBy('created_at', 'desc')
                            ->orderBy('id', 'desc')
                            ->get();
        // return view('rewards.bonus',compact('user',
        return view('wallet.index',compact('user', 'userTransations'));
    }
    public function transferToWallet(Request $request) {
        $user = authUser();
        $latestPayout = $user->payouts->where('income_type', $request->income_type)->whereNull('is_requested')->first();
        if ($latestPayout) {
            $amount = $latestPayout->amount;
            if ($request->amount >= 10 && $amount >= 10) {
                $walletTrans = WalletTransaction::create([
                    'user_id'  => authUser()->member_id,
                    'amount'   => $amount,
                    'keyword'  => 'self_transfer_'.$request->income_type,
                ]);

                \App\Models\UnifiedTransaction::create([
                    'user_id'          => $user->id,
                    'amount'           => $amount,
                    'transaction_type' => 'Credit',
                    'category'         => 'Income Transfer',
                    'status'           => 'Completed',
                    'description'      => 'Income transfer to Wallet: ' . ucwords(str_replace('_', ' ', $request->income_type)),
                ]);

                $latestPayout->is_requested = Carbon::now();
                $latestPayout->save();
                \Session::flash('success','Success|Income transfer to Wallet successfully!');
                return back();
            }
            \Session::flash('error','Error|Minimum amount to transfer should be $10!');
            return redirect()->back();
        }
        \Session::flash('error','Error|Your Income Not Found!');
        return redirect()->back();

    }

    public function walletTransaction(Request $request) {
        $type = $request->type ?? null;
        $amount = $request->amount ?? 0;
        if ($type == 'transfer') {
            $request->validate([
                'to_user' => 'required',
                'amount' => 'required|numeric|min:10',
            ]);
            $skip_otp = ($request->has('skip_otp') && $request->skip_otp == 1) ? true : false;
            
            if (!$skip_otp) {
                if ($request->otp == null) {
                    return response()->json(['errors' => ['otp' => ['OTP is required']]], 422);
                }
            }

            $user = authUser();

            // 1. Prevent self-transfer
            if ($user->member_id === $request->to_user) {
                return response()->json(['errors' => ['to_user' => ['You cannot transfer money to yourself.']]], 422);
            }

            // 2. Verify receiver existence
            $to_user = $request->to_user;
            $transferedUser = User::where('member_id', $to_user)->first();
            if (!$transferedUser) {
                return response()->json(['errors' => ['to_user' => ['Receiver Member ID not found.']]], 422);
            }

            // Downline Check: receiver must have sender's id in their parent_string
            $parents = explode(',', $transferedUser->parent_string ?? '');
            if (!in_array($user->id, $parents)) {
                return response()->json(['errors' => ['to_user' => ['You can only transfer funds to users in your downline.']]], 422);
            }

            // 4. Verify Active Level Reserve & Transferable Balance
            $activeCategory = $user->agentCategory();
            $reserveAmount = 0;
            $totalBalance = $user->income_balance;
            $availableBalance = $totalBalance;

            if ($amount > $availableBalance) {
                return response()->json([
                    'errors' => [
                        'amount' => [
                            "Insufficient transferable balance. You can only transfer up to $" . number_format($availableBalance, 2) . "."
                        ]
                    ]
                ], 422);
            }

            DB::beginTransaction();
            try {
                if ($skip_otp == false) {
                    $varifiedResponse = checkOtp($request->otp);
                    if ($varifiedResponse['status'] == false) {
                        return response()->json(['errors' => ['otp' => [$varifiedResponse['message']]]], 422);
                    }
                }

                $transfered = WalletTransaction::create([
                    'user_id'       => $user->member_id,
                    'keyword'       => 'transfer',
                    'transfered_to' => $transferedUser->member_id,
                    'amount'        => $amount,
                ]);

                \App\Models\UnifiedTransaction::create([
                    'user_id'          => $user->id,
                    'amount'           => $amount,
                    'transaction_type' => 'Debit',
                    'category'         => 'Fund Transfer',
                    'status'           => 'Completed',
                    'description'      => 'Transfer to user ' . $to_user,
                ]);

                \App\Models\UnifiedTransaction::create([
                    'user_id'          => $transferedUser->id,
                    'from_user_id'     => $user->id,
                    'amount'           => $amount,
                    'transaction_type' => 'Credit',
                    'category'         => 'Fund Transfer',
                    'status'           => 'Completed',
                    'description'      => 'Received from user ' . $user->member_id,
                ]);

                if ($skip_otp == false) {
                    $otp = WalletOtp::where('otp', $request->otp)->first();
                    if ($otp) {
                        $otp->update(['is_used' => 1]);
                    }
                }

                DB::commit();
                return ['status' => true, 'modal' => true, 'refresh' => true, 'message' => 'Transfer successfully', 'code' => 200];
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['errors' => ['amount' => [$th->getMessage()]]], 422);
            }
        } else if ($type == 'buy_pin') {
            $joiningKit = JoiningKit::find(decrypt($request->joining_kit_id));
            if ($joiningKit != null) {
                $pin = random_int(1111111111,9999999999);
                $kitAmount = $joiningKit->amount ?? 0;

                if (authUser()->walletIncomesByKey() < $kitAmount) {
                    \Session::flash('error', 'Error|Insufficient wallet balance!');
                    return redirect()->back();
                }

                $pinModel = new Epin;
                $pinModel->joining_kit    = $joiningKit->id;
                $pinModel->pin_no         = $pin;
                $pinModel->transfer_to    = authUser()->id;
                $pinModel->transferred_at = Carbon::now();
                $pinModel->save();

                WalletTransaction::create([
                    'user_id'  => authUser()->member_id,
                    'keyword'  => 'buy_pin',
                    'pin_no'   => $pin,
                    'amount'   => $kitAmount,
                ]);

                \App\Models\UnifiedTransaction::create([
                    'user_id'          => authUser()->id,
                    'amount'           => $kitAmount,
                    'transaction_type' => 'Debit',
                    'category'         => 'Pin Purchase',
                    'status'           => 'Completed',
                    'description'      => 'Pin purchase: ' . $pin,
                ]);
                \Session::flash('success', 'Success|Pin Purchased Sucessfully ');
                return redirect()->back();
            }
            \Session::flash('error', 'Error|Kit not Found!');
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function walletTransfer(Request $request) {
        $user = authUser();
        if ($user == null) {
            abort(403, 'User Not Found');
        }



        $activeCategory = $user->agentCategory();
        $reserveAmount = 0;
        $totalBalance = $user->income_balance;
        $transferable = $totalBalance;

        if ($request->isMethod('post')) {
            $request->validate([
                'to_user' => 'required',
                'amount' => 'required|numeric|min:10',
            ]);

            $skip_otp = ($request->has('skip_otp') && $request->skip_otp == 1) ? true : false;
            
            if (!$skip_otp) {
                if ($request->otp == null) {
                    return response()->json(['errors' => ['otp' => ['OTP is required']]], 422);
                }
            }

            // 1. Prevent self-transfer
            if ($user->member_id === $request->to_user) {
                return response()->json(['errors' => ['to_user' => ['You cannot transfer money to yourself.']]], 422);
            }

            // 2. Verify receiver existence
            $to_user = $request->to_user;
            $transferedUser = User::where('member_id', $to_user)->first();
            if (!$transferedUser) {
                return response()->json(['errors' => ['to_user' => ['Receiver Member ID not found.']]], 422);
            }

            // Downline Check: receiver must have sender's id in their parent_string
            $parents = explode(',', $transferedUser->parent_string ?? '');
            if (!in_array($user->id, $parents)) {
                return response()->json(['errors' => ['to_user' => ['You can only transfer funds to users in your downline.']]], 422);
            }

            // 4. Verify Transferable Balance
            if ($request->amount > $transferable) {
                return response()->json([
                    'errors' => [
                        'amount' => [
                            "Insufficient transferable balance. You can only transfer up to $" . number_format($transferable, 2) . "."
                        ]
                    ]
                ], 422);
            }

            DB::beginTransaction();
            try {
                if ($skip_otp == false) {
                    $varifiedResponse = checkOtp($request->otp);
                    if ($varifiedResponse['status'] == false) {
                        return response()->json(['errors' => ['otp' => [$varifiedResponse['message']]]], 422);
                    }
                }

                $amount = $request->amount;
                $fee = 0;
                $netAmount = $amount;

                $transfered = WalletTransaction::create([
                    'user_id'       => $user->member_id,
                    'keyword'       => 'transfer',
                    'transfered_to' => $transferedUser->member_id,
                    'amount'        => $amount,
                ]);
                $transfered->update([
                    'admin_charges' => $fee,
                    'net_amount'    => $netAmount,
                ]);

                \App\Models\UnifiedTransaction::create([
                    'user_id'          => $user->id,
                    'amount'           => $amount,
                    'transaction_type' => 'Debit',
                    'category'         => 'Fund Transfer',
                    'status'           => 'Completed',
                    'description'      => 'Transfer to user ' . $to_user,
                ]);

                \App\Models\UnifiedTransaction::create([
                    'user_id'          => $transferedUser->id,
                    'from_user_id'     => $user->id,
                    'amount'           => $netAmount,
                    'transaction_type' => 'Credit',
                    'category'         => 'Fund Transfer',
                    'status'           => 'Completed',
                    'description'      => 'Received from user ' . $user->member_id,
                ]);

                // Also create deposit transaction & tracking entries for the receiver so they are recorded as deposits
                $depositTx = Transaction::create([
                    'user_id'          => $transferedUser->id,
                    'wallet_address'   => 'transfer_from_' . $user->member_id,
                    'type'             => 'deposit',
                    'amount'           => $netAmount,
                    'transaction_fees' => $fee,
                    'net_amount'       => $netAmount,
                    'transaction_hash' => 'transfer_' . time() . '_' . uniqid(),
                    'status'           => 'success',
                ]);

                $depositTracking = TransactionTracking::create([
                    'user_id'     => $transferedUser->id,
                    'type'        => 'deposit',
                    'keyword'     => 'Transaction',
                    'amount'      => $amount,
                    'net_amount'  => $netAmount,
                    'related_id'  => $depositTx->id,
                    'remark'      => 'Deposit Amount received via Fund Transfer from ' . $user->member_id,
                ]);



                // If the receiver was unpaid, trigger direct income and cashback benefits
                if ($transferedUser->is_paid == 0) {
                    $directIncomeAmount = 0;
                    if ($netAmount >= 100) {
                        $directIncomeAmount = 15;
                    } elseif ($netAmount >= 50) {
                        $directIncomeAmount = 7;
                    }

                    if ($directIncomeAmount > 0 && $transferedUser->sponsor_id) {
                        $sponsor = User::where('member_id', $transferedUser->sponsor_id)->first();
                        if ($sponsor) {
                            $payout = \App\Models\UserPayout::create([
                                'user_id'       => $sponsor->id,
                                'income_type'   => 'direct_income',
                                'amount'        => $directIncomeAmount,
                                'tds'           => 0,
                                'admin_charges' => 0,
                                'net_amount'    => $directIncomeAmount,
                            ]);

                            $payoutTracking = TransactionTracking::create([
                                'user_id'     => $sponsor->id,
                                'type'        => 'direct_income',
                                'keyword'     => 'Reward',
                                'amount'      => $directIncomeAmount,
                                'net_amount'  => $directIncomeAmount,
                                'related_id'  => $payout->id,
                                'from_user'   => $transferedUser->id,
                                'remark'      => "Direct Income from {$transferedUser->member_id}'s first deposit of \${$netAmount} via Fund Transfer",
                            ]);

                            \App\Models\UnifiedTransaction::create([
                                'user_id'          => $sponsor->id,
                                'from_user_id'     => $transferedUser->id,
                                'amount'           => $directIncomeAmount,
                                'transaction_type' => 'Credit',
                                'category'         => 'Direct Income',
                                'status'           => 'Completed',
                                'description'      => "Direct Income from {$transferedUser->member_id}'s first deposit of \${$netAmount} via Fund Transfer",
                                'created_at'       => $payoutTracking->created_at,
                            ]);
                        }
                    }

                    // 5% Cashback
                    $cashbackAmount = round($netAmount * 0.05, 2);
                    if ($cashbackAmount > 0) {
                        $cashbackPayout = \App\Models\UserPayout::create([
                            'user_id'       => $transferedUser->id,
                            'income_type'   => 'first_deposit_cashback',
                            'amount'        => $cashbackAmount,
                            'tds'           => 0,
                            'admin_charges' => 0,
                            'net_amount'    => $cashbackAmount,
                        ]);

                        $cashbackTracking = TransactionTracking::create([
                            'user_id'     => $transferedUser->id,
                            'type'        => 'first_deposit_cashback',
                            'keyword'     => 'Reward',
                            'amount'      => $cashbackAmount,
                            'net_amount'  => $cashbackAmount,
                            'related_id'  => $cashbackPayout->id,
                            'remark'      => "5% First Deposit Cashback bonus on \${$netAmount} deposit via Fund Transfer",
                        ]);

                        \App\Models\UnifiedTransaction::create([
                            'user_id'          => $transferedUser->id,
                            'amount'           => $cashbackAmount,
                            'transaction_type' => 'Credit',
                            'category'         => 'Bonus Income',
                            'status'           => 'Completed',
                            'description'      => "5% First Deposit Cashback bonus on \${$netAmount} deposit via Fund Transfer",
                            'created_at'       => $cashbackTracking->created_at,
                        ]);
                    }

                    $transferedUser->update([
                        'is_paid' => 1,
                        'user_icon' => 'userpaid.png',
                        'email_verified_at' => $transferedUser->email_verified_at ?? now()
                    ]);
                }

                if ($skip_otp == false) {
                    $otp = WalletOtp::where('otp', $request->otp)->first();
                    if ($otp) {
                        $otp->update(['is_used' => 1]);
                    }
                }

                DB::commit();
                return ['status' => true, 'refresh' => true, 'message' => 'Transfer successfully', 'code' => 200];
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['errors' => ['amount' => [$th->getMessage()]]], 422);
            }
        }

        return view('wallet.transfer', compact('user', 'transferable', 'reserveAmount', 'totalBalance', 'activeCategory'));
    }
}
