<?php
namespace App\Services;

use App\Models\User;
use App\Models\UserPayout;
use App\Models\WalletTransaction;
use App\Models\TransactionTracking;

class IncomeService {
    protected $income = 0;
    protected $users; // All users in memory
    protected $usersByParent = [];

    public function settleAllBonuses() {
        $this->users = User::with(['agentCategory', 'agentCategory'])
            ->where('member_id', '!=', 'admin')
            ->get();

        // Index users by parent ID for faster lookup
        $this->usersByParent = $this->users->groupBy('parent_id');

        $paidUsers = $this->users->where('is_paid', 1);

        foreach ($paidUsers as $user) {
            if ($user->agentCategory() && $user->agentCategory()->community_bonus_rate > 0) {
                $this->settleLayerCommission($user);
            }
            $this->uplineIncome($user);
        }
    }

    protected function settleLayerCommission($user, $children = null, $level = 1) {
        if($children === null){
            $children = $this->getChildMembers($user->id);
        }
        if ($level > 3 || $children->isEmpty()) return;
        $this->income = 0;
        $nextLevel = collect();
        $baseRate = $user->agentCategory()->community_bonus_rate ?? 0;

        foreach ($children as $child) {
            $this->income += $child->total_commission_income_this_month ?? 0;

            $childBonusRate = $child->agentCategory()->community_bonus_rate ?? 0;
            $incomePercentage = max($baseRate - $childBonusRate, 0);

            $grandChildren = $this->getChildMembers($child->id);
            $this->settleTeamCommission($grandChildren, 2);
            if ($incomePercentage > 0 && $this->income > 0) {
                $amount = ($this->income * $incomePercentage) / 100;

                $userPayout = UserPayout::create([
                    'user_id' => $user->id,
                    'income_type' => 'community_bonus',
                    'amount' => $amount,
                    'net_amount' => $amount,
                ]);
                TransactionTracking::create([
                    'user_id'     => $user->id,
                    'type'        => 'community',
                    'keyword'     => 'UserPayout',
                    'amount'      => $amount,
                    'net_amount'  => $amount,
                    'related_id'  => $userPayout?->id,
                    'remark'      => 'Community Bonus for level '.$level,
                ]);
            }
            $nextLevel = $nextLevel->merge($this->getChildMembers($child->id));
        }
        $this->settleLayerCommission($user,$nextLevel, $level + 1);
    }

    protected function settleTeamCommission($children, $level) {
        if ($level > 3 || $children->isEmpty()) return;

        $nextLevel = collect();

        foreach ($children as $child) {
            $this->income += $child->total_commission_income_this_month ?? 0;
            $nextLevel = $nextLevel->merge($this->getChildMembers($child->id));
        }

        $this->settleTeamCommission($nextLevel, $level + 1);
    }

    protected function getChildMembers($parentId) {
        return $this->usersByParent[$parentId] ?? collect();
    }
    public function uplineIncome($user){
        if($user->upline_bonus_status){
            $sponsor = $user->sponsor;
            $commission = $sponsor->total_commission_income_this_month ?? 0;
            $amount = ($commission * 3) / 100;
            if($amount > 0){
                $userPayout = UserPayout::create([
                    'user_id' => $user->id,
                    'income_type' => 'upline_bonus',
                    'amount' => $amount,
                    'net_amount' => $amount,
                ]);
                TransactionTracking::create([
                    'user_id'     => $user->id,
                    'type'        => 'upline',
                    'keyword'     => 'UserPayout',
                    'amount'      => $amount,
                    'net_amount'  => $amount,
                    'related_id'  => $userPayout?->id,
                    'remark'      => 'Upline Bonus',
                ]);
            }
        }
    }
}
?>
