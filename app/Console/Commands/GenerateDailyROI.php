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
    protected $signature = 'generate:daily-roi {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly ROI settlement for active users at month-end';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!now()->isLastOfMonth() && !$this->option('force')) {
            $this->info("Today is not the last day of the month. Settle only at month-end. Use --force to run anyway.");
            return;
        }

        $this->info("Starting Monthly ROI Settlement...");
        
        $users = User::where('is_paid', 1)->get();
        $count = 0;

        foreach ($users as $user) {
            $agentCategory = $user->agentCategory();
            if (!$agentCategory) {
                continue;
            }

            // Check if monthly ROI has already been settled for this month
            $alreadySettled = UnifiedTransaction::where('user_id', $user->id)
                ->where('category', 'Daily ROI Income')
                ->where('status', 'Completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->exists();

            if ($alreadySettled) {
                $this->info("User ID {$user->id} already settled for this month. Skipping.");
                continue;
            }

            // Calculate the user's monthly settlement amount (pro-rata based on deposit active days)
            $settlementAmount = $user->getCurrentMonthAccumulatedROI();

            if ($settlementAmount <= 0) {
                continue;
            }

            // 200% Monthly Cap Check (or cap based on total deposits)
            $totalDeposit = $user->getTotalDeposits();
            $maxMonthlyLimit = $totalDeposit * 2;
            
            // ROI earned THIS MONTH (from database transactions)
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

            // Adjust Settlement if it exceeds the cap
            if (($currentMonthROI + $settlementAmount) > $maxMonthlyLimit) {
                $settlementAmount = round($maxMonthlyLimit - $currentMonthROI, 2);
            }

            if ($settlementAmount > 0) {
                // Credit ROI
                UnifiedTransaction::create([
                    'user_id'          => $user->id,
                    'amount'           => $settlementAmount,
                    'transaction_type' => 'Credit',
                    'category'         => 'Daily ROI Income',
                    'status'           => 'Completed',
                    'description'      => 'Monthly ROI settlement for ' . now()->format('F Y'),
                ]);

                // Distribute Level ROI Income to uplines based on this settled amount
                RewardHelper::processLevelROIIncome($user, $settlementAmount);
                
                $count++;
            }
        }

        $this->info("Processed $count users for monthly ROI settlement.");
    }
}
