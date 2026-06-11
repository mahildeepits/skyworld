<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AnnouncementDataTable;
use App\DataTables\NewsEventDataTable;
use App\Http\Controllers\Controller;
use App\Services\FeaturesService;
use Illuminate\Http\Request;
use App\DataTables\ContactUsDataTable;
use App\Models\Announcement;
use App\Models\NewsEvent;

class MemberFeaturesController extends Controller
{
    public function contactsIndex(ContactUsDataTable $dataTable){
        return $dataTable->render('admin.features.contact');
    }
    public function newAndEventsIndex(NewsEventDataTable $dataTable){
        return $dataTable->render('admin.features.news-events');
    }
    public function newAndEventsCreate(){
        $id = request()->get('id') ?? null;
        $news_event = null;
        if($id != null){
            $news_event = NewsEvent::find(decrypt($id));
        }
        return ['status' => true,'html' =>view('admin.features.news-events-create',compact('news_event'))->render()];
    }
    public function newAndEventsStore(Request $request){
        return (new FeaturesService)->storeNewsAndEvents($request);
    }
    public function announcements(AnnouncementDataTable $dataTable){
       return $dataTable->render('admin.features.announcements');
    }
    public function announcementCreate(Request $request){
        if($request->isMethod('post')){
            return (new FeaturesService)->storeAnnouncement($request);
        }
        $id = request()->get('id') ?? null;
        $announcement = null;
        if($id != null){
            $announcement = Announcement::find(decrypt($id));
        }
        return ['status' => true,'html' => view('admin.features.announcement-create',compact('announcement'))->render()];
    }
    
    public function announcementDelete(Request $request, $id){
        $announcement = Announcement::find(decrypt($id));
        if($announcement){
            if($announcement->image){
                $imagePath = public_path('storage/uploads/announcements/' . $announcement->image);
                if(file_exists($imagePath)){
                    @unlink($imagePath);
                }
            }
            $announcement->delete();
            return redirect()->back()->with('success', 'Success|Announcement deleted successfully');
        }
        return redirect()->back()->with('error', 'Error|Announcement not found');
    }
}
