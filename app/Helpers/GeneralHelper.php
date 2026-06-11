<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

if(!function_exists('get_current_segment')){
    function get_current_segment(){
        return ucwords(str_replace('-',' ',request()->segment(2)));
    }

    function savePinTransaction($epins,$from,$to,$kit){
        foreach($epins as $k => $epinId){
            $epinHistory = new \App\Models\EpinHistory();
            $epinHistory->pin_no = $epinId;
            $epinHistory->joining_kit = $kit;
            $epinHistory->transfer_from = $from;
            $epinHistory->transfer_to = $to;
            $epinHistory->save();
        }

    }
}

if(!function_exists('getRolesToPluck')){
    function getRolesToPluck(){
       $roles = App\Models\Role::where('for_admin',0)->where('level','!=',0)->where('level','!=',6)->get()->pluck('name','id')->toArray();
       return $roles ?? [];
    }
}
if(!function_exists('getRolesToPluck')){
    function getRolesToPluck(){
       $roles = App\Models\Role::where('for_admin',0)->get()->pluck('name','id')->toArray();
       return $roles ?? [];
    }
}
if(!function_exists('addAdminRole')){
    function addAdminRole(){
        $role = App\Models\Role::updateOrCreate([
            'for_admin' => 1,
        ],[
            'name' => 'admin',
            'level' => 1
        ]);
    }
}
if(!function_exists('getEducations')){
    function getEducations(){
       $educations = [
        '10th' => '10th',
        '12th' => '12th',
        'Graduation' => 'Graduation',
       ];
       return $educations ?? [];
    }
}
if(!function_exists('getTechnicalEducations')){
    function getTechnicalEducations(){
       $techEducations = [
        'DCA' => 'DCA',
        'ADCA' => 'ADCA',
        'PGDCA' => 'PGDCA',
        'BCA' => 'BCA',
       ];
       return $techEducations ?? [];
    }
}
if(!function_exists('getSkills')){
    function getSkills(){
       $skills = [
        'Dance' => 'Dance',
        'Singing' => 'Singing',
        'Guitar' => 'Guitar',
        'Piano' => 'Piano',
        'Others' => 'Others',
       ];
       return $skills ?? [];
    }
}
if(!function_exists('getLanguages')){
    function getLanguages(){
       $languages = [
        'Hindi' => 'Hindi',
        'English' => 'English',
       ];
       return $languages ?? [];
    }
}
if(!function_exists('getExperiences')){
    function getExperiences(){
       $experiences = [
        'Monitory' => 'Monitory',
        'Servey' => 'Servey',
        'Field' => 'Field',
        'Other' => 'Other',
       ];
       return $experiences ?? [];
    }
}
if(!function_exists('getInspirations')){
    function getInspirations(){
       $inspirations = [
        'Teaching' => 'Teaching',
        'Counseling' => 'Counseling',
        'Field work' => 'Field work',
        'Visiting' => 'Visiting',
       ];
       return $inspirations ?? [];
    }
}


if(!function_exists('isStudent')){
    function isStudent(){
       return auth()->guard('member')->user()->userRole()->first()->id == 7;
    }
}

