<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnifiedTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class IncomeFixController extends Controller
{
    /**
     * Delete incorrect transactions and regenerate daily ROI/IB incomes.
     */
    public function generateSkippedIncomes(Request $request)
    {
        // Prevent timeout for long-running generation
        set_time_limit(0);

        // Get parameters with defaults
        $startDateStr = $request->query('start_date', '2026-07-02');
        $endDateStr = $request->query('end_date', '2026-07-03');
        $deleteDateStr = null;

        try {
            $start = Carbon::parse($startDateStr);
            $end = Carbon::parse($endDateStr);
            $delete = $deleteDateStr ? Carbon::parse($deleteDateStr) : null;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid date format: ' . $e->getMessage()
            ], 400);
        }

        if ($start->greaterThan($end)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Start date cannot be greater than end date.'
            ], 400);
        }

        $log = [];

        // 1. Delete incorrect records for the specified delete date
        if ($delete) {
            $targetDeleteDate = $delete->toDateString();
            
            // Build the list of categories to delete
            $categoriesToDelete = [
                'Daily Profit Income',
                'Daily ROI Income',
                'Team Profit Income'
            ];
            for ($i = 1; $i <= 10; $i++) {
                $categoriesToDelete[] = "Level {$i} Income";
            }

            // Find all matching transactions for logging before deletion
            $matchingTransactions = UnifiedTransaction::whereDate('created_at', $targetDeleteDate)
                ->whereIn('category', $categoriesToDelete)
                ->get();

            $deletedCount = $matchingTransactions->count();
            $deletedSum = $matchingTransactions->sum('amount');

            // Delete the records
            UnifiedTransaction::whereDate('created_at', $targetDeleteDate)
                ->whereIn('category', $categoriesToDelete)
                ->delete();

            $log['deletion'] = [
                'target_date' => $targetDeleteDate,
                'deleted_count' => $deletedCount,
                'deleted_total_amount' => round($deletedSum, 2),
                'categories' => $categoriesToDelete
            ];
        } else {
            $log['deletion'] = [
                'target_date' => null,
                'message' => 'No deletion target specified.'
            ];
        }

        // 2. Generate incomes day-by-day
        $currentDate = $start->copy();
        $generationSummary = [];

        while ($currentDate->lessThanOrEqualTo($end)) {
            $dateStr = $currentDate->toDateString();
            
            // Track the maximum transaction ID before starting generation for the day
            $lastTxId = UnifiedTransaction::max('id') ?? 0;

            // Mock the system date using Carbon
            Carbon::setTestNow($currentDate);

            try {
                // Call the existing daily ROI generation command
                Artisan::call('generate:daily-roi');
                $artisanOutput = Artisan::output();

                // Get new transactions created in this specific run
                $newTransactions = UnifiedTransaction::where('id', '>', $lastTxId)
                    ->with('user')
                    ->get();

                $roiCount = 0;
                $roiSum = 0;
                $levelCount = 0;
                $levelSum = 0;
                $details = [];

                foreach ($newTransactions as $tx) {
                    $username = $tx->user->member_id ?? 'Unknown';
                    if (in_array($tx->category, ['Daily Profit Income', 'Daily ROI Income'])) {
                        $roiCount++;
                        $roiSum += $tx->amount;
                    } else {
                        $levelCount++;
                        $levelSum += $tx->amount;
                    }
                    $details[] = [
                        'id' => $tx->id,
                        'user' => $username,
                        'category' => $tx->category,
                        'amount' => $tx->amount,
                        'description' => $tx->description
                    ];
                }

                $generationSummary[$dateStr] = [
                    'status' => 'success',
                    'artisan_output' => trim($artisanOutput),
                    'total_created' => $newTransactions->count(),
                    'roi' => [
                        'count' => $roiCount,
                        'amount' => round($roiSum, 2)
                    ],
                    'level_income' => [
                        'count' => $levelCount,
                        'amount' => round($levelSum, 2)
                    ],
                    'transactions' => $details
                ];
            } catch (\Exception $e) {
                $generationSummary[$dateStr] = [
                    'status' => 'failed',
                    'error' => $e->getMessage()
                ];
            }

            // Move to the next day
            $currentDate->addDay();
        }

        // Reset the mocked system time back to real time
        Carbon::setTestNow();

        $log['generation'] = $generationSummary;

        return response()->json([
            'status' => 'success',
            'message' => 'Skipped incomes processed successfully.',
            'results' => $log
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
