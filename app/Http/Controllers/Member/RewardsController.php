<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reward;
class RewardsController extends Controller
{
    public function index(){
        $user = \Auth::guard('member')->user();
        $type = request()->get('type') ?? null;
        $rewards = Reward::where('type',$type)->get();
        return view('rewards.index',['rewards'=>$rewards,'type' => $type]);
    }
}