if(!function_exists('calculateIncentive')){
    function calculateIncentive($totalStudents) {
        $incentive = 0;
        $remainStudents = $totalStudents;

        while ($remainStudents >= 60) {
            $incentive += 20000; // Add incentive
            $remainStudents -= 60; // Deduct 60 from total students
        }

        return [
            'student_count' => $remainStudents,
            'incentive' => $incentive
        ];
    }
}
if(!function_exists('makeMemberId')){
    function makeMemberId($adhaar_number){
        $adhaar_number_array = str_split($adhaar_number, 4);
        $alpha = ['BMW','NF',''];
        $string = '';
        foreach($adhaar_number_array as $key => $digits){
            $string .= $digits.$alpha[$key];
        }
        return $string;
    }
}
if (!function_exists('generateCode')) {
    function generateCode(){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $code = '';
        for ($i = 0; $i < 10; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
        }
        return $code;
    }
}
if (!function_exists('changeTree')) {
    function changeTree($parent,$user){
        $users = \App\Models\User::where('parent_string','!=','')->whereNotIn('id',[$user->id])->get()->map(function($item)use($user,$parent){
            if(in_array($user->id,$item->parent_string_array)){
                $item->update([
                    'parent_string' => $item->parent_string . ',' . $parent->id,
                ]);
            }
        })->filter();
        $user->update([
            'parent_string' => $user->parent_string . ',' . $parent->id,
            'parent_id' => $parent->member_id,
            'sponsor_id' => $parent->member_id
        ]);
    }
}
if(!function_exists('getSponsoredChilds')){
    function getSponsoredChilds($user_ids){
        $users = collect([]);
        if(count($user_ids) > 0){
            $users = \App\Models\User::whereIn('sponsor_id',$user_ids)->get();
        }
        return $users;
    }
}
if(!function_exists('getJoiningKits')){
    function getJoiningKits($is_red = false) {
        $kits = App\Models\JoiningKit::where(['is_red'=>$is_red])->get();
        if($kits->count() > 0){
            return $kits->pluck('name','id')->toArray();
        }
        return [];
    }
}
if(!function_exists('isRewardAmountUpdated')){
    function isRewardAmountUpdated($user = null){
        if($user == null){
            $user == auth()->guard('member')->user();
        }
        $user->update(['is_credit_at' => null]);
    }
}
if(!function_exists('generate_referer_code')){
    function generate_referer_code(){
        $ref_code = strtoupper(\Str::random(20));
        $ecommerceExists = \DB::connection('ecommerce')->table('users')->where('referral_code', $ref_code)->exists();
        if ($ecommerceExists) {
            return generate_referer_code();
        }
        return $ref_code;
    }
}
if(!function_exists('isEcommerceUserExists')){
    function isEcommerceUserExists($email){
        $ref_code = strtoupper(\Str::random(20));
        $ecommerceExists = \DB::connection('ecommerce')->table('users')->where('email', $email)->exists();
        if ($ecommerceExists) {
            return true;
        }
        return false;
    }
}
if(!function_exists('authUser')){
    function authUser($guard = 'member'){
        $auth = auth()->guard($guard);
        if($auth->check()){
            return $auth->user();
        }
        return null;
    }
}
if(!function_exists('generateRandomNumber')){
    function generateRandomNumber($length = 5) {
        $randomNumber = '';
        for ($i = 0; $i < $length; $i++) {
            $randomNumber .= rand(0, 9);
        }
        return $randomNumber;
    }
}
if(!function_exists('generateUniqueMemberId')){
    function generateUniqueMemberId($prefix, $existingIds, $length = 5) {
        do {
            $randomNumber = generateRandomNumber($length);
            $newMemberId = $prefix . $randomNumber;
        } while (in_array($newMemberId, $existingIds));

        return $newMemberId;
    }
}
if(!function_exists('autopoolPluck')){
    function autopoolPluck($type = 'array') {
        $autopools = \App\Models\AutoPool::whereDoesntHave('joiningKit')->get();
        if($type == 'array'){
            $autopools = $autopools->pluck('name','id')->toArray();
        }
        return $autopools;
    }
}
if(!function_exists('autopoolIncomeToAchive')){
    function autopoolIncomeToAchive(){
        $user = authUser('member');
        $latest_autopool = $user->latestAutopool();
        $counts = [
            1 => 'count_4',
            2 => 'count_16',
            3 => 'count_64',
            4 => 'count_256',
            5 => 'count_1024',
            6 => 'count_4096',
            7 => 'count_16384',
        ];
        $latest_autopool_incomes = $user->latestAutopoolIncomes;
        $income = 0;
        if($latest_autopool != null){
            $lastLevelIncome = $latest_autopool_incomes->last() ?? 0;
            foreach ($counts as $level => $column) {
                if($level > $lastLevelIncome->level){
                    $income = $latest_autopool->$column;
                    break;
                }
            }
        }
        return $income;
    }
}
if(!function_exists('dashboardAutopoolIncome')){

    function dashboardAutopoolIncome($user, $autopool = null){
        $user_date = \Carbon\Carbon::parse($user->created_at); // Parse user creation date
        $current_date = now(); // Current date
        $month_difference = $user_date->diffInMonths($current_date);
        // Mapping months to levels
        $month_level_array = [
            6  => 1,
            7  => 2,
            8  => 3,
            9  => 4,
            10 => 5,
            11 => 6,
            12 => 7,
        ];
        // Mapping levels to columns
        $level_column_array = [
            1 => 'count_4',
            2 => 'count_16',
            3 => 'count_64',
            4 => 'count_256',
            5 => 'count_1024',
            6 => 'count_4096',
            7 => 'count_16384',
        ];
        if ($autopool === null) {
            $autopool = \App\Models\AutoPool::first(); // Default autopool
        }
        $total_income = 0; // Initialize total income
        $count = 4;
        foreach ($month_level_array as $month => $level) {
            if ($month_difference >= $month) { // Only process eligible levels
                $column = $level_column_array[$level]; // Get the column for the current level
                $rate = $autopool->$column ?? 0;       // Dynamically fetch the rate for the level
                // dump($month.'-----month----'.$column.'-----'.$rate.'------'.$count);

                // Fetch the user's current income for this level
                $autopoolIncome = AutopoolIncome::where('user_id', $user->id)
                    ->where('autopool_id', $autopool->id)
                    ->where('level', $level)
                    ->first();

                if ($autopoolIncome === null) {
                    $income = $rate;
                } else {
                    // If prior income exists, use the last recorded data for calculations
                    $income = $rate;
                }

                $total_income += ($income * $count); // Add to total income
            }else{
                break;
            }
            $count = $count * 4;
        }
        // dd('here');
        return round($total_income, 2); // Return the total income rounded to 2 decimals
    }

}
if(!function_exists('getMatchedAutoPoolIncome')){
    function getMatchedAutoPoolIncome($autoPoolIncomes){
        $level_column_array = [
            1 => 'count_4',
            2 => 'count_16',
            3 => 'count_64',
            4 => 'count_256',
            5 => 'count_1024',
            6 => 'count_4096',
            7 => 'count_16384',
        ];
        $total_income = 0;
        foreach ($autoPoolIncomes as $autoPoolIncome) {
            $level = $autoPoolIncome->level ?? 1;
            $income = $autoPoolIncome->income ?? 0;
            $income = round($income,2);
            $autopool = $autoPoolIncome?->autopool ?? null;
            $column = $level_column_array[$level];
            $levelIncome = round(($autopool->$column * pow(4,$level)),2);
            if($income == $levelIncome){
                $total_income += $income;
            }
        }
        return round($total_income,2);
    }
}

