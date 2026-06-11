<?php

namespace App\Http\Controllers\Member;

use App\Models\KycDoc;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\UserBankDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function profile(){
        return view('account.profile');
    }

    public function kycDetails(){
        $model = KycDoc::whereUserId(\Auth::guard('member')->user()->id)->get();
        return view('account.kycDetails',compact('model'));
    }

    public function updateKycDocuments(Request $request){
        $request->validate([
            'card_no' => 'required',
            'kyc_type' => 'required',
            'card_front' => 'required|image',
            'card_back' => 'required|image'
        ]);
        $user = \Auth::guard('member')->user();
        $kycDocs = KycDoc::whereUserId($user->id)->where('kyc_type', $request->kyc_type)->first();
        
        if($kycDocs == null || $kycDocs->status == 2){
            if($kycDocs == null){
                $kycDocs = new KycDoc();
                $kycDocs->user_id = $user->id;
            }
            
            $kycDocs->kyc_type = $request->kyc_type;
            $kycDocs->card_no = $request->card_no;
            $kycDocs->status = 0; // Reset to pending
            
            if($request->hasFile('card_front') && $request->hasFile('card_back')) {
                $cards = $this->uploadImages($request);
                $kycDocs->card_front = $cards['card_front'];
                $kycDocs->card_back = $cards['card_back'];
            }
            
            $kycDocs->save();
            \Session::flash('success','Success|KYC details submitted successfully for review!');
        } else {
            $statusMsg = $kycDocs->status == 1 ? 'Approved' : 'Pending';
            \Session::flash('error','Error|You can\'t update the details as it is already ' . $statusMsg . '!');
        }
        return back();
    }

    private function uploadImages(Request $request){
        $CardFront = "IMG_".time().'_'.rand(11111111,9999999).'.'.$request->file('card_front')->getClientOriginalExtension();
        $request->file('card_front')->move('images/kyc_docs/',$CardFront);
        $CardBack = "IMG_".time().'_'.rand(11111111,9999999).'.'.$request->file('card_back')->getClientOriginalExtension();
        $request->file('card_back')->move('images/kyc_docs/',$CardBack);
        return ['card_front'=>$CardFront,'card_back'=>$CardBack];
    }

    public function editBankDetails(){
        $model = UserBankDetail::whereUserId(\Auth::guard('member')->user()->id)->first();
        return view('account.bank-details',compact('model'));
    }

    public function saveBankDetails(Request $request){
        $request->validate([
            'account_number' => 'required',
            'ifsc_code' => 'required',
        ]);
        $user = \Auth::guard('member')->user()->id;
        $userBankDetailModel = UserBankDetail::firstOrNew(['user_id'=>$user]);
        if($userBankDetailModel->exists){
            \Session::flash('error','Error|You can\'t edit your informationonce updated!');
            return back();
        }
        $userBankDetailModel->fill($request->all());
        $userBankDetailModel->save();
        \Session::flash('success','Success|Your information has been updated successfully!');
        return back();
    }

    public function changePassword(){
        return view('account.change-password');
    }

    public function updatePassword(Request $request){
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required',
            'conf_password' => 'required'
        ];
        $request->validate($rules);
        if($request->new_password != $request->conf_password){
            return back()->withErrors(['conf_password'=>'Password do not match!']);
        }

        $user = \Auth::guard('member')->user();
        $user->password = \Hash::make($request->new_password);
        $user->save();
        \Session::flash('success','Success|Your password has been updated successfully!');
        return back();
    }

    public function editProfile(){
        return view('account.edit-profile');
    }

    public function saveProfile(Request $request){
        $userProfile = UserProfile::firstOrNew(['user_id'=>auth()->guard('member')->user()->id]);
        $userProfile->father_name = $request->father_name;
        $userProfile->dob = $request->dob;
        $userProfile->gender = $request->gender;
        $userProfile->address = $request->address;
        $userProfile->pin_code = $request->pin_code;
        $userProfile->city = $request->city;
        $userProfile->state = $request->state;
        $userProfile->country = $request->country;
        $userProfile->nominee_name = $request->nominee_name;
        $userProfile->nominee_relation = $request->nominee_relation;
        $userProfile->save();
        $user = auth('member')->user();
        $user->email = $request->email;
        $user->father_name = $request->father_name;
        $user->gender = $request->gender;
        $user->dob = $request->dob;
        $user->mobile = $request->mobile;
        $user->save();
        \Session::flash('success','Success|Your profile has been updated successfully!');
        return back();
    }

    public function updateProfileImage(Request $request) {
        $user = auth('member')->user();
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $fileName = 'IMG_PROFILE_' . rand(11111, 99999) . '.' . $image->getClientOriginalExtension();
            $destinationPath = 'profile_images/';
    
            // Delete the old profile image if it exists
            if ($user->profile_image && Storage::exists($destinationPath . $user->profile_image)) {
                Storage::delete($destinationPath . $user->profile_image);
            }
    
            // Store the new profile image
            $path = $image->storeAs($destinationPath, $fileName, 'public');
            $user->profile_image = $fileName; // Update the profile image path in the database
            $user->save();
        }
        return response()->json(['status'=>true, 'message'=>'Profile image updated successfully!']);
    }
}
