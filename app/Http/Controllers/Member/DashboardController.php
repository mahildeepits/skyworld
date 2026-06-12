<?php

namespace App\Http\Controllers\Member;

use Validator;
use App\Models\Epin;
use App\Models\Task;
use App\Models\UserPayout;
use App\Models\AdminCharge;
use App\Models\AttendedTask;
use Illuminate\Http\Request;
use App\Helpers\RewardHelper;
use App\Models\AgentCategory;
use App\Models\TransactionTracking;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Locking;

class DashboardController extends Controller
{
    public function index(){
        $agentCategories = AgentCategory::whereNotNull('name')->orderBy('unlock_balance','asc')->get();
        $announcement = \App\Models\Announcement::latest()->first();
        $reviews = AttendedTask::whereNotNull('review')->latest()->take(5)->get();
        return view('dashboard.index',compact('agentCategories','announcement','reviews'));
    }

    public function dismissAnnouncement(Request $request){
        $request->session()->put('announcement_shown', true);
        return response()->json(['status' => true]);
    }

    public function myLevels(){
        $agentCategories = AgentCategory::whereNotNull('name')->orderBy('unlock_balance','asc')->get();
        $user = authUser();
        $currentLevel = $user->agentCategory();
        
        $walletAmount = $user->walletIncomesByKey('deposits');
        $teamACount = $user->allChildMembers->where('is_paid', 1)->count();
        $totalPaidDownline = $user->allPiadChildsExceptSelf()->count();
        $teamBCCount = $totalPaidDownline - $teamACount;

        return view('dashboard.levels', compact('agentCategories', 'currentLevel', 'walletAmount', 'teamACount', 'teamBCCount'));
    }

    public function topupPage(){
        return view('dashboard.topup');
    }

    public function topupNow(Request $request){
        $user = \Auth::guard('member')->user();
        if($request->has('from_existing_pins')){
            $epins = Epin::where('transfer_to',$user->id)->whereNull('used_by')->get();
            if($epins->count() > 0){
                $epin = $epins->first();
                $epin->update(['used_by' => $user->id]);
                $user->update(['is_paid' => 1,'user_icon'=>'userpaid.png','epin'=>$epin->pin_no]);
                $this->generateUsersPayout($user);
                Session::flash('success','Success|Pins topup successfully!');
                return back();
            }else{
                Session::flash('error','Error|No pins found to topup!');
            }
        }
        if($request->isMethod('post')){
            if(!$request->has('pin_no')){
                Session::flash('error','Error|Please enter pin number!');
                return back();
            }
            $epin = Epin::wherePinNo($request->pin_no)->whereNull('used_by')->first();
            if($epin === null){
                Session::flash('error','Error|Invalid pin number!');
                return back();
            }
            $epin->update(['used_by' => $user->id]);
            $user->update(['is_paid' => 1,'user_icon'=>'userpaid.png','epin'=>$epin->pin_no]);
            $this->generateUsersPayout($user);
            Session::flash('success','Success|Pins topup successfully!');
            return back();
        }
    }

    public function generateUsersPayout($userModel){
        $authController = new AuthController;
        $chargesModel = AdminCharge::first();
        $authController->generatePayoutForSingleUser($userModel,$chargesModel);
        RewardHelper::giveRewards();
    }
    public function exploreCategory($id){
        $category       = AgentCategory::findOrFail(decrypt($id));
        $checkEligibility = $this->checkCategoryEligibility($category);
        if ($checkEligibility['status'] === false) {
            return redirect()->route('member.dashboard')->with('error', $checkEligibility['message']);
        }

        $attendedTasks = authUser()->todayAttendedTasksByCategory($category->id);
        $progress = $category->daily_order_limit > 0 ? $attendedTasks->count() / $category->daily_order_limit * 100 : 0;
        RewardHelper::achieveRank(authUser(),$category);
        return view('dashboard.explore-category',compact('category','progress','attendedTasks'));
    }
    public function checkCategoryEligibility($category){
        $user = authUser();
        return RewardHelper::checkPackageEligibility($user, $category);
    }

