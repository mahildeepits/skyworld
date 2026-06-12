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
use App\Models\AgentCategory;
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
        $categories = AgentCategory::all();
        return view('admin.registration-requests.index', compact('requests', 'categories'));
    }

    public function approve(Request $request, $id)
    {
        $req = RegistrationRequest::findOrFail($id);
        if ($req->status != 'pending') {
            return back()->withErrors(['error' => 'Request is already processed.']);
        }

        $request->validate([
            'deposit_amount' => 'nullable|numeric|min:0',
            'agent_category_id' => 'nullable|exists:agent_categories,id',
        ]);

        DB::beginTransaction();
        try {
            $userModel = $req->user;
            
            // Mark user as paid
            $userModel->is_paid = 1;
            $userModel->user_icon = 'userpaid.png';
            $userModel->save();

            // Read inputs
            $depositAmount = $request->input('deposit_amount', 0);
            $agentCategoryId = $request->input('agent_category_id');

            // Update registration request data
            $req->deposit_amount = $depositAmount;
            $req->agent_category_id = $agentCategoryId;

            // Assign Agent Category if present
            if ($agentCategoryId) {
                UserAgentCategory::create([
                    'user_id' => $userModel->id,
                    'agent_category_id' => $agentCategoryId,
                    'is_active' => 1,
                ]);
            }

            // Add Deposit Amount if present
            if ($depositAmount > 0) {
                $transaction = Transaction::create([
                    'user_id'          => $userModel->id,
                    'type'             => 'deposit',
                    'amount'           => $depositAmount,
                    'transaction_fees' => 0,
                    'net_amount'       => $depositAmount,
                    'status'           => 'success',
                    'wallet_address'   => 'System',
                ]);

                TransactionTracking::create([
                    'user_id'     => $userModel->id,
                    'type'        => 'deposit',
                    'keyword'     => 'Transaction',
                    'amount'      => $depositAmount,
                    'net_amount'  => $depositAmount,
                    'related_id'  => $transaction->id,
                    'remark'      => 'Initial Deposit at Registration Approval',
                ]);

                UnifiedTransaction::create([
                    'user_id'          => $userModel->id,
                    'amount'           => $depositAmount,
                    'transaction_type' => 'Credit',
                    'category'         => 'Deposit',
                    'status'           => 'Completed',
                    'description'      => 'Initial Deposit at Registration Approval',
                ]);
            }

            // Give rewards if applicable
            if ($userModel->sponsor_id != 'Company') {
                RewardHelper::giveRewards();
            }

            $req->status = 'approved';
            $req->save();

            DB::commit();

            try {
                $req->load('agentCategory'); // Reload relationship to get correct name if changed
                $packageName = $req->agentCategory ? $req->agentCategory->name : 'N/A';
                Mail::to($userModel->email)->send(new AccountApprovedEmail($userModel, $depositAmount, $packageName));
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
