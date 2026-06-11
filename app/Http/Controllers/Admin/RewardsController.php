<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;

class RewardsController extends Controller
{
    public function index(){
        $rewards = Reward::get();
        return view('admin.rewards.index',compact('rewards'));
    }

    public function save(Request $request){
        $request->validate([
            'pairs' => 'required',
            'name' => 'required',
            // 'rank' => 'required',
            'days' => 'required',
            'amount' => 'required',
            'image' => 'required',
            'description' => 'required',
            'type' => 'required',
        ]);
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('storage/reward_images'), $imageName);
        if($request->description != null){
            $description = explode('|', $request->description);
        }
        Reward::create([
            'pairs' => $request->pairs,
            'name' => $request->name,
            // 'rank' => $request->rank,
            'days' => $request->days,
            'amount' => $request->amount,
            'image' => $imageName,
            'description' => $description,
            'type' => $request->type,
        ]);
        \Session::flash('success','Success|Reward added successfully');
        return back();
    }

    public function delete($id){
        $reward = Reward::find($id);
        $reward->delete();
        \Session::flash('success','Success|Reward deleted successfully');
        return back();
    }
}
