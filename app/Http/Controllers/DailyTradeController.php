<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DailyTrade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DailyTradeController extends Controller
{
    public function index()
    {
        $user = auth('member')->user();
        $todayTrade = DailyTrade::where('user_id', $user->id)
            ->where('trade_date', now()->toDateString())
            ->first();

        $agentCategory = $user->agentCategory();
        $isEligible = ($user->is_paid == 1 && $agentCategory);
        
        $totalWalletBalance = $user->walletIncomesByKey('deposits');
        $massiveOrderRate = $agentCategory ? $agentCategory->massive_order_rate : 0;
        
        // Find next level for capping limit
        $limit = null;
        if ($agentCategory) {
            $nextCategory = \App\Models\AgentCategory::where('unlock_balance', '>', $agentCategory->unlock_balance)
                ->orderBy('unlock_balance', 'asc')
                ->first();
            $limit = $nextCategory ? $nextCategory->unlock_balance : null;
        }

        $remainingSeconds = 0;
        if ($todayTrade && $todayTrade->status == 'pending') {
            $elapsedSeconds = now()->diffInSeconds($todayTrade->created_at);
            if ($elapsedSeconds >= 40) {
                // Auto-complete if user refreshes and time is up
                $this->finalizeTrade($todayTrade);
                $todayTrade->refresh();
            } else {
                $remainingSeconds = 40 - $elapsedSeconds;
            }
        }

        // Calculate Trading Balance (Current or Locked)
        if ($todayTrade) {
            $tradingBalance = $todayTrade->base_amount;
            $massiveOrderRate = $todayTrade->rate;
        } else {
            $tradingBalance = $limit ? min($totalWalletBalance, $limit) : $totalWalletBalance;
        }
        
        return view('dashboard.trade', compact('todayTrade', 'isEligible', 'agentCategory', 'massiveOrderRate', 'tradingBalance', 'totalWalletBalance', 'remainingSeconds', 'limit'));
    }

    public function store(Request $request)
    {
        $user = auth('member')->user();
        $agentCategory = $user->agentCategory();

        // Check eligibility
        if ($user->is_paid != 1 || !$agentCategory) {
            return back()->with('error', 'You are not eligible for daily trade. Please active your package first.');
        }

        // Check if already traded today
        $alreadyTraded = DailyTrade::where('user_id', $user->id)
            ->where('trade_date', now()->toDateString())
            ->exists();

        if ($alreadyTraded) {
            return back()->with('error', 'You have already traded today.');
        }

        // Calculate Trading Base with Capping
        $totalWalletBalance = $user->walletIncomesByKey('deposits');
        $nextCategory = \App\Models\AgentCategory::where('unlock_balance', '>', $agentCategory->unlock_balance)
            ->orderBy('unlock_balance', 'asc')
            ->first();
        $limit = $nextCategory ? $nextCategory->unlock_balance : null;
        
        $tradingBase = $limit ? min($totalWalletBalance, $limit) : $totalWalletBalance;
        $tradingBase = round($tradingBase, 2);

        $rate = $agentCategory->massive_order_rate;
        $profit = round(($tradingBase * $rate) / 100, 2);

        // CREATE TRADE IN PENDING STATE
        $trade = DailyTrade::create([
            'user_id' => $user->id,
            'trade_date' => now()->toDateString(),
            'rate' => $rate,
            'base_amount' => $tradingBase,
            'profit_amount' => $profit,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Trade started. Market analysis in progress...');
    }

    public function completeTrade(Request $request)
    {
        $user = auth('member')->user();
        $trade = DailyTrade::where('user_id', $user->id)
            ->where('trade_date', now()->toDateString())
            ->where('status', 'pending')
            ->first();

        if (!$trade) {
            return response()->json(['success' => false, 'message' => 'No active trade found.']);
        }

        if (now()->diffInSeconds($trade->created_at) < 40) {
            return response()->json(['success' => false, 'message' => 'Trade is still processing.']);
        }

        $this->finalizeTrade($trade);

        return response()->json(['success' => true, 'message' => 'Trade completed successfully.']);
    }

    private function finalizeTrade($trade)
    {
        if ($trade->status !== 'pending') return;

        \DB::transaction(function() use ($trade) {
            $trade->status = 'completed';
            $trade->save();

            $user = $trade->user;
            $profit = $trade->profit_amount;
            $tradingBase = $trade->base_amount;
            $rate = $trade->rate;

            if ($profit > 0) {
                \App\Models\UnifiedTransaction::create([
                    'user_id'          => $user->id,
                    'amount'           => $profit,
                    'transaction_type' => 'Credit',
                    'category'         => 'Trade Income',
                    'status'           => 'Completed',
                    'description'      => 'Daily Trade Profit @ ' . $rate . '% on Trading Base $' . number_format($tradingBase, 2),
                ]);

                if($user->reward_points == null || $user->reward_points == 0){
                    $user->reward_points = 5;
                }else{
                    $user->reward_points += 5;
                }
                $user->save();

                \App\Helpers\RewardHelper::processTeamProfitIncome($user, $profit, 'Trade Income');
            }
        });
    }
}
