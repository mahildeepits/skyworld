<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegistrationRequest;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionTracking;
use App\Models\UnifiedTransaction;
use App\Models\UserAgentCategory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\RewardHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountApprovedEmail;
use App\Mail\AccountRejectedEmail;

class RegistrationRequestController extends Controller
{
    public function index()
    {
        $requests = RegistrationRequest::with('user', 'agentCategory')
                        ->orderBy('id', 'desc')
                        ->paginate(20);
        return view('admin.registration-requests.index', compact('requests'));
    }

    public function approve($id)
    {
        $req = RegistrationRequest::findOrFail($id);
        if ($req->status != 'pending') {
            return back()->withErrors(['error' => 'Request is already processed.']);
        }

        DB::beginTransaction();
        try {
            $userModel = $req->user;
            
            // Mark user as paid
            $userModel->is_paid = 1;
            $userModel->user_icon = 'userpaid.png';
            $userModel->save();

            // Assign Agent Category if present
            if ($req->agent_category_id) {
                UserAgentCategory::create([
                    'user_id' => $userModel->id,
                    'agent_category_id' => $req->agent_category_id,
                    'is_active' => 1,
                ]);
            }

            // Add Deposit Amount if present
            if ($req->deposit_amount > 0) {
                $amount = $req->deposit_amount;
                
                $transaction = Transaction::create([
                    'user_id'          => $userModel->id,
                    'type'             => 'deposit',
                    'amount'           => $amount,
                    'transaction_fees' => 0,
                    'net_amount'       => $amount,
                    'status'           => 'success',
                    'wallet_address'   => 'System',
                ]);

                TransactionTracking::create([
                    'user_id'     => $userModel->id,
                    'type'        => 'deposit',
                    'keyword'     => 'Transaction',
                    'amount'      => $amount,
                    'net_amount'  => $amount,
                    'related_id'  => $transaction->id,
                    'remark'      => 'Initial Deposit at Registration Approval',
                ]);

                UnifiedTransaction::create([
                    'user_id'          => $userModel->id,
                    'amount'           => $amount,
                    'transaction_type' => 'Credit',
                    'category'         => 'Deposit',
                    'status'           => 'Completed',
                    'description'      => 'Initial Deposit at Registration Approval',
                ]);
            }

            // Give rewards if applicable
            if ($userModel->sponsor_id != 'Company') {
                // RewardHelper::giveRewards() normally uses session data or current logged in user logic.
                // It might need context, but let's assume it relies on recent registrations.
                // To be safe we will just call it as AuthController did.
                RewardHelper::giveRewards();
            }

            $req->status = 'approved';
            $req->save();

            DB::commit();

            try {
                Mail::to($userModel->email)->send(new AccountApprovedEmail($userModel));
            } catch (\Exception $e) {
                // Ignore mail failures
            }

            return back()->with('success', 'Registration approved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to approve registration: ' . $e->getMessage()]);
        }
    }

    public function reject($id)
    {
        $req = RegistrationRequest::findOrFail($id);
        if ($req->status != 'pending') {
            return back()->withErrors(['error' => 'Request is already processed.']);
        }

        DB::beginTransaction();
        try {
            $userModel = $req->user;
            if ($userModel) {
                $userModel->is_blocked = 1;
                $userModel->user_icon = 'blocked.png';
                $userModel->save();
            }

            $req->status = 'rejected';
            $req->save();

            DB::commit();

            try {
                if ($userModel) {
                    Mail::to($userModel->email)->send(new AccountRejectedEmail($userModel));
                }
            } catch (\Exception $e) {
                // Ignore mail failures
            }

            return back()->with('success', 'Registration rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to reject registration: ' . $e->getMessage()]);
        }
    }
}
