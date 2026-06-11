<?php

namespace App\Observers;

use App\Models\TransactionTracking;

class TransactionTrackingObserver
{
    /**
     * Handle the TransactionTracking "created" event.
     */
    public function created(TransactionTracking $transactionTracking): void
    {
        // ❌ Disabled to prevent duplicate entries in UnifiedTransaction.
        // We are now handling UnifiedTransaction creation manually in Services and Commands 
        // to ensure tx_hash and other details are correctly preserved.
        
        /*
        \App\Models\UnifiedTransaction::updateOrCreate(
            [
                'created_at' => $transactionTracking->created_at,
                'user_id' => $transactionTracking->user_id,
                'amount' => $transactionTracking->amount,
            ],
            [
                'transaction_type' => in_array($transactionTracking->type, ['withdrawl', 'withdrawal']) ? 'Debit' : 'Credit',
                'category' => $transactionTracking->type_name,
                'from_user_id' => $transactionTracking->from_user,
                'tx_hash' => null,
                'status' => 'Completed',
                'description' => $transactionTracking->remark,
            ]
        );
        */
    }

    /**
     * Handle the TransactionTracking "updated" event.
     */
    public function updated(TransactionTracking $transactionTracking): void
    {
        //
    }

    /**
     * Handle the TransactionTracking "deleted" event.
     */
    public function deleted(TransactionTracking $transactionTracking): void
    {
        //
    }

    /**
     * Handle the TransactionTracking "restored" event.
     */
    public function restored(TransactionTracking $transactionTracking): void
    {
        //
    }

    /**
     * Handle the TransactionTracking "force deleted" event.
     */
    public function forceDeleted(TransactionTracking $transactionTracking): void
    {
        //
    }
}
