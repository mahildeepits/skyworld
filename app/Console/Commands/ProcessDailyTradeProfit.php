<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessDailyTradeProfit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trade:process-profit';
    protected $description = 'Process all pending daily trades and credit profits to user wallets';

    public function handle()
    {
        $today = now()->toDateString();
        $this->info("Starting processing of daily trades for dates before $today...");
        
        $pendingTrades = \App\Models\DailyTrade::where('status', 'pending')
            ->where('trade_date', '<', $today)
            ->orderBy('trade_date', 'asc')
            ->get();
        
        if ($pendingTrades->isEmpty()) {
            $this->info('No pending trades found for previous dates.');
            return;
        }

        $count = 0;
        foreach ($pendingTrades as $trade) {
            // Double safety: Skip if somehow today's trade got into the list
            if ($trade->trade_date >= $today) {
                continue;
            }

            $user = $trade->user;
            if (!$user) continue;

            $agentCategory = $user->agentCategory();
            if (!$agentCategory) {
                $trade->delete();
                continue;
            }

            \DB::transaction(function() use ($trade, $user, $agentCategory, &$count) {
                // Correct Trading Base: Prioritize LOCKED amount from trade record
                if ($trade->base_amount > 0) {
                    $tradingBase = $trade->base_amount;
                } else {
                    $packagePrice = $agentCategory->unlock_balance;
                    $pastTradeEarnings = \App\Models\UnifiedTransaction::where('user_id', $user->id)
                        ->where('category', 'Trade Income')
                        ->where('status', 'Completed')
                        ->sum('amount');
                    $tradingBase = round($packagePrice + $pastTradeEarnings, 2);
                }
                
                // Calculate profit
                $profit = round(($tradingBase * $trade->rate) / 100, 2);

                if ($profit > 0) {
                    // 1. Create Unified Transaction
                    \App\Models\UnifiedTransaction::create([
                        'user_id'          => $user->id,
                        'amount'           => $profit,
                        'transaction_type' => 'Credit',
                        'category'         => 'Trade Income',
                        'status'           => 'Completed',
                        'description'      => 'Daily Trade Profit @ ' . $trade->rate . '% on Trading Base $' . number_format($tradingBase, 2) . ' for date ' . $trade->trade_date,
                    ]);

                    // Trigger Team Profit Income for the receiver's uplines
                    \App\Helpers\RewardHelper::processTeamProfitIncome($user, $profit, 'Trade Income');

                    // 2. Update Trade Record
                    $trade->update([
                        'base_amount'   => $tradingBase,
                        'profit_amount' => $profit,
                        'status'        => 'completed',
                    ]);

                    $count++;
                } else {
                    $trade->update([
                        'base_amount'   => $tradingBase,
                        'profit_amount' => 0,
                        'status'        => 'completed',
                    ]);
                }
            });
        }

        $this->info("Processed $count trades successfully.");
    }
}