    public function upgradePackage($id){
        $category = AgentCategory::findOrFail(decrypt($id));
        $user = authUser();
        
        $eligibility = RewardHelper::checkPackageEligibility($user, $category);
        if ($eligibility['status'] === false) {
            return redirect()->back()->with('error', 'Error|' . $eligibility['message']);
        }

        RewardHelper::achieveRank($user, $category);
        
        return redirect()->back()->with('success', 'Success|Package upgraded to ' . $category->name . ' successfully!');
    }
    public function attendTask($id){
        $category           = AgentCategory::findOrFail(decrypt($id));
        $attendedTaskIds    = AttendedTask::where('user_id',authUser()->id)->where('category_id',$category->id)->whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'))->get()->pluck('task_id')->toArray();
        $user               = authUser();
        $walletTotal        = $user->walletIncomesByKey();
        $todayCommission    = $user->today_total_commission ?? 0;
        $packageAmt         = $category->unlock_balance;

        if (count($attendedTaskIds) >= $category->daily_order_limit) {
            return redirect()->back()->with('error', 'Error|You have already attended the maximum number of tasks for today.');
        }
        // if($walletTotal > 0 && $walletTotal < 100) {
        //     $walletTotal = 100;
        // }

        $percentage = $category->massive_order_rate ?? 2;

        // $userpayout         = UserPayout::where('user_id',$user->id)->where('income_type','package_id_'.$category->id)->whereDate('created_at', '<' ,\Carbon\Carbon::now()->format('Y-m-d'))->get();
        $userpayout         = TransactionTracking::where('user_id',$user->id)->where('type', 'task')->where('keyword','package_id_'.$category->id)->whereDate('created_at', '<' ,\Carbon\Carbon::now()->format('Y-m-d'))->get();
        $previousTaskIncome = $userpayout->sum('amount') ?? 0;
        $totalAmount        = (float) $packageAmt + $previousTaskIncome;
        $distributeAmount   = round( ($totalAmount * $percentage) / 100, 2);
        $perTaskAmount      = round($distributeAmount / $category->daily_order_limit, 2) ?? 0;

        $assignedTask       = AttendedTask::where('user_id',authUser()->id)->where('category_id',$category->id)->whereNull('completed_at')->whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'))->first();
        $task               = $assignedTask->task ?? null;

        if($assignedTask == null){
            $task = Task::whereNotNull( 'title')
            ->whereNotIn('id', $attendedTaskIds)
            ->inRandomOrder()
            ->first();
            $data = [
                'task_id' => $task->id,
                'user_id' => authUser()->id,
                'category_id' => $category->id,
            ];
            $assignedTask = AttendedTask::create($data);
        }
        if($task == null){
            abort(403,'Task not found');
        }
        return view('dashboard.task-attend',compact('task','category','perTaskAmount'));
    }
    public function completeTask(Request $request){
        $validator = Validator::make($request->all(),[
            'task_id' =>'required',
            'rating' => 'required',
            'review' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if($request->rating < 4){
            return response()->json(['errors' => ['rating' => ['Rating should be greater than or equal to 4']]],422);
        }
        $category           = AgentCategory::findOrFail($request->category_id);

        $user           = authUser();
        $pertaskAmount  = $request->per_task_amount ?? 0;
        $redirectUrl    = route('member.dashboard');

        if($request->has('redirectional_url') && $request->redirectional_url != null){
            $redirectUrl = $request->redirectional_url;
        }

        $attendedTask   = AttendedTask::where('user_id',$user->id)->where('task_id', $request->task_id)->whereNull('completed_at')->whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'))->first();
        if($attendedTask == null){
            return response()->json(['status' => false,'message' => 'Task not found','code' => 404]);
        }
        $reviewsArray   = $attendedTask->task->reviews ?? [];
        if(in_array($request->review, $reviewsArray)){
            return response()->json(['status' => false,'message' => 'Can`t submit the suggestion review, Add something addtional!','code' => 400]);
        }
        $data = [
            'rating' => $request->rating,
            'review' => $request->review,
            'completed_at' => now(),
        ];
        try {
            if($attendedTask->update($data)){
                if($pertaskAmount){
                    $taskPackageAmt = TransactionTracking::where('user_id',$user->id)->where('type', 'task')->where('keyword','package_id_'.$category->id)->whereDate('created_at', \Carbon\Carbon::now()->format('Y-m-d'))->latest()->first();
                    if($taskPackageAmt != null){
                        $taskPackageAmt->update([
                            'amount'        => round($taskPackageAmt->amount + $pertaskAmount, 2),
                            'net_amount'    => round($taskPackageAmt->net_amount + $pertaskAmount, 2),
                            'count'         => $taskPackageAmt->count + 1,
                        ]);
                    }else{
                        TransactionTracking::create([
                            'user_id'       => $user->id,
                            'type'          => 'task',
                            'keyword'       => 'package_id_'.$category->id,
                            'amount'        => round($pertaskAmount, 2),
                            'net_amount'    => round($pertaskAmount, 2),
                            'count'         => 1,
                            'related_id'    => $category->id,
                            'remark'        => 'Task income from Package - '.$category->name,
                        ]);
                    }
                    $userpayout = UserPayout::where('user_id', $user->id)->where('income_type','task_income')->whereNull('is_requested')->whereNull('is_paid')->whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'))   ->latest()->first();
                    if($userpayout != null){
                        $userpayout->update([
                            'amount'        => $userpayout->amount + $pertaskAmount,
                            'net_amount'    => $userpayout->net_amount + $pertaskAmount,
                        ]);
                    }else{
                        UserPayout::create([
                            'user_id'       => $user->id,
                            'income_type'   => 'task_income',
                            'amount'        => $pertaskAmount,
                            'net_amount'    => $pertaskAmount,
                        ]);
                    }

                    \App\Models\UnifiedTransaction::create([
                        'user_id'          => $user->id,
                        'amount'           => $pertaskAmount,
                        'transaction_type' => 'Credit',
                        'category'         => 'Task Income',
                        'status'           => 'Completed',
                        'description'      => 'Task income for task ID: ' . $attendedTask->task_id,
                    ]);

                    RewardHelper::levelIncome($user->id, $category);
                    // RewardHelper::achieveRank($user);
                    RewardHelper::giveRewards();
                }
                return response()->json(['status' => true,'redirect' => $redirectUrl,'message' => 'Task completed successfully','code' => 200]);
            }
            throw new \Exception("Error Processing Request", 1);

        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => 'Error processing request','code' => 500]);
        }

    }
    public function lockWalletAmount(Request $request){
        if($request->isMethod('post')){
            $user = authUser();
            $validator = Validator::make($request->all(),[
                'amount' =>'required|numeric',
                'duration' => 'required|in:1,3,5,7',
            ]);
            if($validator->fails()){
                return response()->json(['errors' => $validator->errors()], 422);
            }
            if($user->walletIncomesByKey() < $request->amount || $request->amount <= 0){
                return response()->json(['status' => false,'message' => 'Insufficient balance','code' => 400]);
            }
            
            $profits = [
                1 => 10000,
                3 => 30000,
                5 => 60000,
                7 => 70000,
            ];

            $data = [
                'user_id' => $user->id,
                'amount' => $request->amount,
                'locked_for' => \Carbon\Carbon::now()->addYears($request->duration),
                'return_amount' => $profits[$request->duration],
                'is_unlocked' => 0,
            ];
            try {
                \DB::beginTransaction();
                $locked = Locking::create($data);
                if($locked){
                    \App\Models\UnifiedTransaction::create([
                        'user_id'          => $user->id,
                        'amount'           => $request->amount,
                        'transaction_type' => 'Debit',
                        'category'         => 'Staking Debit',
                        'status'           => 'Completed',
                        'description'      => 'Locked for ' . $request->duration . ' Year' . ($request->duration > 1 ? 's' : ''),
                    ]);
                    \DB::commit();
                    return response()->json(['status' => true,'refresh' => true,'message' => 'Amount locked successfully','code' => 200]);
                }
                throw new \Exception("Error Processing Request", 1);
            }catch(\Throwable $th){
                \DB::rollback();
                return response()->json(['status' => false,'message' => 'Error processing request','code' => 400]);
            }
        }
        return view('dashboard.lock-wallet');
    }
    public function unlockStake(Request $request,$id){
        $stake = Locking::findOrFail(decrypt($id));
        
        if ($stake->is_unlocked == 1) {
            return response()->json(['status' => false,'message' => 'Already redeemed','code' => 400]);
        }

        if (\Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($stake->locked_for))) {
            return response()->json(['status' => false,'message' => 'Locking period is not yet completed','code' => 400]);
        }

        try {
            \DB::beginTransaction();
            $stake->update(['is_unlocked' => 1]);

            \App\Models\UnifiedTransaction::create([
                'user_id'          => $stake->user_id,
                'amount'           => $stake->return_amount,
                'transaction_type' => 'Credit',
                'category'         => 'Locking Profit',
                'status'           => 'Completed',
                'description'      => 'Profit from Locking (Principal: $' . number_format($stake->amount, 2) . ')',
            ]);

            \DB::commit();
            return response()->json(['status' => true, 'refresh' => true, 'message' => 'Amount redeemed successfully', 'code' => 200]);
        } catch(\Throwable $th) {
            \DB::rollback();
            return response()->json(['status' => false,'message' => 'Error processing request','code' => 400]);
        }
    }
}