if(!function_exists('checkOtp')){
    function checkOtp($otp_code){
        $otp = \App\Models\WalletOtp::where('user_id',authUser('member')->id)->where('otp', $otp_code)->first();
        if($otp != null){
            if($otp->is_used == 1){
                return ['status' => false,'message' => 'OTP is already used'];
            }
            if($otp->is_expired == 1){
                return ['status' => false,'message' => 'OTP is expired'];
            }
            $expire_at = \Carbon\Carbon::parse($otp->created_at)->addMinutes(5);
            if($expire_at <= now()){
                $otp->update(['is_expired' => 1]);
                return ['status' => false,'message' => 'OTP is expired'];
            }else{
                return ['status' => true,'message' => 'OTP varified successfully'];
            }
        }
        return ['status' => false,'message' => 'OTP is not correct'];
    }
}

if(!function_exists('uniqueOtpCode')){
    function uniqueOtpCode(){
        $code = mt_rand(1000,9999);
        $otp = \App\Models\WalletOtp::where('otp', $code)->first();
        if($otp != null){
            return uniqueOtpCode();
        }
        return $code;
    }
}
if(!function_exists('generateRandomOrderData')){
    function generateRandomOrderData(){
        // Generate random order number
        $orderNo = strtoupper(\Illuminate\Support\Str::random(4)) . rand(100000, 999999);

        // Generate random order date before today
        $randomTimestamp = rand(strtotime('-1 year'), strtotime('-1 day'));
        $orderDate = date('Y-m-d', $randomTimestamp); // Format: 30/April/2025

        return [
            'order_no' => $orderNo,
            'order_date' => $orderDate,
        ];
    }
}
if(!function_exists('getQRCode')){
    function getQRCode($data,$size = 80){
        return QrCode::size($size)->generate($data);
    }
}
if(!function_exists('getExtraRewards')){
    function getExtraRewards(){
        return [
            [
                'image_path' => asset('images/daily-reward-7.png'),
                'amount' => 200,
                'name' => 'Weekly 100 ID`s With Team',
                'pairs' => 100,
                'days' => 7,
            ],
            [
                'image_path' => asset('images/3000.png'),
                'amount' => 3000,
                'name' => 'Weekly 1000 ID`s With Team',
                'pairs' => 1000,
                'days' => 30,
            ],[
                'image_path' => asset('images/25000.png'),
                'amount' => 25000,
                'name' => 'Weekly 5000 ID`s With Team',
                'pairs' => 5000,
                'days' => 90,
            ],
        ];
    }
}
if(!function_exists('checkRewardEligibility')){
    function checkRewardEligibility($user,$reward){
        $eligibleChildren = $user->allChildMembers->filter(function ($child) use ($reward,$user) {
            if ($child->email_verified_at) {
                $childDate = \Carbon\Carbon::parse($child->email_verified_at); // ✅ correct value

                $startTime = \Carbon\Carbon::parse($user->email_verified_at);
                $endTime = $startTime->copy()->addDays($reward->days);
                return $childDate->between($startTime, $endTime);
            }
            return false;
        });
        if($eligibleChildren->count() >= $reward->pairs){
            return true;
        }
        return false;
    }
}

