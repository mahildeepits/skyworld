<?php

namespace App\Http\Controllers\Admin;

use App\Models\AgentCategory;
use App\Models\AttendedTask;
use App\Models\Epin;
use App\Models\User;
use App\Models\KycDoc;
use App\Models\Payout;
use App\Models\Setting;
use App\Models\UserAgentCategory;
use App\Models\UserKit;
use App\Models\Position;
use App\Models\PairCarry;
use App\Models\PairCount;
use App\Models\SaleEntry;
use App\Models\UserPayout;
use App\Models\UserSeries;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use App\Models\PayoutSummary;
use App\Models\AutopoolIncome;
use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\DepositsTracking;
use App\Models\EpinHistory;
use App\Models\Locking;
use App\Models\Transaction;
use App\Models\UserWeeklyCheckIn;
use App\Models\WalletTransaction;
use App\Models\UnifiedTransaction;
use App\Models\DailyTrade;
use App\Models\TransactionTracking;
use App\Models\LevelPayout;
use App\Models\UnpaidPayout;
use App\Models\RewardAchiever;
use App\Models\PinRequest;
use App\Models\UserAdditionalInfo;
use App\Models\UserAddress;
use App\Models\UserBankDetail;
use App\Models\UserProfile;
use App\Models\WalletOtp;
use App\Models\RegisterationBonus;
use App\Models\RegistrationOtp;
use App\Models\RegistrationRequest;
use App\Models\StudentDetail;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    public function index(Request $request){
        if($request->isMethod('post')){
            foreach($request->except(['_token']) as $key => $value){
                $setting = Setting::where('key',$key)->firstOrNew();
                $setting->key = $key;
                $setting->value = $value;
                $setting->status = 1;
                $setting->save();
            }
            Session::flash('success','Success|Settings update successfully!');
        }
        $settingsCollection = Setting::get();
        $settings = collect();
        foreach($settingsCollection as $key => $value){
            $settings[$value->key] = $value->value;
        }
        $string = \Str::random(50);
        \Session::put('delete_record',$string);
        return view('admin.settings.index',compact('settings','string'));
    }

    public function deleteRecords($code){
        if($code == \Session::get('delete_record')){
            \DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $topUser = User::where('member_id','top')->first();
            \Session::forget('delete_record');
            Epin::query()->truncate();
            KycDoc::query()->truncate();
            Payout::query()->truncate();
            Position::where('user_id','!=','TOP')->delete();
            PairCount::query()->truncate();
            PairCarry::query()->truncate();
            PayoutSummary::query()->truncate();
            SaleEntry::query()->truncate();
            User::whereNotIn('member_id',['Company','admin'])->delete();
            User::where('member_id','Company')->update(['left_count'=>0,'right_count'=>0,'left_child_id'=>0,'right_child_id'=>0,'reward_points'=> 0]);
            UserSeries::where('id',1)->update(['series' => 1]);
            UserWallet::query()->truncate();
            UserPayout::query()->truncate();
            UserKit::query()->truncate();
            AutopoolIncome::query()->truncate();
            EpinHistory::query()->truncate();
            WalletTransaction::query()->truncate();
            AttendedTask::query()->truncate();
            UserAgentCategory::query()->truncate();
            Transaction::query()->truncate();
            Locking::query()->truncate();
            DepositsTracking::query()->truncate();
            ContactUs::query()->truncate();
            UserWeeklyCheckIn::query()->truncate();
            UnifiedTransaction::query()->truncate();
            DailyTrade::query()->truncate();
            TransactionTracking::query()->truncate();
            LevelPayout::query()->truncate();
            UnpaidPayout::query()->truncate();
            RewardAchiever::query()->truncate();
            PinRequest::query()->truncate();
            UserAdditionalInfo::query()->truncate();
            UserAddress::query()->truncate();
            UserBankDetail::query()->truncate();
            UserProfile::query()->truncate();
            WalletOtp::query()->truncate();
            RegisterationBonus::query()->truncate();
            RegistrationOtp::query()->truncate();
            RegistrationRequest::query()->truncate();
            StudentDetail::query()->truncate();
            $epin = new Epin;
            $epin->joining_kit = 1;
            $epin->pin_no = 1231231;
            $epin->save();
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');
            \Session::flash('success','Success|Data Removed successfully!');
            return back();
        }
    }
}
