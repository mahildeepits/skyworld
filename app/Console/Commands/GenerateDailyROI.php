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
    protected $description = 'Generate daily ROI settlement for active users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting Daily ROI Settlement...");
        
        $users = User::where('is_paid', 1)->get();
        $count = 0;

        foreach ($users as $user) {
            $agentCategory = $user->agentCategory();
            if (!$agentCategory) {
                continue;
            }

            // Check if daily ROI has already been settled for today
            $alreadySettled = UnifiedTransaction::where('user_id', $user->id)
                ->where('category', 'Daily ROI Income')
                ->where('status', 'Completed')
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if ($alreadySettled) {
                $this->info("User ID {$user->id} already settled for today. Skipping.");
                continue;
            }

            // Calculate the user's daily settlement amount
            $settlementAmount = $user->getCurrentMonthDailyROI();

            if ($settlementAmount <= 0) {
                continue;
            }

            // 200% Monthly Cap Check (or cap based on total deposits)
            $totalDeposit = $user->getTotalDeposits();
            $maxMonthlyLimit = $totalDeposit * 2;
            
            // ROI earned THIS MONTH (from database transactions)
            $currentMonthROI = (float) UnifiedTransaction::where('user_id', $user->id)
                ->where('category', 'Daily Profit Income')
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
                    'category'         => 'Daily Profit Income',
                    'status'           => 'Completed',
                    'description'      => 'Daily Profit settlement for ' . now()->format('Y-m-d'),
                ]);

                // Distribute Level ROI Income to uplines based on this settled amount
                RewardHelper::processLevelROIIncome($user, $settlementAmount);
                
                $count++;
            }
        }

        $this->info("Processed $count users for daily ROI settlement.");
    }
}
