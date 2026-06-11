<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\FeaturesService;
use Illuminate\Http\Request;
use App\Models\NewsEvent;
use App\Models\Announcement;
use App\Models\Reward;
use App\Models\User;
use App\Helpers\RewardHelper;
use Str;

class FeaturesController extends Controller
{

    public function contactPage(){
        return view('features.contact');
    }
    public function contactSubmit(Request $request){
        return (new FeaturesService)->contactSubmit($request);
    }
    public function newsEventsPage(){
        $news_events = NewsEvent::where('image','!=','')->get();
        return view('features.news-events',compact('news_events'));
    }
    public function announcementsPage(){
        $announcements = Announcement::where('title','!=','')->get();
        return view('features.announcements',compact('announcements'));
    }
    public function weeklyCheckin(Request $request){    
        return (new FeaturesService)->weeklyCheckin($request);
    }
    public function aboutUsPage(){
        return view('features.about-us');
    }
    public function redeemRewards($rewardId){
        $user = User::findOrFail(authUser()->id);
        $array = [
            70 => [
                'logo_downline' => 10,
                'logo_direct' => 1,
            ],
            150 => [
                'logo_downline' => 20,
                'logo_direct' => 2,
                'downline' => 150,
                'direct' => 10,
            ],
            300 => [
                'logo_downline' => 30,
                'logo_direct' => 3,
                'downline' => 400,
                'direct' => 20,
            ],
            400 => [
                'downline' => 1500,
                'direct' => 30,
            ],
            500 => [
                'downline' => 3000,
                'direct' => 60,
            ],
        ];
        $redeemable = false;
        $logocheck = false;
        $reward = Reward::findOrFail($rewardId);
        if($reward != null && $reward->type == 'points' && Str::contains($reward->name,'logo')){
            $logocheck = true;
        }
        if($logocheck){
            if(isset($array[$reward->pairs]['logo_direct']) && $user->allChildMembers()->where('is_paid',1)->count() >= $array[$reward->pairs]['logo_direct'] && $user->reward_points >= $reward->pairs && $user->allPiadChildsExceptSelf()->count() >= $array[$reward->pairs]['logo_downline']){
                $redeemable = true;
            }
        }else{
            if(isset($array[$reward->pairs]) && $user->allChildMembers()->where('is_paid',1)->count() >= $array[$reward->pairs]['direct'] && $user->reward_points >= $reward->pairs && $user->allPiadChildsExceptSelf()->count() >= $array[$reward->pairs]['downline']){
                if(checkRewardEligibility($user,$reward)){
                    $redeemable = true;
                }
            }else if(!isset($array[$reward->pairs])){
                if(checkRewardEligibility($user,$reward)){
                    $redeemable = true;
                }
            }
        }
        try {
            if($redeemable){
                RewardHelper::conditionAndAchieveReward($user,$reward);
                if($reward->amount > 0){
                    RewardHelper::addTransaction($user,$reward);
                }

                return redirect()->back()->with('success','Success|Reward has been redeemed successfully.');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error','Error|'.$th->getMessage());
        }
    }
}
