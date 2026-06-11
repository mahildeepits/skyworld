<?php
namespace App\Services;

use App\Models\ContactUs;
use Validator;
use App\Mail\ContactUsEmail;
use App\Models\NewsEvent;
use App\Models\Announcement;
use App\Models\UserWeeklyCheckIn;
use DB;

class FeaturesService{
    public function contactSubmit($request){
        $validator = Validator::make($request->all(),[
            'name' =>'required',
            'email' =>'required|email',
            'phone' =>'required',
            'message' =>'required'
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        $data = $request->only('name','email','phone','message');
        $data['user_id'] = authUser()->id;
        try {
            $contactUs = ContactUs::create($data);
            if($contactUs){
                return response()->json(['status' => true,'refresh' => true,'message' => 'Your message has been submitted successfully','code' => 200]);
            }
            throw new \Exception("Error Processing Request", 1);
            
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => $th->getMessage(),'code' => 400]);
        }
    }
    public function sendContactEmail($data){
        try {
            \Mail::to($data['email'])->send(new ContactUsEmail($data));
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function storeNewsAndEvents($request){
        $validator = Validator::make($request->all(),[
            'title' =>'required',
            'description' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        if(!$request->hasFile('image') && !$request->has('id')){
            return response()->json(['error' => ['image' => ['The Image field is required']]],422);
        }
        $data = $request->only('title','description');
        $image = $request->file('image') ?? null;

        try {
            if($image != null){
                $imageName = time().'.'.$image->extension();
                $image->move(public_path('storage/uploads/news_events/'), $imageName);
                $data['image'] = $imageName;
            }
            if($request->has('id')){
                $NewsEvent = NewsEvent::find($request->id);
                $NewsEvent->update($data);
                return response()->json(['status' => true,'modal' => true,'message' => 'News / Event has been updated successfully','code' => 200]);
            }else{
                $NewsEvent = NewsEvent::create($data);
                if($NewsEvent){
                    return response()->json(['status' => true,'modal' => true,'message' => 'News / Event has been added successfully','code' => 200]);
                }
            }
            throw new \Exception("Error Processing Request", 1);
            
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => $th->getMessage(),'code' => 400]);
        }
    }
    public function storeAnnouncement($request){
        $validator = Validator::make($request->all(),[
            // Ensure at least description or image is provided.
            'description' => 'required_without:image|nullable',
            'image' => 'required_without:description|nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }

        $data = $request->only('description');
        // Set a default title since table requires it
        $data['title'] = 'Announcement ' . time();

        $image = $request->file('image') ?? null;

        try {
            if($image != null){
                $imageName = time().'.'.$image->extension();
                $image->move(public_path('storage/uploads/announcements/'), $imageName);
                $data['image'] = $imageName;
            }
            if($request->has('id')){
                $Announcement = Announcement::find($request->id);
                $Announcement->update($data);
                return response()->json(['status' => true,'modal' => true,'message' => 'Announcement has been updated successfully','code' => 200]);
            }else{
                $Announcement = Announcement::create($data);
                if($Announcement){
                    return response()->json(['status' => true,'modal' => true,'message' => 'Announcement has been added successfully','code' => 200]);
                }
            }
            throw new \Exception("Error Processing Request", 1);
            
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => $th->getMessage(),'code' => 400]);
        }
    }
        public function weeklyCheckin($request){
            // $validator = Validator::make($request->all(),[
            //     'checkin_day' => 'required',
            // ]);
            // if($validator->fails()){
            //     return response()->json(['status' => false,'message' => $validator->errors()['checkin_day'][0],'code' => 400]);
            // }
            $user = \App\Models\User::findOrFail(authUser()->id);
            $totalWeeklyCheckIn = UserWeeklyCheckIn::where('user_id',$user->id)->count();
            $data = [
                'user_id' => $user->id,
                'count' => $totalWeeklyCheckIn + 1,
                'check_in_date' => now(),
            ];
            DB::beginTransaction();
            try {
                $checkIn = UserWeeklyCheckIn::create($data);
                if($checkIn){
                    DB::commit();
                    return response()->json(['status' => true,'refresh' => true,'message' => 'Check-in has been submitted successfully','code' => 200]);
                }
                throw new \Exception("Error Processing Request", 1);
                
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['status' => false,'message' => $th->getMessage(),'code' => 400]);
            }
        }
}

?>