<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UnifiedTransaction;
use App\Helpers\RewardHelper;

class GenerateDailyROI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-roi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily ROI for active users with compounding and capped at 200%';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting Daily ROI Generation...");
        
        $users = User::where('is_paid', 1)->get();
        $count = 0;

        foreach ($users as $user) {
            $agentCategory = $user->agentCategory();
            if (!$agentCategory) {
                continue;
            }

            $rate = (float) $agentCategory->massive_order_rate;
            if ($rate <= 0) {
                continue;
            }

            $totalDeposit = $user->getTotalDeposits();
            if ($totalDeposit <= 0) {
                continue;
            }

            // Calculate total ROI income earned so far (all time)
            $totalROIIncome = (float) UnifiedTransaction::where('user_id', $user->id)
                ->where('category', 'Daily ROI Income')
                ->where('status', 'Completed')
                ->sum('amount');

            // Compound ROI formula: ((Total Deposit + Total ROI Income) * Rate) / 100
            $dailyROI = round((($totalDeposit + $totalROIIncome) * $rate) / 100, 2);

            if ($dailyROI <= 0) {
                continue;
            }

            // 200% Monthly Cap Check
            $maxMonthlyLimit = $totalDeposit * 2;
            
            // ROI earned THIS MONTH
            $currentMonthROI = (float) UnifiedTransaction::where('user_id', $user->id)
                ->where('category', 'Daily ROI Income')
                ->where('status', 'Completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');

            // If already hit the cap, skip
            if ($currentMonthROI >= $maxMonthlyLimit) {
                continue;
            }

            // Adjust Daily ROI if it exceeds the cap
            if (($currentMonthROI + $dailyROI) > $maxMonthlyLimit) {
                $dailyROI = round($maxMonthlyLimit - $currentMonthROI, 2);
            }

            if ($dailyROI > 0) {
                // Credit ROI
                UnifiedTransaction::create([
                    'user_id'          => $user->id,
                    'amount'           => $dailyROI,
                    'transaction_type' => 'Credit',
                    'category'         => 'Daily ROI Income',
                    'status'           => 'Completed',
                    'description'      => 'Daily ROI Income @ ' . $rate . '%',
                ]);

                // Distribute Level ROI Income
                RewardHelper::processLevelROIIncome($user, $dailyROI);
                
                $count++;
            }
        }

        $this->info("Processed $count users for Daily ROI.");
    }
}
