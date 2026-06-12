<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Payout;
use App\Models\Reward;
use App\Models\UserPayout;
use App\Models\AdminCharge;
use App\Models\LevelPayout;
use App\Models\AutopoolIncome;
use App\Models\RewardAchiever;
use App\Models\UserAgentCategory;
use App\Models\WalletTransaction;
use App\Models\RegisterationBonus;
use App\Models\TransactionTracking;

class RewardHelper
{
    public static function giveRewards()
    {
        $allUsers = User::whereNotNull('parent_string')->where('parent_string','!=','')->get();
        $rewards = self::getRewards();
        if($rewards->count() > 0){
            foreach($rewards as $k => $singleReward){
                $users = $allUsers->filter(function ($user) use ($singleReward) {

                    $eligibleChildren = $user->allChildMembers->filter(function ($child) use ($singleReward,$user) {
                        if ($child->email_verified_at) {
                            $childDate = \Carbon\Carbon::parse($child->email_verified_at); // ✅ correct value

                            $startTime = \Carbon\Carbon::parse($user->email_verified_at);
                            $endTime = $startTime->copy()->addDays($singleReward->days);
                            return $childDate->between($startTime, $endTime);
                        }
                        return false;
                    });

                    // Check if eligible child count meets or exceeds the reward pair requirement
                    return $eligibleChildren->count() >= $singleReward->pairs;
                    // return true;
                })->values();
                foreach($users as $index => $user){
                    self::conditionAndAchieveReward($user,$singleReward);
                }
            }
        }
    }
    public static function conditionAndAchieveReward($user,$singleReward){
        $isRewardAlreadyGiven = $user->achievedRewards->where('id',$singleReward->id)->count() == 0? true : false;
        if(in_array($singleReward->amount,[200,3000,25000])){
            $isRewardAlreadyGiven = true;
        }
        // here the login about dates
        if ($user->email_verified_at) {
            $hoursSinceVerified = \Carbon\Carbon::parse($user->email_verified_at)->diffInHours(\Carbon\Carbon::now());

            $allowedHours = $singleReward->days * 24;

            if ($hoursSinceVerified > $allowedHours) {
                $isRewardAlreadyGiven = false;
            }
        } else {
            $isRewardAlreadyGiven = false;
        }
        if($isRewardAlreadyGiven){
            self::saveRewardAchiever($user,$singleReward);
        }
    }
    public static function getRewards(){
        return Reward::whereNull('type')->orWhere('type','rewards')->get();
    }

    public static function saveRewardAchiever($user,$reward): RewardAchiever
    {
        $modelObject = new RewardAchiever;
        $modelObject->user_id = $user->id;
        $modelObject->reward_id = $reward->id;
        $modelObject->pairs = $reward->pairs;
        $modelObject->save();
        return $modelObject;
    }

