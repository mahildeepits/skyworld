<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\AllUsersPayoutsDataTable;
use App\Models\UserPayout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\UserPayoutsDataTable;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\WithdrawalPaidEmail;

class UserPayoutController extends Controller
{
    public function index(UserPayoutsDataTable $dataTable){
        $type = request()->get('type') ?? null;
        $user_id = authUser('member')->id ?? null;
        return $dataTable->with(['type' => $type,'user_id' => $user_id])->render('user-payouts.index',compact('type'));
    }
    public function payoutRequest($id){
        $payout = UserPayout::findOrfail(decrypt($id));
        try {
            if($payout->update(['is_requested' => now()])){
                return back()->with('success','Success|Request is generated successfully');
            }
        } catch (\Throwable $th) {
            return back()->with('error','Error|'.$th->getMessage());
        }
    }
    public function allPayouts(AllUsersPayoutsDataTable $dataTable){
        $type = request()->get('type') ?? null;
        return $dataTable->with(['type' => $type])->render('admin.user-payouts.index',compact('type'));
    }
    public function requestedPayouts(AllUsersPayoutsDataTable $dataTable){
        return $dataTable->render('admin.user-payouts.index');
    }
    public function payPayoutsView($id){
        $payout = Transaction::find(decrypt($id));
        if($payout == null){
            return ['status' => false,'message' => 'Payout not found','code' => 400];
        }
        $html = view('admin.user-payouts.transaction',compact('id','payout'))->render();
        return ['status' => true,'message' => 'Working','html' => $html,'code' => 200];
    }
    public function payPayouts(Request $request,$id){
        $request->validate([
            'transaction_hash' => 'required|unique:transactions,transaction_hash',
        ]);
        $payout = Transaction::findOrFail(decrypt($id));
        $data = [
            'transaction_hash' => $request->transaction_hash,
            'status' => 'success',
        ];
        try {
            DB::beginTransaction();
            if($payout->update($data)){
                // Sync with UnifiedTransaction (Robust search with related_id)
                $unified = \App\Models\UnifiedTransaction::where('user_id', $payout->user_id)
                    ->where('category', 'Withdrawal')
                    ->where('related_id', $payout->id)
                    ->first();

                if (!$unified) {
                    $unified = \App\Models\UnifiedTransaction::where('user_id', $payout->user_id)
                        ->where('category', 'Withdrawal')
                        ->where('amount', $payout->amount)
                        ->where('status', 'Pending')
                        ->latest()
                        ->first();
                }

                if ($unified) {
                    $unified->update(['status' => 'Completed', 'tx_hash' => $request->transaction_hash]);
                }

                DB::commit();

                // Send Email Notification to User
                try {
                    Mail::to($payout->user->email)->send(new WithdrawalPaidEmail($payout));
                } catch (\Exception $e) {
                    // Log error or ignore to prevent breaking the response if mail failing
                    \Log::error("Withdrawal Email failed: " . $e->getMessage());
                }

                return ['status' => true,'message' => 'The withdrawal request is paid','modal' => true,'code' => 200];
            }
            throw new \Exception("Error Processing Request", 1);
            
        } catch (\Throwable $th) {
            return ['status' => false,'message' => $th->getMessage(),'code' => 400];
        }
    }
    public function rejectPayouts(Request $request, $id) {
        $payout = Transaction::findOrFail(decrypt($id));
        
        try {
            DB::beginTransaction();
            
            // 1. Update Transaction Status
            $payout->update(['status' => 'Rejected']);

            // 2. Update UnifiedTransaction Status (Robust search with related_id)
            $unified = \App\Models\UnifiedTransaction::where('user_id', $payout->user_id)
                ->where('category', 'Withdrawal')
                ->where('related_id', $payout->id)
                ->first();

            if (!$unified) {
                $unified = \App\Models\UnifiedTransaction::where('user_id', $payout->user_id)
                    ->where('category', 'Withdrawal')
                    ->where('amount', $payout->amount)
                    ->whereIn('status', ['Pending', 'Completed']) // Support fixing wrongly auto-completed records
                    ->latest()
                    ->first();
            }

            if ($unified) {
                $unified->update(['status' => 'Rejected']);
            }

            // 3. Refund Amount
            \App\Models\UnifiedTransaction::create([
                'user_id'          => $payout->user_id,
                'amount'           => $payout->amount,
                'transaction_type' => 'Credit',
                'category'         => 'Withdrawal Refund',
                'status'           => 'Completed',
                'description'      => 'Refund for Rejected Withdrawal Request #' . $payout->id,
            ]);

            DB::commit();
            if (request()->ajax()) {
                return ['status' => true, 'message' => 'Withdrawal request rejected and refunded', 'code' => 200, 'refresh' => true];
            }
            return back()->with('success', 'Success|Withdrawal request rejected and refunded');

        } catch (\Throwable $th) {
            DB::rollBack();
            if (request()->ajax()) {
                return ['status' => false, 'message' => $th->getMessage(), 'code' => 400];
            }
            return back()->with('error', 'Error|' . $th->getMessage());
        }
    }
}