if (!function_exists('getIncomeByCategory')) {
    function getIncomeByCategory($categoryId, $type, $user = null) {
        if ($user === null) {
            $user = authUser();
        }

        $income = 0;
        switch ($type) {
            case 'todayTasks':
                if($user->transactionTrackings->count() > 0){
                    $income = $user
                                ->transactionTrackings()
                                ->where('type', 'task')
                                ->where('related_id', $categoryId)
                                ->whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'))
                                ->sum('amount') ?? 0;
                }
                return $income;
                break;
            case 'yesterdayTasks':
                if($user->transactionTrackings->count() > 0){
                    $income = $user
                                ->transactionTrackings()
                                ->where('type', 'task')
                                ->where('related_id', $categoryId)
                                ->whereDate('created_at', \Carbon\Carbon::yesterday()->format('Y-m-d'))
                                ->sum('amount') ?? 0;
                }
                return $income;
                break;

            default:
                return $income;
                break;
        }

    }
}

if (!function_exists('getWalletAddress')) {
    function getWalletAddress($user = null, $walletType = 'BEP-20') {
        if ($user == null) {
            $user = authUser();
        }
        $userWallets = $user->wallet_addresses ?? [];
        return $userWallets[$walletType] ?? null;
    }
}

if (!function_exists('adminMember')) {
    function adminMember() {
        return User::whereIn('member_id', ['Company', 'COMPANY', 'TOP', 'Top'])->first();
    }
}


if (!function_exists('backupBeforeWalletAddressChange')) {
    function backupBeforeWalletAddressChange($userId, $subFolder, $tables = [])
    {
        if (empty($tables)) {
            $tables = ['deposits_trackings', 'payouts', 'transactions', 'users', 'user_kits', 'user_payouts', 'transaction_trackings'];
        }
        // Ensure storage/backups folder exists
        $rootBackupPath = storage_path('dbtable_backups');

        // Add optional subfolder
        $backupPath = $subFolder ? $rootBackupPath . '/' . trim($subFolder, '/') : $rootBackupPath;

        // Ensure the folder exists
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $filename = "db_bk_{$userId}-" . date('d-m-Y-H-i-s') . ".sql";
        $fullPath = $backupPath . '/' . $filename;
    
        $sqlContent = "";

        foreach ($tables as $table) {
            // Table structure
            $createTable = DB::select("SHOW CREATE TABLE {$table}");
            $sqlContent .= $createTable[0]->{'Create Table'} . ";\n\n";
        
            // Table data
            $rows = DB::table($table)->get();
            if ($rows->isEmpty()) continue;
        
            $columns = array_keys((array)$rows->first()); // Same columns for all rows
            $sqlContent .= "INSERT INTO `{$table}` (`" . implode('`,`', $columns) . "`) VALUES \n";
        
            $allValues = [];
            foreach ($rows as $row) {
                $values = array_map(function ($value) {
                    if ($value === null) return "NULL";
                    return "'" . str_replace("'", "''", $value) . "'";
                }, array_values((array)$row));
                $allValues[] = "(" . implode(',', $values) . ")";
            }
        
            $sqlContent .= implode(",\n", $allValues) . ";\n\n";
        }
    
        file_put_contents($fullPath, $sqlContent);
    
        return $fullPath;
    }
}