    public static function studentRegIncentive($userId){
        $adminCharge = AdminCharge::first();
        $student = User::find($userId);
        $sponsor = $student->sponsor;
        if($sponsor != null){
            $payout = Payout::where('username',$sponsor->member_id)->get()->last();
            $pair_amount = 200;
            $tds = ((200*$adminCharge->tds_charges)/100);
            $admin_charges = ((200*$adminCharge->admin_charges)/100);
            $net_amount = ((200 - $tds) - $admin_charges);
            if($payout != null && $payout->pair_count < 60){
                $payout_count = $payout->pair_count + 1;
                $pair_amount = $payout->pair_amount + $pair_amount;
                $tds = $payout->tds + $tds;
                $admin_charges = $payout->admin_charge + $admin_charges;
                $net_amount = $payout->net_amount + $net_amount;
                $payout->update([
                    'pair_count' => $payout_count,
                    'pair_amount' => $pair_amount,
                    'tds' => $tds,
                    'admin_charge' => $admin_charges,
                    'net_amount' => $net_amount,
                ]);
                if($payout_count == 60){
                    self::registerBonus($sponsor,$sponsor->payouts->count());
                    self::registerBonus($sponsor->sponsor);
                    self::upperLevelBonus($sponsor->sponsor);
                }
            }else{
                Payout::create([
                    'username' => $sponsor->member_id,
                    'tree' => 1,
                    'pair_count' => 1,
                    'pair_amount' => $pair_amount,
                    'direct_income' => 0,
                    'tds' => $tds,
                    'admin_charge' => $admin_charges,
                    'net_amount' => $net_amount,
                ]);
            }
        }
    }
    public static function upperLevelBonus($user){
        $roles = [
            4 => 100000,  // district field officer
            3 => 20000,  // district team manager
            2 => 20000,  //  admin
        ];  // role_id => amount

        foreach ($roles as $role => $amount) {
            $count = User::where('sponsor_id',$user->sponsor_id)->where('role',$user->role)->whereHas('regisBonus',function($query)use($amount){
                $query->select(\DB::raw('SUM(bonus_amount) as total_bonus'))
                      ->groupBy('user_id')
                      ->having('total_bonus', '>=', $amount);
            })->get()->count();
            $index = 0;
            while ($count >= 5) {
                $index = $index + 1;
                self::registerBonus($user->sponsor,$index);
                $count = $count - 5;
            }
            $user = $user->sponsor;
        }
    }
    public static function registerBonus($user,$index = null){
        $adminCharge = AdminCharge::first();
        $bonus_amount = 20000;
        if($index != null){
            $bonus_amount = 20000 * $index;
        }

        $tds = (($bonus_amount*$adminCharge->tds_charges)/100);
        $admin_charges = (($bonus_amount*$adminCharge->admin_charges)/100);
        $net_amount = (($bonus_amount - $tds) - $admin_charges);
        RegisterationBonus::updateOrCreate([
            'user_id' => $user->id,
        ],[
            'bonus_amount' => $bonus_amount,
            'tds' => $tds,
            'admin_charges' => $admin_charges,
            'net_amount' => $net_amount,
        ]);
    }
    public static function levelIncome($user_id, $category = null){
        return; // Old Level Income Disabled
        if($user_id != null){
            $user = User::find($user_id);
            $levelIncome = [
                1 => $category?->team_a_profit ?? 16,
                2 => $category?->team_b_profit ?? 8,
                3 => $category?->team_c_profit ?? 4,
            ]; // level => income
            foreach ($levelIncome as $level => $percentage) {
                $sponsor = $user?->sponsor ?? null;
                if($user != null && $user->is_paid == 1){
                    if($sponsor != null){
                        $sponsorActiveChilds = $sponsor->allChildMembers->where('is_paid',1);
                        if($sponsorActiveChilds->count() >= $level) {
                            $column         = 'level_'.$level;
                            $level_income   = round((($percentage * $user->today_tasks_income)/100),2);
                            if ($category != null) {
                                $level_income   = round((($percentage * getIncomeByCategory($category->id, 'todayTasks', $user))/100),2);
                                $keyword       = $column = 'level_'.$level.'-package_'.$category->id;
                            }
                            if ($level_income > 0) {

                                $payout         = UserPayout::where('user_id',$sponsor->id)->where('income_type',$column)->whereNull('is_requested')->whereNull('is_paid')->whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'))->latest()->first();
                                if($payout != null){
                                    $payout->update(['amount' => $level_income]);
                                }else{
                                    $payout = UserPayout::create([
                                        'user_id'     => $sponsor->id,
                                        'income_type' => $column,
                                        'amount'      => $level_income,
                                    ]);
                                }

                                $transaction    = TransactionTracking::where(['user_id' => $sponsor->id, 'from_user' => $user_id, 'type' => 'level', 'keyword' => $keyword])
                                                    ->whereDate('created_at', \Carbon\Carbon::now()->format('Y-m-d'))
                                                    ->latest()
                                                    ->first();
                                if ($transaction != null) {
                                    $transaction->update([
                                        'amount'     => $level_income,
                                        'net_amount' => $level_income,
                                        'remark'     => 'Level Income from User - ' . $user->member_id . ' (L-'.$level . '), Package ' . ($category?->name ?? 'N/A'),
                                    ]);
                                } else {
                                    TransactionTracking::create([
                                        'user_id'    => $sponsor->id,
                                        'type'       => 'level',
                                        'keyword'    => $keyword,
                                        'amount'     => $level_income,
                                        'net_amount' => $level_income,
                                        'related_id' => $payout?->id,
                                        'remark'     => 'Level Income from User - ' . $user->member_id . ' (L-'.$level . '), Package ' . ($category?->name ?? 'N/A'),
                                        'from_user'  => $user_id,
                                    ]);
                                }

                                \App\Models\UnifiedTransaction::updateOrCreate(
                                    [
                                        'user_id'      => $sponsor->id,
                                        'from_user_id' => $user_id,
                                        'category'     => 'Level ' . $level . ' Income',
                                        'transaction_type' => 'Credit',
                                        'tx_hash'      => 'level_'.$level.'_'.$user_id.'_'.date('Ymd'), // Unique per day per user per level
                                    ],
                                    [
                                        'amount'      => $level_income,
                                        'status'      => 'Completed',
                                        'description' => 'Level Income from ' . $user->member_id . ' (L-'.$level . ')',
                                        'created_at'  => now(),
                                    ]
                                );

                                // Trigger Team Profit Income for the receiver's uplines
                                // self::processTeamProfitIncome($sponsor, $level_income, 'Level ' . $level . ' Income');
                            }
                        }
                        $user = $sponsor;
                    }
                }
            }
        }
    }

