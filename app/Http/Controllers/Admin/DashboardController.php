<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\RewardHelper;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionTracking;
use App\Http\Controllers\Controller;
use App\DataTables\TransactionDataTable;
use App\Models\UserPayout;
use App\Models\AgentCategory;
use App\Models\UserAgentCategory;
use App\Models\DepositsTracking;
use App\Models\UnifiedTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class DashboardController extends Controller
{
    public function index()
    {
        $total_revenue = Transaction::where('type', 'deposit')->where('status', 'success')->sum('amount');
        $today_revenue = Transaction::where('type', 'deposit')->where('status', 'success')->whereDate('created_at', \Carbon\Carbon::today())->sum('amount');
        
        $lastMonth = \Carbon\Carbon::now()->subMonth();
        $last_month_revenue = Transaction::where('type', 'deposit')->where('status', 'success')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('amount');

        return view('admin.dashboard.index', compact('total_revenue', 'today_revenue', 'last_month_revenue'));
    }

    public function companyRevenueReport(Request $request)
    {
        $from = $request->from_date;
        $to = $request->to_date;

        $query = Transaction::query()->with('user');

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        // Clone query for summaries to apply same date filters
        $depositQuery = (clone $query)->where('type', 'deposit')->where('status', 'success');
        $withdrawalSuccessQuery = (clone $query)->where('type', 'withdrawl')->whereIn('status', ['success', 'Completed']);
        $withdrawalPendingQuery = (clone $query)->where('type', 'withdrawl')->where('status', 'pending');

        $total_deposit = $depositQuery->sum('amount');
        $total_withdrawal_success = $withdrawalSuccessQuery->sum('amount');
        $total_withdrawal_pending = $withdrawalPendingQuery->sum('amount');

        $transactions = $query->latest()->paginate(50);
        $title = "Company Revenue Report";

        return view('admin.dashboard.revenue_report', compact(
            'transactions', 
            'title', 
            'total_deposit', 
            'total_withdrawal_success', 
            'total_withdrawal_pending',
            'from',
            'to'
        ));
    }
    public function withdrawalRequests(TransactionDataTable $dataTable){
        return $dataTable->render('admin.dashboard.withdrawl_requests');
    }
    public function setPaidForm($id){
        $user = User::find($id);
        return [
            'status' => true,
            'html' => view('admin.dashboard.setpaid',compact('user'))->render()
        ];
    }
    public function setPaidFunction(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::find($id);
        if($user == null){
            return response()->json(['status' => false,'message' => 'User not found.','code' => 400]);
        }
        
        DB::beginTransaction();
        try {
            $amount = round($request->amount, 2);
            $hash = 'ADMIN_SET_PAID_'.strtoupper(bin2hex(random_bytes(6))).'_'.time();
            $network = 'ADMIN';
            $wallet_address = $user?->wallet_addresses['TRC-20'] ?? $user?->wallet_addresses['BEP-20'] ?? 'ADMIN_SET'.rand(00000,99999);

            // ✅ Store deposit
            $transaction = Transaction::create([
                'user_id'          => $user->id,
                'wallet_address'   => $wallet_address,
                'type'             => 'deposit',
                'amount'           => $amount,
                'transaction_fees' => 0,
                'net_amount'       => $amount,
                'transaction_hash' => $hash,
                'status'           => 'success',
            ]);

            $transactionTracking = TransactionTracking::create([
                'user_id'     => $user->id,
                'type'        => 'deposit',
                'keyword'     => 'Transaction',
                'amount'      => $amount,
                'net_amount'  => $amount,
                'related_id'  => $transaction->id,
                'remark'      => 'Deposit Amount manually set by Admin: ' . $hash,
            ]);

            UnifiedTransaction::updateOrCreate(
                [
                    'tx_hash' => $hash,
                ],
                [
                    'user_id'          => $user->id,
                    'amount'           => $amount,
                    'transaction_type' => 'Credit',
                    'category'         => 'Deposit',
                    'status'           => 'Completed',
                    'description'      => 'Manual Deposit confirmed via Admin interface',
                    'created_at'       => $transactionTracking->created_at,
                ]
            );

            DepositsTracking::updateOrCreate(
                [
                    'transaction_hash' => $hash,
                ],
                [
                    'user_id'      => $user->id,
                    'confirmed_at' => now(),
                    'type'         => $network,
                ]
            );

            // ✅ Activate matching package for the user based on deposit amount
            $matchingCategory = AgentCategory::where('unlock_balance', '>=', 50)
                ->where('unlock_balance', '<=', $amount)
                ->orderBy('unlock_balance', 'desc')
                ->first();

            if ($matchingCategory) {
                $existingRecord = UserAgentCategory::where('user_id', $user->id)
                    ->where('agent_category_id', $matchingCategory->id)
                    ->first();

                if ($existingRecord) {
                    if ($existingRecord->is_active == 0) {
                        $existingRecord->update(['is_active' => 1]);
                    }
                } else {
                    $eligibility = RewardHelper::checkPackageEligibility($user, $matchingCategory);
                    if ($eligibility['status']) {
                        UserAgentCategory::create([
                            'user_id'           => $user->id,
                            'agent_category_id' => $matchingCategory->id,
                            'is_active'         => 1,
                        ]);
                        // Only give upgrade income if user was already paid (otherwise first-deposit tiered income applies)
                        if ($user->is_paid == 1) {
                            RewardHelper::packageUpgradeDirectIncome($user, $matchingCategory);
                        }
                    }
                }
            }

            // ✅ Set user status to paid if it's their first deposit or they are currently unpaid
            if ($user->is_paid == 0) {
                // Determine direct income amount for sponsor based on new tiers
                $directIncomeAmount = 0;
                if ($amount >= 500) {
                    $directIncomeAmount = 40;
                } elseif ($amount >= 200) {
                    $directIncomeAmount = 25;
                } elseif ($amount >= 100) {
                    $directIncomeAmount = 15;
                } elseif ($amount >= 50) {
                    $directIncomeAmount = 7;
                }

                if ($directIncomeAmount > 0 && $user->sponsor_id) {
                    $sponsor = User::where('member_id', $user->sponsor_id)->first();
                    if ($sponsor) {
                        $payout = UserPayout::create([
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
                            'from_user'   => $user->id,
                            'remark'      => "Direct Income from {$user->member_id}'s first manual deposit of \${$amount} via Admin",
                        ]);

                        UnifiedTransaction::updateOrCreate(
                            [
                                'user_id'          => $sponsor->id,
                                'from_user_id'     => $user->id,
                                'category'         => 'Direct Income',
                                'amount'           => $directIncomeAmount, 
                                'transaction_type' => 'Credit',
                            ],
                            [
                                'status'      => 'Completed',
                                'description' => "Direct Income from {$user->member_id}'s first manual deposit of \${$amount} via Admin",
                                'created_at'  => $payoutTracking->created_at,
                            ]
                        );

                        // Trigger Team Profit Income for the receiver's uplines
                        // RewardHelper::processTeamProfitIncome($sponsor, $directIncomeAmount, 'Direct Income');
                    }
                }

                // ✅ 5% Cashback for the user on first deposit
                $cashbackAmount = round($amount * 0.05, 2);
                if ($cashbackAmount > 0) {
                    $cashbackPayout = UserPayout::create([
                        'user_id'       => $user->id,
                        'income_type'   => 'first_deposit_cashback',
                        'amount'        => $cashbackAmount,
                        'tds'           => 0,
                        'admin_charges' => 0,
                        'net_amount'    => $cashbackAmount,
                    ]);

                    $cashbackTracking = TransactionTracking::create([
                        'user_id'     => $user->id,
                        'type'        => 'first_deposit_cashback',
                        'keyword'     => 'Reward',
                        'amount'      => $cashbackAmount,
                        'net_amount'  => $cashbackAmount,
                        'related_id'  => $cashbackPayout->id,
                        'remark'      => "5% First Deposit Cashback bonus on \${$amount} manually added deposit",
                    ]);

                    UnifiedTransaction::create([
                        'user_id'          => $user->id,
                        'amount'           => $cashbackAmount,
                        'transaction_type' => 'Credit',
                        'category'         => 'Bonus Income',
                        'status'           => 'Completed',
                        'description'      => "5% First Deposit Cashback bonus on \${$amount} manually added deposit",
                        'created_at'       => $cashbackTracking->created_at,
                    ]);
                    
                    Log::info("✅ ADMIN: First Deposit Cashback of \${$cashbackAmount} credited for User {$user->member_id}");
                }

                $user->update([
                    'is_paid' => 1,
                    'user_icon' => 'userpaid.png',
                    'email_verified_at' => $user->email_verified_at ?? now()
                ]);
            }
            
            // RewardHelper::giveRewards();
            // RewardHelper::ambasdorIncome($user);
            DB::commit();
            return response()->json(['status' => true,'refresh' => true,'message' => 'User paid successfully.','code' => 200]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => false,'message' => $th->getMessage(),'code' => 400]);
        }
    }
}
