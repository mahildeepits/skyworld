<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminCharge;
use App\Models\KycDoc;
use App\Models\User;
use App\Models\UserBankDetail;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request){
        $kycDetails = collect();
        if($request->has('block_user')){
            User::whereId($request->block_user)->update(['is_blocked'=>1,'user_icon'=>'blocked.png']);
            return redirect()->back();
        }
        if($request->has('unblock_user')){
            User::whereId($request->unblock_user)->update(['is_blocked'=>0,'user_icon'=>'userpaid.png']);
            return redirect()->back();
        }
        if($request->has('kyc_details')){
            $kycDetails = KycDoc::whereUserId($request->kyc_details)->get();
        }
        $usersModel = User::with(['kyc_docs', 'registrationRequest'])->whereIn('role',[3])->orderBy('id','desc')->get();
        return view('admin.users.index',['users'=>$usersModel,'kycDetails'=>$kycDetails]);
    }

    public function editUser(Request $request){
        $userModel = [];
        if($request->has('member_id')){
            $userModelObject = User::with(['profile_rel','bank_details','kyc_docs'])->whereMemberId($request->member_id)->first();
            if($userModelObject != null){
                $userModel = $userModelObject->toArray();
                $userModel['user_id'] = $userModelObject->id;
                $userModel['deposit_amount'] = $userModelObject->getTotalDeposits();
                if($userModelObject->profile_rel != null){
                    $userProfile = $userModelObject->profile_rel->toArray();
                    unset($userProfile['id']);
                    $userModel = array_merge($userModel,$userProfile);
                }
                if($userModelObject->bank_details != null){
                    $bankDetails = $userModelObject->bank_details->toArray();
                    unset($bankDetails['id']);
                    $userModel = array_merge($userModel,$bankDetails);
                    $userModel['bank_nominee_name'] = $userModel['nominee_name'] ?? '';
                    $userModel['bank_nominee_relation'] = $userModel['nominee_relation'] ?? '';
                    $userModel['bank_nominee_dob'] = $userModel['nominee_dob'] ?? '';
                }
                $userModel['kyc_docs'] = $userModelObject->kyc_docs;
            }
        }
        return view('admin.users.profile',compact('userModel'));
    }

    public function profileUpdate(Request $request){
        $userModel = User::find($request->id);

        // Reconcile Deposit Amount if provided
        if ($request->has('deposit_amount')) {
            $newDeposit = (float)$request->deposit_amount;
            $currentDeposit = (float)$userModel->getTotalDeposits();

            if ($newDeposit != $currentDeposit) {
                $transaction = \App\Models\Transaction::where('user_id', $userModel->id)
                    ->where('type', 'deposit')
                    ->latest()
                    ->first();

                $unifiedTransaction = \App\Models\UnifiedTransaction::where('user_id', $userModel->id)
                    ->where('category', 'Deposit')
                    ->latest()
                    ->first();

                $tracking = \App\Models\TransactionTracking::where('user_id', $userModel->id)
                    ->where('type', 'deposit')
                    ->latest()
                    ->first();

                if ($transaction && $unifiedTransaction) {
                    $diff = $newDeposit - $currentDeposit;

                    $transaction->amount += $diff;
                    $transaction->net_amount += $diff;
                    $transaction->save();

                    $unifiedTransaction->amount += $diff;
                    $unifiedTransaction->save();

                    if ($tracking) {
                        $tracking->amount += $diff;
                        $tracking->net_amount += $diff;
                        $tracking->save();
                    }
                } else {
                    if ($newDeposit > 0) {
                        $newTx = \App\Models\Transaction::create([
                            'user_id'          => $userModel->id,
                            'type'             => 'deposit',
                            'amount'           => $newDeposit,
                            'transaction_fees' => 0,
                            'net_amount'       => $newDeposit,
                            'status'           => 'success',
                            'wallet_address'   => 'System',
                        ]);

                        \App\Models\TransactionTracking::create([
                            'user_id'     => $userModel->id,
                            'type'        => 'deposit',
                            'keyword'     => 'Transaction',
                            'amount'      => $newDeposit,
                            'net_amount'  => $newDeposit,
                            'related_id'  => $newTx->id,
                            'remark'      => 'Deposit adjusted by Admin',
                        ]);

                        \App\Models\UnifiedTransaction::create([
                            'user_id'          => $userModel->id,
                            'amount'           => $newDeposit,
                            'transaction_type' => 'Credit',
                            'category'         => 'Deposit',
                            'status'           => 'Completed',
                            'description'      => 'Deposit adjusted by Admin',
                        ]);
                    }
                }
            }
        }

        $userModel->fill($request->except(['password', 'deposit_amount']));
        if($request->has('password') && $request->password != null){
            $userModel->password = \Hash::make($request->password);
            $userModel->enc_password = \Crypt::encrypt($request->password);
        }
        $userModel->save();
        $userProfileModel = UserProfile::whereUserId($userModel->id)->firstOrNew();
        $userProfileModel->fill($request->all());
        $userProfileModel->user_id = $userModel->id;
        $userProfileModel->save();

        if($request->has('kyc_type') && $request->kyc_type != ''){
            $kycDocs = KycDoc::whereUserId($userModel->id)->where('kyc_type',$request->kyc_type)->firstOrNew();
            $kycDocs->user_id = $userModel->id;
            $kycDocs->kyc_type = $request->kyc_type;
            $kycDocs->card_no = $request->card_no;
            
            if($request->hasFile('card_front')){
                $CardFront = "IMG_".time().'_'.rand(11111111,9999999).'.'.$request->file('card_front')->getClientOriginalExtension();
                $request->file('card_front')->move('images/kyc_docs/',$CardFront);
                $kycDocs->card_front = $CardFront;
            }
            if($request->hasFile('card_back')){
                $CardBack = "IMG_".time().'_'.rand(11111111,9999999).'.'.$request->file('card_back')->getClientOriginalExtension();
                $request->file('card_back')->move('images/kyc_docs/',$CardBack);
                $kycDocs->card_back = $CardBack;
            }
            $kycDocs->save();
        }

        \Session::flash('success','Success|User profile updated successfully!');
        return back();
    }

    public function editKyc(Request $request){
        $kycDetails = [];
        $userDetails = [];
        if($request->has('member_id')){
            if($request->member_id == ''){
                return redirect()->back();
            }
        }
        if(($request->has('member_id') && $request->member_id != '') || $request->has('user_id')){
            if($request->has('member_id')){
                $userDetails = User::whereMemberId($request->member_id)->first();
            }
            if($request->has('user_id')){
                $userDetails = User::find($request->user_id);
            }
            $name = $userDetails->name;
            $kycDetails = KycDoc::whereUserId($userDetails->id)->first();
            if($kycDetails != null){
                $kycDetails = $kycDetails->toArray();
            }else{
                $kycDetails = [];
            }
            $kycDetails['name'] = $name;
        }
        return view('admin.users.kyc-details',['kycDetails' => $kycDetails,'user'=>$userDetails]);
    }

    public function updateKycDetails(Request $request){
        $rules = [
            'kyc_type' => 'required',
            'card_no' => 'required',
        ];
        $request->validate($rules);
        $kycDocs = KycDoc::whereUserId($request->id)->firstOrNew();
        $kycDocs->user_id = $request->id;
        $kycDocs->kyc_type = $request->kyc_type;
        $kycDocs->card_no = $request->card_no;
        
        if($request->has('status')){
            $kycDocs->status = $request->status;
        }
        if($request->has('admin_remark')){
            $kycDocs->admin_remark = $request->admin_remark;
        }

        $cards = $this->uploadImages($request);
        if($cards['card_front'] != ''){
            $kycDocs->card_front = $cards['card_front'];
        }
        if($cards['card_back'] != ''){
            $kycDocs->card_back = $cards['card_back'];
        }
        $kycDocs->save();
        \Session::flash('success','Success|KYC details saved successfully!');
        return redirect()->route('admin.users', ['kyc_details' => $request->id]);
    }

    private function uploadImages(Request $request){
        $CardFront = '';
        $CardBack = '';
        if($request->hasFile('card_front')){
            $CardFront = "IMG_".time().'_'.rand(11111111,9999999).'.'.$request->file('card_front')->getClientOriginalExtension();
            $request->file('card_front')->move('images/kyc_docs/',$CardFront);
        }
        if($request->hasFile('card_back')){
            $CardBack = "IMG_".time().'_'.rand(11111111,9999999).'.'.$request->file('card_back')->getClientOriginalExtension();
            $request->file('card_back')->move('images/kyc_docs/',$CardBack);
        }
        return ['card_front'=>$CardFront,'card_back'=>$CardBack];
    }

    public function adminCharges(Request $request){

        $details = AdminCharge::first();
        if($request->isMethod('post')){
            $validator = $request->validate([
                'tds_charges' => 'required|numeric',
                'admin_charges' => 'required|numeric',
                'direct_amount' => 'required|numeric',
                'pair_amount' => 'required|numeric',
                'capping_of_pair' => 'required|numeric'
            ]);

            $adminCharges = AdminCharge::firstOrNew();
            $adminCharges->fill($request->all());
            $adminCharges->save();
            \Session::flash('success','Success|Charges saved successfully!');
            return back();
        }
        return view('admin.admin-charges.index',compact('details'));
    }

    public function setUserToPaid($userId){
        $user = User::find($userId);
        if($user != null){
            $user->user_icon = 'payment-done.png';
            $user->is_paid = 1;
            $user->save();
            Session::flash('success','Success|User set to paid successfully!');
        }
        return back();
    }

    public function searchMembers(Request $request){
        return User::where('member_id','like','%'.$request->term.'%')
            ->select(['member_id','id'])->get();
    }

    public function makeFranchiseUser($user_id, $type) {
        $userModel = User::find($user_id);
        $userModel->is_franchise = $type == 'make' ? 1 : 0;

        $user = $userModel->save();
        if($user) {
            if ($userModel->is_franchise == 1) {
                \Session::flash('success', "Success|User is now a franchise user successfully!");
            } else{
                \Session::flash('error', "Error|User is successfully removed from franchise user!");
            }
        }
        else{
            \Session::flash('error', "Error|Something went wrong!");
        }

        return back();
    }

    public function approveKyc($id){
        $kyc = KycDoc::find($id);
        if($kyc){
            $kyc->update(['status' => 1]);
            \Session::flash('success','Success|KYC approved successfully!');
            return redirect()->route('admin.users', ['kyc_details' => $kyc->user_id]);
        }
        return back();
    }

    public function rejectKyc($id){
        $kyc = KycDoc::find($id);
        if($kyc){
            $kyc->update(['status' => 2]);
            \Session::flash('success','Success|KYC rejected successfully!');
            return redirect()->route('admin.users', ['kyc_details' => $kyc->user_id]);
        }
        return back();
    }
}