    public static function directIncome($user_id){
        return; // Old Direct Income Disabled
        $adminCharge = AdminCharge::first();
        $user = User::with(['latestJoiningKit','sponsor'])->where('id',$user_id)->first();
        if($user != null){
            $kit            = $user->latestJoiningKit ?? null;
            $sponsor        = $user->sponsor;
            $direct_amount  = $kit->direct_income ?? 0;
            $amount         = $direct_amount;
            $tds            = 0;
            $admin_charges  = 0;
            $net_amount     = round((($amount - $tds) - $admin_charges),2);
            $payout         = UserPayout::where('user_id',$sponsor->id)->where('income_type','direct')->whereNull('is_requested')->whereNull('is_paid')->latest()->first();
            if($payout != null){
                $payout->update([
                    'amount' => round(($payout->amount + $amount),2),
                    'tds' => round(($payout->tds+$tds),2),
                    'admin_charges' => round(($payout->admin_charges+$admin_charges),2),
                    'net_amount' => round(($payout->net_amount+$net_amount),2),
                ]);
            }else{
                $payout = UserPayout::create([
                    'user_id'       => $sponsor->id,
                    'income_type'   => 'direct',
                    'amount'        => $amount,
                    'tds'           => $tds,
                    'admin_charges' => $admin_charges,
                    'net_amount'    => $net_amount,
                ]);
            }
            TransactionTracking::create([
                'user_id'     => $sponsor->id,
                'type'        => 'direct',
                'keyword'     => 'UserPayout',
                'amount'      => $amount,
                'net_amount'  => $net_amount,
                'related_id'  => $payout?->id,
                'remark'      => 'Direct Income',
                'from_user'   => $user->id,
            ]);

            \App\Models\UnifiedTransaction::create([
                'user_id'          => $sponsor->id,
                'from_user_id'     => $user->id,
                'amount'           => $amount,
                'transaction_type' => 'Credit',
                'category'         => 'Direct Income',
                'status'           => 'Completed',
                'description'      => 'Direct Income from ' . $user->member_id,
            ]);

            // Trigger Team Profit Income for the receiver's uplines
            // self::processTeamProfitIncome($sponsor, $amount, 'Direct Income');
        }
    }
    public static function sponsorIncome($user_id){
        return; // Old Sponsor Income Disabled
        $adminCharge    = AdminCharge::first();
        $user           = User::with(['latestJoiningKit','sponsor'])->where('id',$user_id)->first();
        $amount         = $user->latestJoiningKit->level2_5;
        $tds            = 0;
        $admin_charges  = 0;
        $net_amount     = round((($amount - $tds) - $admin_charges),2);
        $sponsor        = $user->sponsor;
        for ($i=0; $i < 6; $i++) {
            if($sponsor != null){
                $payout = UserPayout::where('user_id',$sponsor->id)->where('income_type','level')->whereNull('is_requested')->whereNull('is_paid')->latest()->first();
                if($payout != null){
                    $payout->update([
                        'amount' => round(($payout->amount + $amount),2),
                        'tds' => round(($payout->tds + $tds),2),
                        'admin_charges' => round(($payout->admin_charges + $admin_charges),2),
                        'net_amount' => round(($payout->net_amount + $net_amount),2),
                    ]);
                }else{
                    $payout = UserPayout::create([
                        'user_id' => $sponsor->id,
                        'income_type' => 'level',
                        'amount' => $amount,
                        'tds' => $tds,
                        'admin_charges' => $admin_charges,
                        'net_amount' => $net_amount,
                    ]);
                }
                TransactionTracking::create([
                    'user_id'     => $sponsor->id,
                    'type'        => 'sponsor',
                    'keyword'     => 'UserPayout',
                    'amount'      => $amount,
                    'net_amount'  => $net_amount,
                    'related_id'  => $payout?->id,
                    'remark'      => 'Sponsor Income',
                    'from_user'   => $user->id,
                ]);

                \App\Models\UnifiedTransaction::create([
                    'user_id'          => $sponsor->id,
                    'from_user_id'     => $user->id,
                    'amount'           => $amount,
                    'transaction_type' => 'Credit',
                    'category'         => 'Sponsor Income',
                    'status'           => 'Completed',
                    'description'      => 'Sponsor Income from ' . $user->member_id,
                ]);
            }
            $sponsor = $sponsor->sponsor ?? null;
        }
    }
    public static function charityIncome($user_id){
        return; // Old Charity Income Disabled
        $adminCharge = AdminCharge::first();
        $user = User::with(['latestJoiningKit','sponsor'])->where('id',$user_id)->first();
        $amount = $user?->latestJoiningKit?->bonus_amount ?? 0;
        $tds = round((($amount * $adminCharge->tds_charges)/100),2);
        $admin_charges = round((($amount * $adminCharge->admin_charges)/100),2);
        $net_amount = round((($amount - $tds) - $admin_charges),2);
        $charityUsers = User::whereIn('member_id',['CH0001','CH0002'])->get();
        foreach ($charityUsers as $user) {
            $payout = UserPayout::where('user_id',$user->id)->where('income_type','charity')->whereNull('is_requested')->whereNull('is_paid')->latest()->first();
            if($payout != null){
                $payout->update([
                    'amount' => round(($payout->amount + $amount),2),
                    'tds' => round(($payout->tds + $tds),2),
                    'admin_charges' => round(($payout->admin_charges + $admin_charges),2),
                    'net_amount' => round(($payout->net_amount + $net_amount),2),
                ]);
            }else{
                UserPayout::create([
                    'user_id' => $user->id,
                    'income_type' => 'charity',
                    'amount' => $amount,
                    'tds' => $tds,
                    'admin_charges' => $admin_charges,
                    'net_amount' => $net_amount,
                ]);
            }
        }
    }
    public static function autopoolIncome($user_id){
        return; // Old Autopool Income Disabled
        $user = User::with(['latestJoiningKit','sponsor'])->where('id',$user_id)->first();
        $autoPool = $user->latestJoiningKit->autoPool;
        $counts = [
            1 => 'count_4',
            2 => 'count_16',
            3 => 'count_64',
            4 => 'count_256',
            5 => 'count_1024',
            6 => 'count_4096',
            7 => 'count_16384',
        ];
        $sponsor = $user->sponsor;
        $userCount = 4;
        foreach($counts as $level => $column){
            $olderAutopoolIncome = null;
            $autopoolIncome = AutopoolIncome::where('autopool_id',$autoPool->id)->where('level',$level)->where('user_id',$sponsor->id)->latest()->first();
            if($level >= 2){
                $olderAutopoolIncome = AutopoolIncome::where('autopool_id',$autoPool->id)->where('level',($level - 1))->where('user_id',$sponsor->id)->latest()->first();
            }
            if($olderAutopoolIncome != null){
                $sameKitSponsorChilds = $user->sponsor->childs->toQuery()->where('is_paid',1)->whereHas('latestJoiningKit',function($query)use($autoPool){
                    return $query->where('autopool_id',$autoPool->id);
                })->count();
                if(!in_array($user->sponsor->id,$olderAutopoolIncome->child_ids)){
                    break;
                }else if($sameKitSponsorChilds > 4){
                    break;
                }
            }
            $is_eligible = false;
            $sponsorAutopoolIds = $sponsor?->joiningKits->pluck('autopool_id')->toArray() ?? [];
            if(in_array($autoPool->id,$sponsorAutopoolIds) || $sponsor->member_id == 'Company'){
                $is_eligible = true;
            }
            if($is_eligible){
                if($autopoolIncome != null){
                    if(count($autopoolIncome->child_ids) < $userCount){
                        $currentChild_ids = $autopoolIncome->child_ids;
                        if(!in_array($user->id,$currentChild_ids)){
                            array_push($currentChild_ids,$user->id);
                            $autopoolIncome->update([
                                'income' => ($autopoolIncome->income != null)? round($autopoolIncome->income + $autoPool->$column,2) : $autoPool->$column,
                                'child_ids' => ($autopoolIncome->child_ids != null)? $currentChild_ids : [$user->id],
                            ]);
                        }
                    }
                }else{
                    $data = [
                        'user_id' => $sponsor->id,
                        'income' => $autoPool->$column,
                        'level' => $level,
                        'child_ids' => [$user->id],
                        'autopool_id' => $autoPool->id,
                    ];
                    AutopoolIncome::create($data);

                    \App\Models\UnifiedTransaction::create([
                        'user_id'          => $sponsor->id,
                        'amount'           => $autoPool->$column,
                        'transaction_type' => 'Credit',
                        'category'         => 'Autopool Income',
                        'status'           => 'Completed',
                        'description'      => 'Autopool Level ' . $level . ' Income',
                    ]);
                }
            }
            if(count($sponsorAutopoolIds) > 1 && $sponsor->latestJoiningKit->autopool_id != $autoPool->id){
                self::settlePreviousAutopool($sponsor,$autoPool->id);
            }
            $sponsor = $sponsor->sponsor;
            $userCount = $userCount*4;
            if($sponsor == null){
                break;
            }
        }
    }
    public static function settlePreviousAutopool($user,$autopool_id){
        $adminCharge = AdminCharge::first();
        $autopoolIncomes = AutopoolIncome::where('user_id',$user->id)->where('autopool_id',$autopool_id)->orderBy('level','asc')->get();
        $olderPayouts = UserPayout::where('user_id',$user->id)->where('income_type','autopool')->where(function($query){
            return $query->whereNotNull('is_requested')->orWhereNotNull('is_paid');
        })->get();
        $income = getMatchedAutoPoolIncome($autopoolIncomes);
        if($olderPayouts->count() > 0) {
            $amount = round(($income - $olderPayouts->sum('amount')),2);
        }else {
            $amount = round($income,2);
        }
        $tds = round((($amount * $adminCharge->tds_charges)/100),2);
        $admin_charges = round((($amount * $adminCharge->admin_charges)/100),2);
        $net_amount = round((($amount - $tds) - $admin_charges),2);
        $payout = UserPayout::where('user_id',$user->id)->where('income_type','autopool')->whereNull('is_requested')->whereNull('is_paid')->latest()->first();
        if($payout != null){
            $payout->update([
                'amount' => $amount,
                'tds' => $tds,
                'admin_charges' => $admin_charges,
                'net_amount' => $net_amount,
            ]);
        }else{
            UserPayout::create([
                'user_id' => $user->id,
                'income_type' => 'autopool',
                'amount' => $amount,
                'tds' => $tds,
                'admin_charges' => $admin_charges,
                'net_amount' => $net_amount,
            ]);
        }
    }
    public static function achieveRank($user,$rank){
        if($user != null){
            $wallet = $user->walletIncomesByKey();
            $levels = \App\Models\AgentCategory::get();
            $achieved_level = $rank;
            
            if($achieved_level != null){
                $existingRecord = UserAgentCategory::where('user_id', $user->id)
                    ->where('agent_category_id', $achieved_level->id)
                    ->first();

                if ($existingRecord) {
                    if ($existingRecord->is_active == 0) {
                        $existingRecord->update(['is_active' => 1]);
                    }
                } else {
                    UserAgentCategory::create([
                        'user_id' => $user->id,
                        'agent_category_id' => $achieved_level->id,
                        'is_active' => 1,
                    ]);

                    self::packageUpgradeDirectIncome($user,$achieved_level);
                }
            }
        }
    }
    public static function achieveUplineBonus($user){
        if($user != null){
            if($user->allChildMembers->count() >= 8) {
                $user->update(['upline_bonus_status' => 1]);
            }
        }
    }
    public static function lifeTimeReward($user_id){
        $user = User::find($user_id);
        if($user != null){
            $parentsArray = $user->parent_string_array;
            $users = User::whereIn('id',$parentsArray)->get();
            foreach($users as $user){
                self::createRewardAchiever($user);
            }
        }
    }

