<?php

namespace App\Http\Controllers\Member;

use App\Helpers\RewardHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\RegisterEmail;
use App\Models\AdminCharge;
use App\Models\Epin;
use App\Models\PairCarry;
use App\Models\Payout;
use App\Models\Position;
use App\Models\UnpaidPayout;
use App\Models\User;
use App\Models\Role;
use App\Models\UserSeries;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use DB;
use App\Models\KycDoc;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\RegistrationOtp;
use App\Mail\RegisterOtpEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginForm(){
        return view('auth.login');
    }

    public function login(LoginRequest $request){
        if (strtolower($request->username) == 'admin') {
            return back()->withErrors(['username' => 'Member login not allowed for this ID.']);
        }

        $checkUser = User::where('member_id', $request->username)->first();
        if ($checkUser && $checkUser->role == 1) {
            return back()->withErrors(['username' => 'Admin users cannot login from here.']);
        }

        $user = \Auth::guard('member');
        if($user->attempt(['member_id'=>$request->username,'password'=>$request->password,'is_blocked'=>0,])){
            $rewards = $user->user()->latestReward();
            if($rewards !== null){
                Session::put('current_rank',$rewards->reward->rank);
            }else{
                Session::put('current_rank','No Rank');
            }
            return redirect()->route('member.dashboard');
        }else{
            return back()->withErrors(['username'=>'Wrong username and password']);
        }
    }

    // public function registerForm(Request $request){
    //     $roles = Role::where('for_admin',0)->get()->pluck('name','id')->toArray();
    //     return view('auth.new-register',compact('roles'));
    // }
    public function registerForm(Request $request){
        return view('auth.signup');
    }
    protected function isValidateCompanyUser(){
        $companyUser = User::where('sponsor_id','Company')->first();
        if($companyUser == null){
            return true;
        }
        return false;
    }

    public function sendRegistrationOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|numeric',
            'password' => 'required|min:6',
            'sponsor' => 'required|exists:users,member_id',
            'agent_category_id' => 'required|exists:agent_categories,id',
            'deposit_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (env('REGISTRATION_IP_VALIDATION', false)) {
            $userIp = $request->ip();
            $existingUserIp = User::where('registration_ip', $userIp)->exists();
            if ($existingUserIp) {
                return response()->json(['errors' => ['error' => ['Only one ID registration is allowed per IP address.']]], 422);
            }
        }

        $otpCode = rand(111111, 999999);
        $expiresAt = now()->addMinutes(10);

        RegistrationOtp::create([
            'email' => $request->email,
            'details' => $request->all(),
            'otp' => $otpCode,
            'expires_at' => $expiresAt,
        ]);

        try {
            Mail::to($request->email)->send(new RegisterOtpEmail($otpCode));
            return response()->json(['status' => true, 'message' => 'OTP sent to your email.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to send OTP. Please try again.'], 500);
        }
    }

    public function verifyRegistrationOtp(Request $request){
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        $otpRecord = RegistrationOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            return response()->json(['errors' => ['otp' => ['Invalid or expired OTP.']]], 422);
        }

        // OTP is valid, proceed with registration
        $registrationResult = $this->completeRegistration($otpRecord->details, $request);
        
        if ($registrationResult['status']) {
            $otpRecord->update(['is_used' => true]);
            return response()->json([
                'status' => true, 
                'message' => 'Registration successful!', 
                'redirect' => route('register.details')
            ]);
        }

        return response()->json(['status' => false, 'message' => $registrationResult['message']], 400);
    }

    private function completeRegistration($details, $request){
        DB::beginTransaction();
        $adminSettings = AdminCharge::first();
        $epin = null;
        try{
            if(isset($details['epin'])){
                $epin = Epin::where(['pin_no' => $details['epin'], 'used_by' => null])->first();
                if($details['epin'] != '1231231') {
                    if ($epin == null) {
                        return ['status' => false, 'message' => 'Incorrect Epin'];
                    }
                }
            }
            $userSeries = UserSeries::first();
            $memberIds = User::whereNotNull('parent_id')->get()->pluck('member_id')->toArray();
            $prefix = $adminSettings->id_prefix ?? 'HM';
            $member_id = generateUniqueMemberId($prefix,$memberIds);
            
            $userModel = new User;
            $userModel->name = $details['full_name'];
            $userModel->email = $details['email'];
            $userModel->mobile = $details['mobile'];
            $userModel->sponsor_id = $details['sponsor'];
            $userModel->parent_id = $details['sponsor'];
            $userModel->member_id = $member_id;
            $userModel->registration_ip = $request->ip();
            $userModel->password = \Hash::make($details['password']);
            $userModel->enc_password = \Crypt::encrypt($details['password']);
            $userModel->role = 3;
            $userModel->parent_leg = 'left';
            $userModel->is_paid = 0; // Unpaid until admin approval
            $userModel->user_icon = 'userunpaid.png';
            $userModel->save();

            $registerDetails = $details;
            $registerDetails['user_id'] = $userModel->member_id;
            $registerDetails['sponsor'] = $details['sponsor'];
            
            $sponsor_name = $details['sponsor_name'] ?? '';
            if(empty($sponsor_name) && strtolower($details['sponsor']) != 'company'){
                $sponsorUser = User::where('member_id', $details['sponsor'])->first();
                if($sponsorUser){
                    $sponsor_name = $sponsorUser->name;
                }
            }elseif(strtolower($details['sponsor']) == 'company'){
                $sponsor_name = 'Company';
            }
            $registerDetails['sponsor_name'] = $sponsor_name;
            
            Session::flash('reg_details',$registerDetails);

            if(isset($details['epin']) && $details['epin'] != '1231231'){
                Epin::where(['pin_no'=>$details['epin']])->update(['used_by'=>$userModel->id,'used_at'=>Carbon::now()->format('Y-m-d H:i:s')]);
            }
            $this->updateParentString($userModel);
            
            if($epin != null){
                $data = [
                    'kit_id' => $epin->joining_kit,
                    'user_id' => $userModel->id,
                    'epin_id' => $epin->id
                ];
                $this->savePinData($userModel,$data,$epin);
            }

            // Create Registration Request
            \App\Models\RegistrationRequest::create([
                'user_id' => $userModel->id,
                'agent_category_id' => $details['agent_category_id'] ?? null,
                'deposit_amount' => $details['deposit_amount'] ?? 0,
                'status' => 'pending',
            ]);
            if($details['sponsor'] != 'Company') {
               RewardHelper::giveRewards();
            }

            try {
                $this->sendEmail($details['email'], $userModel);
            } catch (\Exception $e) { }
            DB::commit();
            return ['status' => true];
        }catch(\Exception $e){
            DB::rollBack();
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function register(RegisterRequest $request){
        if (env('REGISTRATION_IP_VALIDATION', false)) {
            $userIp = $request->ip();
            $existingUserIp = User::where('registration_ip', $userIp)->exists();
            if ($existingUserIp) {
                return back()->withInput()->withErrors(['error' => 'Only one ID registration is allowed per IP address.']);
            }
        }

        $registrationResult = $this->completeRegistration($request->all(), $request);
        
        if ($registrationResult['status']) {
            return redirect()->route('register.details')->with('success', 'Registration successful!');
        }

        return back()->withInput()->withErrors(['error' => $registrationResult['message']]);
    }

    private function sendEmail($email, $userModel,$coupon_code = null){
        \Mail::to($email)->send(new RegisterEmail($userModel,$coupon_code));
    }

    public function generatePayoutForSingleUser($singleUser, $adminCharges){
        $usersArray = [];
        $stringUsers = User::whereIn('id',explode(',',$singleUser->parent_string))->get();
        foreach($stringUsers as $key => $singleUser){
            $usersArray[] = ['parent_id'=> $singleUser->parent_id,'parent_leg'=> $singleUser->parent_leg];
        }
        $payoutIds = collect($usersArray)->groupBy('parent_id')->forget('Company')->forget('company')->toArray();
        foreach($payoutIds as $userId => $listUsers){
            $this->generatePayout($payoutIds,$userId, $adminCharges);
        }
    }
    public function addUserToEcommerce($user,$password = '123456'){
        $client = new Client();
        $url = config('app.api_url').'/api/user/create';
        $formData = [
            'name' => $user->name,
            'f_name' => $user->name,
            'l_name' => null,
            'email' => $user->email,
            'phone' => $user->mobile,
            'is_active' => 1,
            'password' => $password,
        ];
        try {
            $response = $client->post($url, [
                'form_params' => $formData,
            ]);
            $responseBody = $response->getBody()->getContents();
            $responseJson = json_decode($responseBody, true);
            if($responseJson['status'] == true){
                $user->customer_id = $responseJson['customer_id'];
                $user->save();
            }
            return $responseJson;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse();
                $errorBody = $errorResponse->getBody()->getContents();
                return response()->json(['error' => $errorBody], $errorResponse->getStatusCode());
            }
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function addCouponToEcommerce($user,$kit){
        $today = Carbon::now();
        $next_month = Carbon::now()->addMonth();
        DB::beginTransaction();
        try {
            DB::connection('ecommerce')->table('coupons')->insert([
                'coupon_type' => 'discount_on_purchase',
                'title' => 'Full value return coupon',
                'code' => generateCode(),
                'coupon_bearer' => 'inhouse',
                'seller_id' => 0,
                'customer_id' => 0,
                'limit' => 1,
                'discount_type' => 'percentage',
                'discount' => 100,
                'min_purchase' => 0,
                'max_discount' => $kit->amount,
                'start_date' => $today->format('Y-m-d'),
                'expire_date' => $next_month->format('Y-m-d'),
            ]);

            DB::commit();
            return ['status' => true];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ['status' => false,'message' => $th->getMessage()];
        }
    }
    public function sendCouponRequest($user,$kit){
        $client = new Client();
        $today = Carbon::now();
        $next_month = Carbon::now()->addMonth();
        $url = config('app.api_url').'/api/coupon';
        $formData = [
            'coupon_type' => 'discount_on_purchase',
            'title' => 'Full value return coupon',
            'code' => generateCode(),
            'coupon_bearer' => 'inhouse',
            'seller_id' => 0,
            'customer_id' => 0,
            'limit' => 1,
            'discount_type' => 'percentage',
            'discount' => 100,
            'min_purchase' => 0,
            'max_discount' => $kit->amount,
            'start_date' => $today->format('Y-m-d'),
            'expire_date' => $next_month->format('Y-m-d'),
        ];
        try {
            $response = $client->post($url, [
                'form_params' => $formData,
            ]);
            $responseBody = $response->getBody()->getContents();
            $responseJson = json_decode($responseBody, true);
            return $responseJson;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse();
                $errorBody = $errorResponse->getBody()->getContents();
                return response()->json(['error' => $errorBody], $errorResponse->getStatusCode());
            }
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function generatePayoutIds(){
        $lastPayout = Payout::orderBy('created_at', 'desc')->first();
        $payoutDate = null;
        if($lastPayout == null){
            $users = User::get();
        }else{
            $payoutDate = $lastPayout->created_at;
            $users = User::where('created_at', '>', $payoutDate->format('Y-m-d H:i:s'))->get();
        }
        $usersArray = [];
        foreach($users as $key => $user){
            $stringUsers = User::whereIn('id',explode(',',$user->parent_string))->get();
            foreach($stringUsers as $key => $singleUser){
                $usersArray[] = ['parent_id'=> $singleUser->parent_id,'parent_leg'=> $singleUser->parent_leg];
            }
        }
        $payoutIds = collect($usersArray)->groupBy('parent_id')->forget('Company')->forget('company')->toArray();
        foreach($payoutIds as $userId => $listUsers){
            $this->generatePayout($payoutIds,$userId);
        }
    }

    public function generatePayout($payoutIds, $payoutSingleId, $adminCharges){
        $idPayoutDetails = collect($payoutIds[$payoutSingleId]);
        $leftCount = $idPayoutDetails->where('parent_leg','left')->count();
        $rightCount = $idPayoutDetails->where('parent_leg','right')->count();
        $leftCarries = $rightCarries = 0;
        $existingCarries = $this->hasExistingCarry($payoutSingleId);
        $leftCount += $existingCarries['left_carry'];
        $rightCount += $existingCarries['right_carry'];
        if($leftCount > $rightCount){
            $numberOfPairs = $rightCount;
            $leftCarries = $leftCount - $rightCount;
        }else{
            $numberOfPairs = $leftCount;
            $rightCarries = $rightCount - $leftCount;
        }
        if($rightCarries != 0 || $leftCarries != 0){
            $this->saveCarries($payoutSingleId, $leftCarries, $rightCarries);
        }
        $pairAmount = $adminCharges->pair_amount*$numberOfPairs;
        $adminCutting = round($pairAmount * $adminCharges->admin_charges / 100);
        $pairAmount -= $adminCutting;
        $tdsAmount = round($pairAmount * $adminCharges->tds_charges / 100);
        $pairAmount -= $tdsAmount;
        $isEligibleForPayout = $this->validateDailyPairCapping($payoutSingleId, $adminCharges);
        if($isEligibleForPayout){
            $payoutModel = new Payout;
            $payoutModel->username = $payoutSingleId;
            $payoutModel->pair_count = $numberOfPairs;
            $payoutModel->direct_income = 0;
            $payoutModel->tds = $tdsAmount;
            $payoutModel->tree = 1;
            $payoutModel->admin_charge = $adminCutting;
            $payoutModel->pair_amount = ($numberOfPairs != 0) ? $adminCharges->pair_amount: 0;
            $payoutModel->net_amount = $pairAmount;
            $payoutModel->save();
        }
    }

    protected function validateDailyPairCapping($memberId, $adminCharges){
        $todayPayoutOfSingleUser = Payout::where('username',$memberId)->where('pair_amount','!=',0)
            ->where(DB::raw('date(created_at)'),Carbon::now()->format('Y-m-d'))->count();
        if($todayPayoutOfSingleUser >= $adminCharges->capping_of_pair){
            return false;
        }else{
            return true;
        }
    }

    public function hasExistingCarry($payoutSingleId){
        $existingCarry = PairCarry::where('user_id',$payoutSingleId)->first();
        $leftRightCarries = [];
        $leftRightCarries['left_carry'] = 0;
        $leftRightCarries['right_carry'] = 0;
        if($existingCarry != null){
            $leftRightCarries['left_carry'] = $existingCarry->left_carry;
            $leftRightCarries['right_carry'] = $existingCarry->right_carry;
            $existingCarry->left_carry = 0;
            $existingCarry->right_carry = 0;
            $existingCarry->save();
        }
        return $leftRightCarries;
    }

    public function saveCarries($userId, $leftCarries, $rightCarries){
        $pairCarry = PairCarry::firstOrNew(['user_id'=>$userId]);
        if($pairCarry->exists){
            $pairCarry->left_carry = $pairCarry->left_carry + $leftCarries;
            $pairCarry->right_carry = $pairCarry->right_carry + $rightCarries;
            $pairCarry->save();
        }else{
            $pairModel = new PairCarry;
            $pairModel->left_carry = $leftCarries;
            $pairModel->right_carry = $rightCarries;
            $pairModel->user_id = $userId;
            $pairModel->save();
        }
    }


    protected function savePayoutSummary($userModel, $pairCountModel, $pairCountLevel, $chargesModel){
        $payoutSummary = new PayoutSummary();
        $payoutSummary->user_id = $pairCountModel->user_id;
        $payoutSummary->payout_from = $userModel->member_id;
        $payoutSummary->payout_amount = $chargesModel->direct_amount;
        $tdsAmount = ($chargesModel->direct_amount * $chargesModel->tds_charges)/100;
        $adminChargesAmount = (($chargesModel->direct_amount - $tdsAmount) * $chargesModel->admin_charges) / 100;
        $payoutSummary->from_level = $pairCountLevel;
        $payoutSummary->tds = $tdsAmount;
        $payoutSummary->admin_charges = $adminChargesAmount;
        $payoutSummary->total_amount = $chargesModel->direct_amount - $tdsAmount - $adminChargesAmount;
        $payoutSummary->save();
    }

    protected function updateUsedPin($request,$user){
        Epin::where(['pin_no'=>$request->epin])->update(['used_by'=>$user->id,'used_at'=>Carbon::now()->format('Y-m-d H:i:s')]);
    }

    protected function calculateTdsAndAdminCharges($chargesModel){
        $tdsCharges = ($chargesModel->direct_amount * $chargesModel->tds_charges) / 100;
        $payoutAmount = $chargesModel->direct_amount - $tdsCharges;
        $adminCharges = ($payoutAmount * $chargesModel->admin_charges) / 100;
        $payoutAmount = $payoutAmount - $adminCharges;
        return [
            'payout_amount' => $payoutAmount,
            'admin_charges' => $adminCharges,
            'tds_charges' => $tdsCharges
        ];
    }

    private function putPositions($user,$request){
        if($request->has('parent_id') && $request->parent_id !== null){
            $parent_id = $request->parent_id;
        }else{
            $parent_id = $this->findExtremePosition($request);
        }
        for($tree = 1; $tree <= 1; $tree++){
            $positionModel = new Position;
            $positionModel->user_id = $user->member_id;
            $positionModel->position = $request->position;
            $positionModel->parent_id = $parent_id;
            $positionModel->direct_of = $request->sponsor;
            $positionModel->tree_id = $tree;
            if($tree == 1){
                $positionModel->status = 1;
            }else{
                $positionModel->status = 0;
            }
            $positionModel->save();
        }
        return $parent_id;
    }

    private function findExtremePosition($request){
        $usersArray = [];
        $usersArray[] = $request->sponsor;;
        foreach($usersArray as $key => &$user){
            $positionModel = Position::where(['position'=>$request->position,'parent_id'=>$user])->first();
            if($positionModel != null){
                $usersArray[] = $positionModel->user_id;
            }else{
                return $user;
            }
        }
        return null;
    }

    public function logout(){
        \Auth::guard('member')->logout();
        session()->forget('announcement_shown');
        return redirect()->route('login');
    }

    public function registerDetails(Request $request){
        $tempArray = Session::get('reg_details');
        if($tempArray == null){
            return redirect()->route('register');
        }
        return view('auth.register-details',['tempArray'=>$tempArray]);
    }

    public function updateParentString($registeredUser){
        $parentUser = User::where(['member_id'=>$registeredUser->parent_id])->first();
        if($parentUser != null && $parentUser->parent_string != null){
            $registeredUser->parent_string = $parentUser->parent_string.','.$registeredUser->id;
            $registeredUser->save();
        }else{
            $registeredUser->parent_string = $registeredUser->id;
            $registeredUser->save();
        }
    }

    protected static function updateLeftRightCount($user){
        $stringIds = explode(',',$user->parent_string);
        if(count($stringIds) != 1){
            unset($stringIds[count($stringIds)-1]);
        }
        DB::statement("UPDATE users SET left_count =
                                    CASE WHEN parent_leg = 'left' THEN left_count + 1 ELSE left_count END,
                                    right_count = CASE WHEN parent_leg = 'right' THEN right_count + 1 ELSE right_count END
                                    WHERE id IN(".implode(',',$stringIds).")");
    }

    public function sendSms($mobile, $memberId, $password){

        $message = "Welcome To Tremendousgold Your Login Id Is ".$memberId." And Password is ".$password." RegardsSMSDTL";
        // Prepare data for POST request
        $data = array('key' => '26225D296E0385', 'contacts' => $mobile, "message" => $message,'campaign'=>'0',
            'routeid'=>'2','type'=>'text','senderid'=>'SMSDTL','msg'=>$message,'template_id'=>'1507162833225926649');

        // Send the POST request with cURL
        $ch = curl_init('http://pro.eglsms.in/app/smsapi/index.php');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
    }


    public function forgetPassword(Request $request){
        if($request->isMethod('post')){
            $request->validate(['username'=>'required']);
            $user = User::where(['member_id'=>$request->username])->first();
            if($user != null){
//                $this->sendPassword($user);
                $this->sendPasswordEmail($user);
            }else{
                Session::flash('error','Error|User not found');
                return back();
            }
            Session::flash('success','Success|Password sent successfully!');
            return redirect()->route('login');
        }
        return view('auth.forget-password');
    }

    public function sendPasswordEmail($user){
        \Mail::to($user->email)->send(new RegisterEmail($user));
    }

    public function sendPassword($user){
        $message = "Dear ".$user->name.", Your password is: ".Crypt::decrypt($user->enc_password)." RegardsSMSDTL";
        // Prepare data for POST request
        $data = array('key' => '26225D296E0385', 'contacts' => $user->mobile, "message" => $message,'campaign'=>'0',
            'routeid'=>'2','type'=>'text','senderid'=>'SMSDTL','msg'=>$message,'template_id'=>'1507161866166333051');

        // Send the POST request with cURL
        $ch = curl_init('http://pro.eglsms.in/app/smsapi/index.php');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
    }


    public function updateParentCounts($member)
    {
        // Get the member's parent
        $parent = $member->parent;
        // If the member has no parent, we have reached the root of the tree
        if (!$parent) {
            return;
        }
        // Determine if the new member is the left or right child of the parent
        $isLeftChild = $parent->left_child_id === $member->id;
        // Increment the appropriate count for the parent
        if ($isLeftChild) {
            $parent->left_count++;
        } else {
            $parent->right_count++;
        }
        // Save the changes to the parent
        $parent->save();

        // Recursively update the parent counts for the parent's ancestors
        $this->updateParentCounts($parent);
    }
}
