<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'enc_password',
        'alter_email',
        'member_id',
        'epin',
        'parent_string',
        'left_count',
        'right_count',
        'sponsor_id',
        'parent_id',
        'parent_leg',
        'father_name',
        'gender',
        'dob',
        'mobile',
        'email_activation',
        'terms',
        'role',
        'is_blocked',
        'is_paid',
        'remember_token',
        'left_child_id',
        'right_child_id',
        'user_icon',
        'is_franchise',
        'kit_id',
        'profile_image',
        'upline_bonus_status',
        'wallet_addresses',
        'wallet_pin',
        'is_locked',
        'reward_points',
        'email_verified_at',
        'bep20_wallet_address',
        'google2fa_secret',
        'google2fa_setup_at',
        'registration_ip',
    ];

    protected $appends = [
        'profile',
        'today_tasks_income',
        'yesterday_tasks_income',
        'today_trade_income',
        'yesterday_trade_income',
        'today_total_commission',
        'today_direct_income',
        'yesterday_direct_income',
        'today_bonus_income',
        'yesterday_bonus_income',
        'today_team_commission',
        'yesterday_team_commission',
        'today_level_1_commission',
        'yesterday_level_1_commission',
        'today_level_2_commission',
        'yesterday_level_2_commission',
        'today_level_3_commission',
        'yesterday_level_3_commission',
        'yesterday_locking_profit',
        'profile_image_url',
        'income_balance',
    ];

    public static $panCardOption = [
        'pan_exist' => 'Pancard Available',
        'pan_not_exist' => 'Pancard Not Available'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'wallet_addresses' => 'array',
        'google2fa_setup_at' => 'datetime',
    ];

    public static function position(){
        return [
            'left' => 'Left',
            'right' => 'Right'
        ];
    }


    public function wallet(){
        return $this->belongsTo(UserWallet::class,'username','username');
    }

    public static function getWalletAmount(){
        $userId = \Auth::guard('member')->user()->username;
        $userWallet = UserWallet::where(['username'=>$userId])->first();
        if($userWallet != null) {
            return $userWallet->amount;
        }else{
            return 0;
        }
    }

    public function position_rel(){
        return $this->belongsTo(Position::class,'member_id','user_id');
    }

    public function bank_details(){
        return $this->hasOne(UserBankDetail::class,'user_id','id');
    }

    public function user_address(){
        return $this->hasOne(UserAddress::class,'user_id','id');
    }

    public function registrationRequest()
    {
        return $this->hasOne(RegistrationRequest::class, 'user_id', 'id');
    }

    public function profile(){
        return $this->belongsTo(UserProfile::class,'id','user_id');
    }

    public function getProfileAttribute(){
        return $this->profile()->first();
    }

    public function used_pin_rel(){
        return $this->belongsTo(Epin::class,'epin','pin_no')->with(['joining_kit_rel']);
    }

    public function profile_rel(){
        return $this->belongsTo(UserProfile::class,'id','user_id');
    }

    public function transfer_pin_rel(){
        return $this->hasMany(Epin::class,'transfer_to','id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id','member_id');
    }

    public function leftChild()
    {
        return $this->belongsTo(User::class, 'left_child_id');
    }

    public function rightChild()
    {
        return $this->belongsTo(User::class, 'right_child_id');
    }

    public function getLeftCount()
    {
        return $this->countChildren('left_child_id');
    }

    public function getRightCount()
    {
        return $this->countChildren('right_child_id');
    }

    private function countChildren($childColumn)
    {
        $count = 0;
        $childId = $this->$childColumn;

        if ($childId) {
            $child = self::find($childId);
            $count += 1 + $child->countChildren('left_child_id') + $child->countChildren('right_child_id');
        }

        return $count;
    }

    public function kyc_rel(){
        return $this->belongsTo(KycDoc::class,'id','user_id');
    }

    public function kyc_docs(){
        return $this->hasMany(KycDoc::class,'user_id','id');
    }

    public function saleEntries() {
        return $this->hasMany(SaleEntry::class,'user_id','id');
    }

    public function isFirstSale() {
        return $this->saleEntries()->count();
    }

    public function joiningKit(){
        return $this->belongsTo(Epin::class,'epin','pin_no');
    }

    public function achievedRewards(){
        return $this->hasMany(RewardAchiever::class,'user_id','id');
    }

    public function latestReward(){
        return $this->achievedRewards()->latest()->first();
    }

    public function allChildMembers(){
        return $this->hasMany(self::class,'sponsor_id','member_id');
    }
    public function latestChildMember(){
        return $this->hasOne(self::class,'sponsor_id','member_id')->latest();
    }
    public function userRole(){
        return $this->belongsTo(Role::class,'role','id');
    }
    public function sponsor(){
        return $this->belongsTo(self::class,'sponsor_id','member_id');
    }
    public function regisBonus(){
        return $this->hasMany(RegisterationBonus::class,'user_id','id');
    }
    public function payouts(){
        return $this->hasMany(UserPayout::class,'user_id','id');
    }
    public function joiningKits(){
        return $this->belongsToMany(JoiningKit::class,'user_kits','user_id','kit_id');
    }

    public function latestJoiningKit(){
        return $this->hasOneThrough(JoiningKit::class, UserKit::class, 'user_id', 'id', 'id', 'kit_id')->latest();
    }
    public function getParentStringArrayAttribute(){
        return ($this->parent_string) ? explode(',', $this->parent_string) : [];
    }
    public function unPaidPayouts(){
        return $this->payouts()->whereNull('is_paid');
    }

    // Dashboard Counts
    public function getLevelIncomeAttribute(){
        $walletTransferedIncome = 0;
        $levelIncome = $this->unPaidPayouts()->where('income_type','level')->sum('amount') ?? 0;
        $walletTransferedIncome = $this->walletTransations->where('keyword', 'self_transfer_level')->sum('amount') ?? 0;
        return round($levelIncome - $walletTransferedIncome, 2);
    }
    public function getDirectBonusIncomeAttribute(){
        $walletTransferedIncome = 0;
        $directIncome = $this->unPaidPayouts()->where('income_type','direct')->sum('amount') ?? 0;
        $walletTransferedIncome = $this->walletTransations->where('keyword', 'self_transfer_direct')->sum('amount') ?? 0;
        return round($directIncome - $walletTransferedIncome, 2);
    }
    public function getTotalIncomeAttribute(){
        return round(($this->unPaidPayouts()->whereNull('is_requested')->sum('net_amount')),2);
    }
    public function getAutopoolIncomeAttribute(){
        $walletTransferedIncome = 0;
        $autopoolIncome = $this->unPaidPayouts()->where('income_type','autopool')->sum('amount') ?? 0;
        $walletTransferedIncome = $this->walletTransations->where('keyword', 'self_transfer_autopool')->sum('amount') ?? 0;
        return round($autopoolIncome - $walletTransferedIncome, 2);
    }
    public function getBonusIncomeAttribute(){
        return $this->unPaidPayouts()->sum('pair_amount');
    }
    public function getTotalIncomeWithoutChargesAttribute(){
        return round($this->unPaidPayouts()->whereNull('is_requested')->sum('amount'),2);
    }

    public function getProfileImageUrlAttribute(){
        return $this->profile_image ? asset('storage/profile_images/'.$this->profile_image) : asset('images/nophoto_m.jpg');
    }
    public function childs(){
        return $this->hasMany(self::class,'parent_id','member_id');
    }
    public function allChilds(){
        return self::whereRaw("FIND_IN_SET(?, parent_string)", [$this->id])->get();
    }
    public function allPiadChildsExceptSelf(){
        return self::where('is_paid', 1)
                   ->whereRaw("FIND_IN_SET(?, parent_string)", [$this->id])
                   ->where('id', '!=', $this->id)
                   ->get();
    }
    public function allUnPaidChildsExceptSelf(){
        return self::where('is_paid', 0)
                   ->whereRaw("FIND_IN_SET(?, parent_string)", [$this->id])
                   ->where('id', '!=', $this->id)
                   ->get();
    }
    public function allPaidChilds(){
        return self::where('is_paid', 1)
                   ->whereRaw("FIND_IN_SET(?, parent_string)", [$this->id])
                   ->get();
    }
    public function autoPoolIncomes(){
        return $this->hasMany(AutopoolIncome::class,'user_id','id');
    }
    public function latestAutopoolIncomes(){
        $kit_id = $this?->latestJoiningKit?->autopool_id ?? null;
        return $this->hasMany(AutopoolIncome::class,'user_id','id')->when($kit_id,function($query) use($kit_id){
            return $query->where('autopool_id',$kit_id);
        })->orderBy('level','asc');
    }
    public function latestAutopool(){
        return $this->latestJoiningKit->autoPool ?? null;
    }
    public function walletTransations() {
        return $this->hasMany(WalletTransaction::class, 'user_id', 'member_id')->orderBy('created_at', 'desc');
    }
    public function recievedMoney() {
        return $this->hasMany(WalletTransaction::class, 'transfered_to', 'member_id');
    }

    protected $walletCache = null;

    public function unifiedTransactions() {
        return $this->hasMany(UnifiedTransaction::class, 'user_id', 'id')->orderBy('created_at', 'desc')->orderBy('id', 'desc');
    }

    public function dailyTrades() {
        return $this->hasMany(DailyTrade::class, 'user_id', 'id');
    }

    public function getTradingLockedAmount() {
        $todayTrade = $this->dailyTrades()
            ->where('trade_date', now()->toDateString())
            ->where('status', 'pending')
            ->first();

        if ($todayTrade) {
            $agentCategory = $this->agentCategory();
            if ($agentCategory) {
                $packagePrice = $agentCategory->unlock_balance;
                $pastTradeEarnings = \App\Models\UnifiedTransaction::where('user_id', $this->id)
                    ->where('category', 'Trade Income')
                    ->where('status', 'Completed')
                    ->whereDate('created_at', '<', now()->toDateString()) // Exclude today's earnings from locking the base
                    ->sum('amount');
                return round($packagePrice + $pastTradeEarnings, 2);
            }
        }
        return 0;
    }

    public function walletIncomesByKey($key = 'totalIncome') {
        if ($this->walletCache === null) {
            // Fetching all transactions to ensure robust calculation
            $allTrans = $this->unifiedTransactions()
                ->where(function($q) {
                    $q->whereIn('status', ['Completed', 'success'])
                      ->orWhere(function($sq) {
                          $sq->where('category', 'Withdrawal');
                      });
                })
                ->get();

            $directIncome   = $allTrans->where('category', 'Direct Income')->sum('amount');
            $teamCommission = $allTrans->filter(fn($t) => str_contains($t->category, 'Level') || $t->category === 'Team Profit Income')->sum('amount');
            $taskCommission = $allTrans->where('category', 'Task Income')->sum('amount');
            $communityBonus = $allTrans->where('category', 'Community Income')->sum('amount');
            $uplineBonus    = $allTrans->where('category', 'Upline Income')->sum('amount');
            $rewardIncome   = $allTrans->where('category', 'Reward Income')->sum('amount');
            $bonusIncome    = $allTrans->where('category', 'Bonus Income')->sum('amount');
            $tradeIncome    = $allTrans->where('category', 'Trade Income')->sum('amount');
            $ambasdor       = $allTrans->where('category', 'Ambassador Income')->sum('amount');
            $roiIncome      = $allTrans->where('category', 'Daily ROI Income')->sum('amount');
            
            $stakingUnlocked = $allTrans->where('category', 'Staking Return')->sum('amount');
            $withdrawls     = $allTrans->where('category', 'Withdrawal')
                ->whereIn('status', ['Completed', 'success', 'Pending'])
                ->sum('amount');
            $deposits       = $allTrans->filter(function($t) {
                return $t->category === 'Deposit' || ($t->category === 'Fund Transfer' && $t->transaction_type === 'Credit');
            })->sum('amount');
            $lockedStaking  = $allTrans->where('category', 'Staking Debit')->sum('amount'); 

            $totalCredits   = $allTrans->where('transaction_type', 'Credit')->sum('amount');
            $totalDebits    = $allTrans->where('transaction_type', 'Debit')->sum('amount');

            $totalIncome    = round($totalCredits - $totalDebits, 2);
            $myIncome       = round($directIncome + $teamCommission + $taskCommission + $communityBonus + $uplineBonus + $rewardIncome + $bonusIncome + $tradeIncome + $ambasdor + $roiIncome, 2);

            $this->walletCache = [
                'directIncome'  => $directIncome,
                'teamCommission'=> $teamCommission,
                'taskCommission' => $taskCommission,
                'communityBonus'=> $communityBonus,
                'uplineBonus'   => $uplineBonus,
                'rewardIncome'  => $rewardIncome,
                'bonusIncome'   => $bonusIncome,
                'tradeIncome'   => $tradeIncome,
                'roiIncome'     => $roiIncome,
                'total'         => $totalIncome,
                'adminCharges'  => 0,
                'myIncome'      => $myIncome,
                'withdrawls'    => $withdrawls,
                'deposits'      => $deposits,
                'totalIncome'   => $totalIncome,
            ];
        }

        if ($key === 'all') {
            return $this->walletCache;
        }

        // Keep backward compatibility for the typo just in case, or fixed it everywhere
        if ($key === 'taskCommision') {
            return $this->walletCache['taskCommission'] ?? 0;
        }

        return $this->walletCache[$key] ?? 0;
    }

    public function getIncomeBalanceAttribute() {
        $total = $this->walletIncomesByKey('totalIncome');
        $deposits = $this->walletIncomesByKey('deposits');
        return round($total - $deposits, 2);
    }
    public function todayAttendedTasks(){
        return $this->hasMany(AttendedTask::class,'user_id','id')->whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'));
    }
    public function todayAttendedTasksByCategory($categoryId){
        return $this->hasMany(AttendedTask::class,'user_id','id')->where('category_id', $categoryId)->whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'));
    }
    public function getTodayTasksIncomeAttribute(){
        return (float) $this->unifiedTransactions()
            ->where('category', 'Task Income')
            ->where('status', 'Completed')
            ->whereDate('created_at', \Carbon\Carbon::now()->format('Y-m-d'))
            ->sum('amount');
    }

    public function getTodayTradeIncomeAttribute(){
        return (float) $this->unifiedTransactions()
            ->where('category', 'Trade Income')
            ->where('status', 'Completed')
            ->whereDate('created_at', \Carbon\Carbon::now()->format('Y-m-d'))
            ->sum('amount');
    }

    public function getYesterdayTradeIncomeAttribute(){
        return (float) $this->unifiedTransactions()
            ->where('category', 'Trade Income')
            ->where('status', 'Completed')
            ->whereDate('created_at', \Carbon\Carbon::now()->subDay()->format('Y-m-d'))
            ->sum('amount');
    }

    public function getYesterdayTasksIncomeAttribute(){
        return (float) $this->unifiedTransactions()
            ->where('category', 'Task Income')
            ->where('status', 'Completed')
            ->whereDate('created_at', \Carbon\Carbon::now()->subDay()->format('Y-m-d'))
            ->sum('amount');
    }

    public function getTodayDirectIncomeAttribute(){
        return (float) $this->unifiedTransactions()
            ->where('category', 'Direct Income')
            ->where('status', 'Completed')
            ->whereDate('created_at', \Carbon\Carbon::now()->format('Y-m-d'))
            ->sum('amount');
    }

    public function getYesterdayDirectIncomeAttribute(){
        return (float) $this->unifiedTransactions()
            ->where('category', 'Direct Income')
            ->where('status', 'Completed')
            ->whereDate('created_at', \Carbon\Carbon::now()->subDay()->format('Y-m-d'))
            ->sum('amount');
    }

    public function getTodayBonusIncomeAttribute(){
        return (float) $this->unifiedTransactions()
            ->where('category', 'Bonus Income')
            ->where('status', 'Completed')
            ->whereDate('created_at', \Carbon\Carbon::now()->format('Y-m-d'))
            ->sum('amount');
    }

    public function getYesterdayBonusIncomeAttribute(){
        return (float) $this->unifiedTransactions()
            ->where('category', 'Bonus Income')
            ->where('status', 'Completed')
            ->whereDate('created_at', \Carbon\Carbon::now()->subDay()->format('Y-m-d'))
            ->sum('amount');
    }
    public function getTotalCommissionIncomeAttribute(){
        $income = 0;
        if($this->payouts()->exists()){
            $income = $this->payouts()->where(function ($query) {
                $query->where('income_type', 'task_income')
                      ->orWhere('income_type', 'like', 'level_1%')
                      ->orWhere('income_type', 'like', 'level_2%')
                      ->orWhere('income_type', 'like', 'level_3%');
            })->whereNull('is_paid')->sum('amount') ?? 0;
        }
        return $income;
    }
    public function getTotalCommissionIncomeThisMonthAttribute(){
        $income = 0;
        if($this->payouts()->exists()){
            $income = $this->payouts()->where(function ($query) {
                $query->where('income_type', 'task_income')
                      ->orWhere('income_type', 'like', 'level_1%')
                      ->orWhere('income_type', 'like', 'level_2%')
                      ->orWhere('income_type', 'like', 'level_3%');
            })
            ->whereMonth('created_at', \Carbon\Carbon::now()->month)
            ->whereYear('created_at', \Carbon\Carbon::now()->year)
            ->sum('amount') ?? 0;
        }
        return $income;
    }
    public function getTodayTeamCommissionAttribute(){
        return (float) $this->unifiedTransactions()
            ->where(function($q) {
                $q->where('category', 'like', 'Level % Income')
                  ->orWhere('category', 'Team Profit Income');
            })
            ->where('status', 'Completed')
            ->whereDate('created_at', \Carbon\Carbon::now()->format('Y-m-d'))
            ->sum('amount');
    }

    public function getYesterdayTeamCommissionAttribute(){
        return (float) $this->unifiedTransactions()
            ->where(function($q) {
                $q->where('category', 'like', 'Level % Income')
                  ->orWhere('category', 'Team Profit Income');
            })
            ->where('status', 'Completed')
            ->whereDate('created_at', \Carbon\Carbon::now()->subDay()->format('Y-m-d'))
            ->sum('amount');
    }

    public function getTodayTotalCommissionAttribute(){
        return $this->today_team_commission + $this->today_tasks_income + $this->today_trade_income + $this->today_direct_income + $this->today_bonus_income;
    }
    public function latestUserAgentCategory()
    {
        return $this->hasOne(UserAgentCategory::class)->where('is_active', 1)->latest('id');
    }
    public function firstUserAgentCategory()
    {
        return $this->hasOne(UserAgentCategory::class)->where('is_active', 1)->oldest('id');
    }
    public function agentCategory(){
        return $this->latestUserAgentCategory?->agentCategory;
    }
    public function agentCategories(){
        return $this->belongsToMany(AgentCategory::class,'user_agent_categories','user_id','agent_category_id');
    }
    public function transactions(){
        return $this->hasMany(Transaction::class,'user_id','id');
    }
    public function withdrawls(){
        return $this->hasMany(Transaction::class,'user_id','id')->where('type','withdrawl');
    }
    public function deposits(){
        return $this->hasMany(Transaction::class,'user_id','id')->where('type','deposit');
    }
    public function stakings(){
        return $this->hasMany(Locking::class,'user_id','id');
    }
    public function lockedStakings(){
        return $this->hasMany(Locking::class,'user_id','id')->where('is_unlocked',0);
    }
    public function unlockedStakings(){
        return $this->hasMany(Locking::class,'user_id','id')->where('is_unlocked',1);
    }
     public function getTodayLevel1CommissionAttribute(){
        $income = 0;
        if($this->payouts->count() > 0){
            $income = $this->payouts()->where('income_type', 'like', 'level_1%')->whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'))->sum('amount') ?? 0;
        }
        return $income;
    }
    public function getYesterdayLevel1CommissionAttribute(){
        $income = 0;
        if($this->payouts->count() > 0){
            $income = $this->payouts()->where('income_type', 'like', 'level_1%')->whereDate('created_at',\Carbon\Carbon::now()->subDay()->format('Y-m-d'))->sum('amount') ?? 0;
        }
        return $income;
    }
    public function getTodayLevel2CommissionAttribute(){
        $income = 0;
        if($this->payouts->count() > 0){
            $income = $this->payouts()->where('income_type', 'like', 'level_2%')->whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'))->sum('amount') ?? 0;
        }
        return $income;
    }
    public function getYesterdayLevel2CommissionAttribute(){
        $income = 0;
        if($this->payouts->count() > 0){
            $income = $this->payouts()->where('income_type', 'like', 'level_2%')->whereDate('created_at',\Carbon\Carbon::now()->subDay()->format('Y-m-d'))->sum('amount') ?? 0;
        }
        return $income;
    }
    public function getTodayLevel3CommissionAttribute(){
        $income = 0;
        if($this->payouts->count() > 0){
            $income = $this->payouts()->where('income_type', 'like', 'level_3%')->whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'))->sum('amount') ?? 0;
        }
        return $income;
    }
    public function getYesterdayLevel3CommissionAttribute(){
        $income = 0;
        if($this->payouts->count() > 0){
            $income = $this->payouts()->where('income_type', 'like', 'level_3%')->whereDate('created_at',\Carbon\Carbon::now()->subDay()->format('Y-m-d'))->sum('amount') ?? 0;
        }
        return $income;
    }
    public function getYesterdayLockingProfitAttribute(){
        return $this->unlockedStakings()->whereDate('created_at',\Carbon\Carbon::now()->subDay()->format('Y-m-d'))->sum('return_amount') ?? 0;
    }
    public function checkIns(){
        return $this->hasMany(UserWeeklyCheckIn::class,'user_id','id');
    }
    public function pendingTasks($categoryId = null){
        return $this->hasMany(AttendedTask::class,'user_id','id')
                    ->when($categoryId, function($query) use ($categoryId) {
                        return $query->where('category_id', $categoryId);
                    })->whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'))->whereNull('completed_at');
    }

    public function transactionTrackings(){
        return $this->hasMany(TransactionTracking::class,'user_id','id');
    }

    public function getSecondAndThirdLevelDirects($isPaid = null)
    {
        // Level 1: User's direct referrals (Team A)
        $teamAIds = $this->allChildMembers()->pluck('member_id');

        // Level 2: Directs of Team A (Team B)
        $teamBIds = self::whereIn('sponsor_id', $teamAIds)->pluck('member_id');

        // We need users of Team B (whose sponsor is in Team A) 
        // and Team C (whose sponsor is in Team B)
        $sponsorIds = $teamAIds->merge($teamBIds);

        $query = self::whereIn('sponsor_id', $sponsorIds);
        
        if (!is_null($isPaid)) {
            $query->where('is_paid', $isPaid);
        }

        return $query->get();
    }

    // --- Withdrawal Limit Methods ---

    public function getTotalDeposits() {
        return (float) $this->unifiedTransactions()
            ->where(function($query) {
                $query->where('category', 'Deposit')
                      ->orWhere(function($q) {
                          $q->where('category', 'Fund Transfer')
                            ->where('transaction_type', 'Credit');
                      });
            })
            ->whereIn('status', ['Completed', 'success'])
            ->sum('amount');
    }

    public function getTotalWithdrawals() {
        // Includes Completed and Pending requests to prevent exceeding limit while processing
        return (float) $this->unifiedTransactions()
            ->where('category', 'Withdrawal')
            ->whereIn('status', ['Completed', 'success', 'Pending'])
            ->sum('amount');
    }

    public function getPaidDirectsCount() {
        return $this->allChildMembers()->where('is_paid', 1)->count();
    }

    public function getWithdrawalLimit() {
        $deposits = $this->getTotalDeposits();
        $directs = $this->getPaidDirectsCount();
        
        // Formula: (Deposits * 2) + (Directs * Deposits)
        // Which is equal to: Deposits * (2 + Directs)
        return round($deposits * (2 + $directs), 2);
    }

    public function getRemainingWithdrawalLimit() {
        $limit = $this->getWithdrawalLimit();
        $withdrawn = $this->getTotalWithdrawals();
        return max(0, round($limit - $withdrawn, 2));
    }

    public function getDownlineLevel($downline) {
        $parents = $downline->parent_string_array;
        
        $uplineIndex = array_search($this->id, $parents);
        $downlineIndex = array_search($downline->id, $parents);
        
        if ($uplineIndex !== false && $downlineIndex !== false) {
            return $downlineIndex - $uplineIndex;
        }
        
        return null;
    }

    public function getCurrentMonthDailyROI() {
        $agentCategory = $this->agentCategory();
        if (!$agentCategory) {
            return 0;
        }

        $rate = (float) $agentCategory->massive_order_rate;
        if ($rate <= 0) {
            return 0;
        }

        $totalDeposit = $this->getTotalDeposits();
        if ($totalDeposit <= 0) {
            return 0;
        }

        $daysInMonth = now()->daysInMonth;
        return round(($totalDeposit * ($rate / 100)) / $daysInMonth, 2);
    }

    public function getCurrentMonthAccumulatedROI() {
        $agentCategory = $this->agentCategory();
        if (!$agentCategory) {
            return 0;
        }

        $rate = (float) $agentCategory->massive_order_rate;
        if ($rate <= 0) {
            return 0;
        }

        // Fetch completed/success deposits of the user
        $deposits = $this->unifiedTransactions()
            ->where(function($query) {
                $query->where('category', 'Deposit')
                      ->orWhere(function($q) {
                          $q->where('category', 'Fund Transfer')
                            ->where('transaction_type', 'Credit');
                      });
            })
            ->whereIn('status', ['Completed', 'success'])
            ->get();

        $totalAccumulated = 0;
        $now = now();
        $daysInMonth = $now->daysInMonth;
        $currentMonthStart = $now->copy()->startOfMonth();
        $currentMonthEnd = $now->copy()->endOfMonth();

        foreach ($deposits as $deposit) {
            $depositDate = \Carbon\Carbon::parse($deposit->created_at);
            
            if ($depositDate->greaterThan($currentMonthEnd)) {
                continue;
            }

            // Calculate active days in the current month
            if ($depositDate->lessThan($currentMonthStart)) {
                $activeDays = $now->day;
            } else {
                $activeDays = $now->day - $depositDate->day + 1;
            }

            if ($activeDays > 0) {
                $depositROI = ($deposit->amount * ($rate / 100) / $daysInMonth) * $activeDays;
                $totalAccumulated += $depositROI;
            }
        }

        return round($totalAccumulated, 2);
    }

    public function getCurrentMonthDailyLevelROI() {
        // Find all paid downline members
        $downlines = self::where('is_paid', 1)
            ->whereRaw("FIND_IN_SET(?, parent_string)", [$this->id])
            ->where('id', '!=', $this->id)
            ->get();

        $totalDailyLevelROI = 0;
        $paidDirectsCount = $this->getPaidDirectsCount();

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

        foreach ($downlines as $downline) {
            $level = $this->getDownlineLevel($downline);
            
            if ($level && $level >= 1 && $level <= 10 && $paidDirectsCount >= $level) {
                $childDailyROI = $downline->getCurrentMonthDailyROI();
                if ($childDailyROI > 0) {
                    $percentage = $percentages[$level];
                    $totalDailyLevelROI += ($childDailyROI * $percentage) / 100;
                }
            }
        }

        return round($totalDailyLevelROI, 2);
    }

    public function getCurrentMonthAccumulatedLevelROI() {
        // Find all paid downline members
        $downlines = self::where('is_paid', 1)
            ->whereRaw("FIND_IN_SET(?, parent_string)", [$this->id])
            ->where('id', '!=', $this->id)
            ->get();

        $totalAccumulatedLevelROI = 0;
        $paidDirectsCount = $this->getPaidDirectsCount();

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

        foreach ($downlines as $downline) {
            $level = $this->getDownlineLevel($downline);
            
            if ($level && $level >= 1 && $level <= 10 && $paidDirectsCount >= $level) {
                $childAccumulatedROI = $downline->getCurrentMonthAccumulatedROI();
                if ($childAccumulatedROI > 0) {
                    $percentage = $percentages[$level];
                    $totalAccumulatedLevelROI += ($childAccumulatedROI * $percentage) / 100;
                }
            }
        }

        return round($totalAccumulatedLevelROI, 2);
    }
}