    public static function createRewardAchiever($user){
        $rewards = self::getRewards();
        $userDirectCount = $user->allChildMembers();
        $data = ['user_id' => $user->id];
        foreach ($rewards as $reward) {
            if($userDirectCount >= $reward->pairs){
                $data['reward_id'] = $reward->id;
                $data['pairs'] = (int) $userDirectCount;
            }
        }
        if(isset($data['reward_id']) && $data['reward_id'] != null){
            RewardAchiever::updateOrCreate([
                'user_id' => $data['user_id'],
                'reward_id' => $data['reward_id']
            ],[
                'pairs' => $data['pairs']
            ]);
        }
    }
    public static function addTransaction($user,$singleReward){
        $userpayout = UserPayout::where('user_id',$user->id)->where('income_type','reward_income')->where('amount',$singleReward->amount)->whereNull('is_requested')->whereNull('is_paid')->latest()->first();
        if($userpayout == null){
            $payout = UserPayout::create([
                'user_id' => $user->id,
                'income_type' => 'reward_income',
                'amount' => $singleReward->amount,
                'net_amount' => $singleReward->amount,
            ]);
            TransactionTracking::create([
                'user_id'     => $user->id,
                'type'        => 'reward',
                'keyword'     => 'UserPayout',
                'amount'      => $singleReward->amount,
                'net_amount'  => $singleReward->amount,
                'related_id'  => $payout?->id,
                'remark'      => 'Reward Income',
            ]);

            \App\Models\UnifiedTransaction::create([
                'user_id'          => $user->id,
                'amount'           => $singleReward->amount,
                'transaction_type' => 'Credit',
                'category'         => 'Reward Income',
                'status'           => 'Completed',
                'description'      => 'Achievement Reward: ' . ($singleReward->rank ?? 'Bonus'),
            ]);
        }
    }
    public static function packageUpgradeDirectIncome($user,$package = null){
        // $amount = $user->walletIncomesByKey();
        $amount = $package->unlock_balance;
        $actual_amount = $package->level_upgrade_income ?? round(($amount*5)/100,2);
        
        if ($actual_amount <= 0) {
            return;
        }

        $userpayout = UserPayout::where('user_id',$user->id)->where('income_type','bonus_income')->whereNull('is_requested')->whereNull('is_paid')->latest()->first();
        if($userpayout == null){
            $userpayout = UserPayout::create([
                'user_id' => $user->id,
                'income_type' => 'bonus_income',
                'amount' => $actual_amount,
                'net_amount' => $actual_amount,
            ]);
        }else{
            $userpayout->update([
                'amount' => ($userpayout->amount + $actual_amount),
                'net_amount' => ($userpayout->net_amount + $actual_amount),
            ]);
        }
        TransactionTracking::create([
            'user_id'     => $user->id,
            'type'        => 'bonus',
            'keyword'     => 'UserPayout',
            'amount'      => $actual_amount,
            'net_amount'  => $actual_amount,
            'related_id'  => $userpayout?->id,
            'remark'      => 'Package Upgrade Bonus',
            'from_user'   => $user->id,
        ]);

        \App\Models\UnifiedTransaction::create([
            'user_id'          => $user->id,
            'from_user_id'     => $user->id,
            'amount'           => $actual_amount,
            'transaction_type' => 'Credit',
            'category'         => 'Bonus Income',
            'status'           => 'Completed',
            'description'      => 'Package Upgrade Bonus (Self)',
        ]);

        // Trigger Team Profit Income for the receiver's uplines
        // self::processTeamProfitIncome($user, $actual_amount, 'Package Upgrade Bonus');
    }
    public static function ambasdorIncome($activeUser){
        $user_ids   = $activeUser->parent_string_array ?? [];
        $users      = User::whereIn('id',$user_ids)->get();
        if($users->count() > 0){
            foreach($users as $user){
                if($user->allPiadChildsExceptSelf()->count() >= 400){
                    $userpayout = UserPayout::where('user_id',$user->id)->where('income_type','ambasdor_income')->whereNull('is_requested')->whereNull('is_paid')->latest()->first();
                    if($userpayout == null){
                        $payout = UserPayout::create([
                            'user_id' => $user->id,
                            'income_type' => 'ambasdor_income',
                            'amount' => 500,
                            'net_amount' => 500,
                        ]);

                        TransactionTracking::create([
                            'user_id'     => $user->id,
                            'type'        => 'ambasdor',
                            'keyword'     => 'UserPayout',
                            'amount'      => 500,
                            'net_amount'  => 500,
                            'related_id'  => $payout->id,
                            'remark'      => 'Ambasdor Income',
                        ]);

                        \App\Models\UnifiedTransaction::create([
                            'user_id'          => $user->id,
                            'amount'           => 500,
                            'transaction_type' => 'Credit',
                            'category'         => 'Ambassador Income',
                            'status'           => 'Completed',
                            'description'      => 'Ambassador Achievement Bonus',
                        ]);
                    }
                }
            }
        }
    }
    public static function checkPackageEligibility($user, $category)
    {
        $walletAmount = $user->walletIncomesByKey('deposits');
        
        $teamACount = $user->allChildMembers->where('is_paid', 1)->count();
        $totalPaidDownline = $user->allPiadChildsExceptSelf()->count();
        $teamBCCount = $totalPaidDownline - $teamACount;

        if ($user->reward_points < ($category->required_points ?? 0)) {
            return [
                'status' => false,
                'message' => 'Insufficient reward points (Required: '.($category->required_points ?? 0).').',
            ];
        }

        if ($walletAmount < $category->unlock_balance) {
            return [
                'status' => false,
                'message' => 'Insufficient wallet balance (Required: $'.number_format($category->unlock_balance, 2).').',
            ];
        }

        if ($teamACount < ($category->team_a ?? 0)) {
            return [
                'status' => false,
                'message' => 'Requirements not meet for the Package (Need '.($category->team_a).' Team A active members).',
            ];
        }

        if ($teamBCCount < ($category->team_b_c ?? 0)) {
            return [
                'status' => false,
                'message' => 'Requirements not meet for the Package (Need '.($category->team_b_c).' Team B & C members).',
            ];
        }

        return [
            'status' => true,
            'message' => 'Valid',
        ];
    }

