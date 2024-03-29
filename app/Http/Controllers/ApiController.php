<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\MainMenu;
use App\Models\Option;
use App\Models\Ourteam;
use App\Models\Testimonial;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Slug;
use App\Models\Slide;
use App\Models\Article;
use App\Models\Update;
use App\Models\Room;
use App\Models\Socialmedia;
use App\Models\Media;
use App\Models\Page;
use App\Models\Image;
use GuzzleHttp\Client;
use DB;

class ApiController extends Controller
{
    Private $status = 200;
  
    public function storecontact(Request $request)
    {
      $data = [
            'name' =>  $request['name'],
            'email' => $request['email'],
            'mobile' => $request['mobile'],
            'message' => $request['message'],
      ];
      Contact::create($data);
        //  $email = $request['email'];
         $bodyContent = [
             'toName' => $request['name'],
             'toemail'   => $request['email'],
             'tomobile'=> $request['mobile'],
             'tosubject'=> $request['message'],
         ];
         {  
             try {
               Mail::to('sakthiganapathi@dbcyelagiri.edu.in ')->send(new ContactFormMail($bodyContent));
            //    Mail::to($email)->send(new ContactFormMail($bodyContent));
            return response()->json(['status' => 'success', 'message'=> 'Request sent successfully']);
                }
                 catch (Exception $e) {
                    dd($e);
                    return response()->json(['status' => 'failed', 'message'=> 'Request Not sent successfully']);
             }
         } 
     
    }
    public function storecontactform(Request $request){
        try {
            $data = [
                'name' =>  $request['name'],
                'email' => $request['email'],
                'mobile' => $request['mobile'],
                'message' => $request['message'],
          ];
          Contact::create($data);
          return response()->json(['status' => 'success', 'message'=> 'Request sent successfully']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed', 'message'=> 'Failed to submit']);
        }
    }
    public function getpostdata($id){  
        $articles = Article::select(
            'articles.title',
            'articles.id',
            'articles.content',
            'articles.media_id',
            'articles.created_at',
            'categories.title as category_name',
             'categories.content as category_description'
        )
            ->leftJoin('categories', 'articles.category_id', '=', 'categories.id')
            ->where('articles.status', 1)
            ->where('categories.id', $id)
            ->get();
        
        $articles->each(function ($article) {
            $mediaUrl = null;
          $media = Media::find($article->media_id);
        
            if ($media) {
                $mediaUrl = $media->getUrl();
            }
            if($article->media_id != 1){
                $article->image = $mediaUrl;
            }
            
            $article->date = $article->created_at->format('d-m-Y');
        });
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'category_name'=> $articles[0]->category_name,
            'category_description'=> $articles[0]->category_description,
            'data' => $articles,
        ]);
        
    }
    public function getsliderimages($id){

        
        $Slides =Slide::select('slides.id','slides.title','slides.content','slides.bg','categories.title as category_name','categories.content as category_description','slides.created_at','slides.category_id')
        ->leftJoin('categories', 'slides.category_id', '=', 'categories.id')
        ->where('categories.id', $id)
        ->get();
    //   dd($Slides);
        $SlidesData = [];
        
        foreach ($Slides as $key => $slides) {
            $data = [
                'id' => $slides->id,
                'title' => $slides->title,
                'content' => $slides->content,
                'image' => $slides->bg,
                'category_id' =>$slides->category_id
,                'category_name' => $slides->category_name,
                'date' =>  $slides->created_at->format('d-m-Y'),
            ]; 
            $SlidesData[] = $data; 
        }
        if(count($SlidesData) > 0) {
            return response()->json(["status" => $this->status, "success" => true, 
                        "count" => count($SlidesData), "data" => $SlidesData]);
        }
        else {
            return response()->json(["status" => "failed",
            "success" => false, "message" => "Whoops! no record found"]);
        }
    }
    public function getnewsletter(){
        $updates = Update::select(
            'updates.title',
            'updates.file_id',
            'updates.id',
            'updates.content',
            'updates.media_id',
            'updates.created_at',
            'updates.eventdate',
            'categories.title as category_name'
        )
        ->leftJoin('categories', 'updates.category_id', '=', 'categories.id')
        ->where('updates.status', 1)
        ->get();
    
       $updates->each(function ($update) {
        $mediaUrl = null;
        $update->created_date = $update->created_at->format('d-m-Y');
        $update->eventdate = date("d-m-Y", strtotime($update->eventdate));
        $media = Media::find($update->media_id);
    
        if ($media) {
            $mediaUrl = $media->getUrl();
        }
        $update->file_url = asset('updates/' . $update->file_id);
        
        if($update->media_id != 1){
            $update->media_url = $mediaUrl;
        }
       
    });
    
    return response()->json([
        'success' => true,
        'message' => 'success',
        'data' => $updates,
    ]);
    
    }
    public function getpage($id){
     
        $pages = Page::select(
            'pages.title',
            'pages.id',
            'pages.content',
            'pages.media_id',
            'pages.created_at',
        )
        ->where('pages.status', 1)
        ->where('pages.id', $id)
        ->get();
        $pages->each(function ($page) {
            $mediaUrl = null;
        
            $media = Media::find($page->media_id);
        
            if ($media) {
                $mediaUrl = $media->getUrl();
            } 
            $page->media_url = $mediaUrl;
        });
        
        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $pages,
        ]);
    }
    public function getslidebar(){

        $articles = Article::select(
            'articles.title',
            'articles.id',
            'articles.content',
            'articles.media_id',
            'articles.created_at',
            'categories.title as category_name'
        )
            ->leftJoin('categories', 'articles.category_id', '=', 'categories.id')
            ->where('articles.status', 1)
            ->where('articles.category_id', 8)
            ->get();
        
        $articles->each(function ($article) {
            $mediaUrl = null;
          $media = Media::find($article->media_id);
        
            if ($media) {
                $mediaUrl = $media->getUrl('thumb');
            }
            if($article->media_id != 1){
                $article->image = $mediaUrl;
            }
            
            $article->date = $article->created_at->format('d-m-Y');
        });
       
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $articles,
        ]);
    }

    public function getGalleryimages(){

        $Image =Image::select('images.id','images.title','images.alt','images.path','images.created_at','categories.title as categoryname','categories.id as category_id')->leftJoin('categories' ,'categories.id','=','images.category_id')
        ->orderBy('images.id','desc')->get();
// dd( $Image);
        $imagesData = [];
        
        foreach ($Image as $key => $image) {
            $data = [
                'id' => $image->id,
                'title' => $image->title,
                'alt_tag' => $image->alt,
                'image' => asset($image->path),
                'date' =>  $image->created_at->format('d-m-Y'),
                'categoryname' => $image->categoryname,
                'category_id' => $image->category_id,
            ]; 
            $imagesData[] = $data; 
        }
        if(count($Image) > 0) {
            return response()->json(["status" => $this->status, "success" => true, 
                        "count" => count($imagesData), "data" => $imagesData]);
        }
        else {
            return response()->json(["status" => "failed",
            "success" => false, "message" => "Whoops! no record found"]);
        }
    }

    public function getteam(){

        $team = Ourteam::select(
            'ourteams.title',
            'ourteams.id',
            'ourteams.content',
            'ourteams.media_id',
            'ourteams.created_at',
            'ourteams.email',
            'ourteams.phone',
            'categories.title as category_name',
            'categories.id as category_id'
        )
            ->leftJoin('categories', 'ourteams.category_id', '=', 'categories.id')
            ->where('ourteams.status', 1)
            // ->where('ourteams.category_id', 8)
            ->orderBy('id','asc')
            ->get();
            $team->each(function ($item) {

                $mediaUrl = null;
                $media = Media::find($item->media_id);
              
                  if ($media) {
                    //   $mediaUrl = $media->getUrl('thumb');
                      $mediaUrl = $media->getUrl();
                  }
                  if($item->media_id != 1){
                      $item->image = $mediaUrl;
                  }
                  
                  $item->created_date = $item->created_at->format('d-m-Y');
            });
       
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $team,
        ]);
    }

    public function getteammembers($id){

        $team = Ourteam::select(
            'ourteams.title',
            'ourteams.id',
            'ourteams.content',
            'ourteams.media_id',
            'ourteams.created_at',
            'ourteams.email',
            'ourteams.phone',
            'categories.title as category_name',
            'categories.id as category_id'
        )
            ->leftJoin('categories', 'ourteams.category_id', '=', 'categories.id')
            ->where('ourteams.status', 1)
            ->where('ourteams.category_id', $id)
            ->orderBy('id','asc')
            ->get();
            $team->each(function ($item) {

                $mediaUrl = null;
                $media = Media::find($item->media_id);
              
                  if ($media) {
                    //   $mediaUrl = $media->getUrl('thumb');
                      $mediaUrl = $media->getUrl();
                  }
                  if($item->media_id != 1){
                      $item->image = $mediaUrl;
                  }
                  
                  $item->created_date = $item->created_at->format('d-m-Y');
            });
       
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $team,
        ]);
    }
    public function getresourcedata($id){
      
        $resource = Room::select(
            'rooms.title',
            'rooms.file_id',
            'rooms.id',
            'rooms.content',
            'rooms.media_id',
            'rooms.eventdate',
            'rooms.created_at',
            'categories.title as category_name'
        )
        ->leftJoin('categories', 'rooms.category_id', '=', 'categories.id')
        ->where('rooms.status', 1)
        ->where('rooms.category_id',$id)
        ->get();
    
       $resource->each(function ($update) {
        $mediaUrl = null;
        $update->created_date = $update->created_at->format('d-m-Y');
        // $update->edate = $update->eventdate->format('d-m-Y');
    
        $update->eventdate = date("d-m-Y", strtotime($update->eventdate));
        
        $media = Media::find($update->media_id);
    
        if ($media) {
            $mediaUrl = $media->getUrl();
        }
        if ($update->file_id) {
            $update->file_url = asset('newletter/' . $update->file_id);
        }
        
        
        if($update->media_id != 1){
            $update->media_url = $mediaUrl;
        }
       
    });
    $count = count($resource);
    return response()->json([
        'success' => true,
        'message' => 'success',
        'count' => $count,
        'data' => $resource,
    ]);
    }

    public function getVideos()
    {
        // dd('dj');
        $apiKey = 'AIzaSyDzi78e4zOTVdgbANgJV-YYfJ9AFDjK0UA';
        $channelId = 'UCueYcgdqos0_PzNOq81zAFg';

        $client = new Client();
        $response = $client->get("https://www.googleapis.com/youtube/v3/search", [
            'query' => [
                'part' => 'snippet',
                'channelId' => $channelId,
                'order' => 'date',
                'type' => 'video',
                'key' => $apiKey,
            ],
        ]);

        $videos = json_decode($response->getBody()->getContents(), true);

        // Now, $videos contains the response from the YouTube Data API.
        // You can process and display the videos as needed.

      
        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $videos,
        ]);
    }

    public function getyoutubedata(){
           
            $data = Socialmedia::all();
            return response()->json([
                'success' => true,
                'message' => 'success',
                'data' => $data,
            ]);
    }

    public function getcontactpage(){
       $contactpage = Option::where('key','contact')->first();
       
       
       $arrayData = unserialize($contactpage->value);
       $map = $arrayData['map'];
       $zoom = $arrayData['zoom'];
       $contactdata = [
            'mobile' => $arrayData['phone'],
            'cell' => $arrayData['cell'],
            'email' => $arrayData['email'],
            'address' => $arrayData['address'],
            'googleMapsUrl' => "https://maps.google.com/maps?q=".$map."&t=&z=".$zoom."&ie=UTF8&iwloc=&output=embed"
        ];
    
        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $contactdata,
        ]);
       
    }

    public function getactivitylist($id){


        $activties = Activity::select('activities.id','activities.title','categories.title as categoryname' ,'activities.activitydate','activities.content','activities.media_id','activities.status','activities.created_at')
        ->leftjoin('categories','categories.id','activities.category_id')
        ->where('status','1')
        ->where('category_id',$id)
        ->get();

        $activties->each(function ($activity){
            
            $activity->created_date = $activity->created_at->format('d-m-Y');
            $activity->activitydate = date("d-m-Y",strtotime($activity->activitydate));
            $media = Media::find($activity->media_id);
            if ($media) {
                $activity->media_url =$media->getUrl();
            }

        });
        $count = count($activties);
        if ($count) {
            return response()->json([
                'success' => true,
                'message' => 'success',
                'data' => $activties,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'failed',
                'count'=> $count
            ]);
        }
       

    }
    public function getmenus(){
     
            $results = DB::table('main_menus')
            ->select('main_menus.id', 'main_menus.title as label', 'main_menus.link as url', 'submenus.title as submenutitle', 'submenus.link as submenuUrl','submenus.id as submenuid','main_menus.status')
            ->leftJoin('submenus', 'submenus.parent_id', 'main_menus.id')
            ->where('main_menus.status', 1)
            ->orderBy('main_menus.Position', 'asc')
            ->orderBy('submenus.Position', 'asc')
            ->whereNull('submenus.deleted_at') 
            ->whereNull('main_menus.deleted_at') 
            ->get();

           $groupedResults = collect($results)->groupBy('id');

             $finalResult = $groupedResults->map(function ($group) {
             $mainMenu = $group->first();

             $children = $group->filter(function ($item) {
                return !empty($item->submenutitle) && !empty($item->submenuUrl);
            })->map(function ($item) {
                return [
                    'id' => $item->submenuid,
                    'label' => $item->submenutitle,
                    'url' => $item->submenuUrl,
                ];
            })->values();

            return [
                'id' => $mainMenu->id,
                'label' => $mainMenu->label,
                'url' => $mainMenu->url,
                'children' => $children->isNotEmpty() ? $children : null,
            ];
        })->values();

        $response = $finalResult->toArray();
        return response()->json($response);
    }

    public function gettestimonialdata($id){

        $articles = Testimonial::select(
            'testimonials.title',
            'testimonials.id',
            'testimonials.content',
            'testimonials.media_id',
            'testimonials.created_at',
            'categories.title as category_name',
            'categories.content as category_description'
        )
            ->leftJoin('categories', 'testimonials.category_id', '=', 'categories.id')
            ->where('testimonials.status', 1)
            ->where('categories.id', $id)
            ->get();
         
        $articles->each(function ($article) {
            $mediaUrl = null;
          $media = Media::find($article->media_id);
        
            if ($media) {
                $mediaUrl = $media->getUrl();
            }
            if($article->media_id != 1){
                $article->image = $mediaUrl;
            }
            
            $article->date = $article->created_at->format('d-m-Y');
        });
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'category_name'=> $articles[0]->category_name,
            'category_description'=> $articles[0]->category_description,
            'data' => $articles,
        ]);

    }

}