    public static function processTeamProfitIncome($childUser, $incomeAmount, $sourceCategory)
    {
        return; // Old Team Profit Disabled
        if ($incomeAmount <= 0) return;

        $levels = [
            1 => 'team_a_profit',
            2 => 'team_b_profit',
            3 => 'team_c_profit',
        ];

        $currentChild = $childUser;
        foreach ($levels as $level => $profitField) {
            $upline = $currentChild->sponsor;
            if (!$upline) break;

            $agentCategory = $upline->agentCategory();
            if ($agentCategory && isset($agentCategory->$profitField)) {
                $percentage = (float) $agentCategory->$profitField;
                if ($percentage > 0) {
                    $teamProfit = round(($incomeAmount * $percentage) / 100, 2);
                    if ($teamProfit > 0) {
                        \App\Models\UnifiedTransaction::create([
                            'user_id'          => $upline->id,
                            'from_user_id'     => $childUser->id,
                            'amount'           => $teamProfit,
                            'transaction_type' => 'Credit',
                            'category'         => 'Team Profit Income',
                            'status'           => 'Completed',
                            'description'      => 'Team Profit from ' . $childUser->member_id . ' (' . $sourceCategory . ') at Level ' . ($level == 1 ? 'A' : ($level == 2 ? 'B' : 'C'))  ,
                        ]);
                    }
                }
            }
            $currentChild = $upline;
        }
    }

    public static function processLevelROIIncome($childUser, $roiAmount)
    {
        if ($roiAmount <= 0) return;

        $percentages = [
            1 => 10,
            2 => 9,
            3 => 8,
            4 => 6,
            5 => 5,
            6 => 4,
            7 => 3,
            8 => 2,
            9 => 2,
            10 => 1,
        ];

        $currentChild = $childUser;

        for ($level = 1; $level <= 10; $level++) {
            $upline = $currentChild->sponsor;
            if (!$upline) break;

            // Check if upline has enough paid directs to open this level
            $paidDirectsCount = $upline->allChildMembers()->where('is_paid', 1)->count();

            if ($paidDirectsCount >= $level) {
                $percentage = $percentages[$level];
                $levelIncome = round(($roiAmount * $percentage) / 100, 2);

                if ($levelIncome > 0) {
                    \App\Models\UnifiedTransaction::create([
                        'user_id'          => $upline->id,
                        'from_user_id'     => $childUser->id,
                        'amount'           => $levelIncome,
                        'transaction_type' => 'Credit',
                        'category'         => 'Level ' . $level . ' Income',
                        'status'           => 'Completed',
                        'description'      => 'Level ' . $level . ' Income from ' . $childUser->member_id,
                    ]);
                }
            }

            $currentChild = $upline;
        }
    }
}
