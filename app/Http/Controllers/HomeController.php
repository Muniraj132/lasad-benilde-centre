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
use App\Models\Newsletter;
use App\Models\Resource;
use App\Models\Socialmedia;
use App\Models\Media;
use App\Models\Page;
use App\Models\Image;
use GuzzleHttp\Client;
use DB;

class HomeController extends Controller
{
    private $status = 200;
    public function gethomepagedetails(Request $request)
    {
        // Get parameters from the request with default values set to null
        $slideid = $request->input('slideid', null);
        $testid = $request->input('testid', null);
        $projectid = $request->input('projectid', null);

        #region Slider Data  
        $Slides = Slide::select('slides.id', 'slides.title', 'slides.content', 'slides.bg', 'categories.title as category_name', 'categories.content as category_description', 'slides.created_at', 'slides.category_id')
            ->leftJoin('categories', 'slides.category_id', '=', 'categories.id')
            ->when($slideid, function ($query) use ($slideid) {
                // Only apply the where clause if $slideid is not null
                $query->where('categories.id', $slideid);
            })
            ->get();

        $SlidesData = [];

        foreach ($Slides as $key => $slides) {
            $data = [
                'id' => $slides->id,
                'title' => $slides->title,
                'content' => $slides->content,
                'image' => $slides->bg,
                'category_id' => $slides->category_id,
                'category_name' => $slides->category_name,
                'date' => optional($slides->created_at)->format('d-m-Y'), // Use optional to handle potential null value
            ];
            $SlidesData[] = $data;
        }
        #endregion

        #region Newsletter Data    
        $newsletters = Newsletter::select(
            'newsletters.title',
            'newsletters.file_id',
            'newsletters.id',
            'newsletters.content',
            'newsletters.media_id',
            'newsletters.created_at',
            'newsletters.eventdate',
            'categories.title as category_name'
        )
            ->leftJoin('categories', 'newsletters.category_id', '=', 'categories.id')
            ->where('newsletters.status', 1)
            ->orderBy('newsletters.created_at', 'desc') // Add this line to order by created_at in descending order
            ->limit(4)
            ->get();

        $newsletters->each(function ($newsletter) {
            $mediaUrl = null;
            $newsletter->created_date = optional($newsletter->created_at)->format('d-m-Y');
            $newsletter->eventdate = optional(date_create($newsletter->eventdate))->format('d-m-Y');
            $media = Media::find($newsletter->media_id);

            if ($media) {
                $mediaUrl = $media->getUrl();
            }
            $newsletter->file_url = $newsletter->file_id ? asset('newsletter/' . $newsletter->file_id) : null;

            if ($newsletter->media_id != 1) {
                $newsletter->media_url = $mediaUrl;
            }
        });
        #endregion

        #region project Data 
        $resource = Resource::select(
            'resources.title',
            'resources.file_id',
            'resources.id',
            'resources.content',
            'resources.media_id',
            'resources.eventdate',
            'resources.created_at',
            'categories.title as category_name'
        )
            ->leftJoin('categories', 'resources.category_id', '=', 'categories.id')
            ->where('resources.status', 1)
            ->when($projectid, function ($query) use ($projectid) {
                $query->where('resources.category_id', $projectid);
            })
            ->orderBy('resources.created_at', 'desc') // Add this line to order by created_at in descending order
            ->limit(6) // Add this line to limit the result to the top four records
            ->get();

        $resource->each(function ($newsletter) {
            $mediaUrl = null;
            $newsletter->created_date = optional($newsletter->created_at)->format('d-m-Y');
            $newsletter->eventdate = optional(date_create($newsletter->eventdate))->format('d-m-Y');
            $media = Media::find($newsletter->media_id);
            if ($media) {
                $mediaUrl = $media->getUrl();
            }
            $newsletter->file_url = $newsletter->file_id ? asset('newsletter/' . $newsletter->file_id) : null;

            if ($newsletter->media_id != 1) {
                $newsletter->media_url = $mediaUrl;
            }
        });

        #endregion

        #region testimonial

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
            ->when($testid, function ($query) use ($testid) {
                // Only apply the where clause if $testid is not null
                $query->where('categories.id', $testid);
            })
            ->get();

        $articles->each(function ($article) {
            $mediaUrl = null;
            $media = Media::find($article->media_id);

            if ($media) {
                $mediaUrl = $media->getUrl();
            }
            $article->image = $article->media_id != 1 ? $mediaUrl : null;
            $article->date = optional($article->created_at)->format('d-m-Y');
        });
        #endregion 

        #region youtube Data
        $data = Socialmedia::all();
        #endregion

        #region Allgallery Data
        $Image = Image::select('images.id', 'images.title', 'images.alt', 'images.path', 'images.created_at', 'categories.title as categoryname')->leftJoin('categories', 'categories.id', '=', 'images.category_id')
            ->orderBy('images.id', 'desc')->get();
        $imagesData = [];

        foreach ($Image as $key => $image) {
            $data = [
                'id' => $image->id,
                'title' => $image->title,
                'alt_tag' => $image->alt,
                'image' => asset($image->path),
                'date' => $image->created_at->format('d-m-Y'),
                'categoryname' => $image->categoryname,
            ];
            $imagesData[] = $data;
        }
        #endregion
        
        #region footer contact Data
        $contactpage = Option::where('key', 'contact')->first();

// dd($contactpage);
        if($contactpage != null){
            $arrayData = unserialize($contactpage->value);
            $map = $arrayData['map'];
            $zoom = $arrayData['zoom'];
            $contactdata = [
                'mobile' => $arrayData['phone'],
                'cell' => $arrayData['cell'],
                'email' => $arrayData['email'],
                'address' => $arrayData['address'],
                'googleMapsUrl' => "https://maps.google.com/maps?q=" . $map . "&t=&z=" . $zoom . "&ie=UTF8&iwloc=&output=embed"
            ];
        }
        

        #endregion

        #region Header menu Data
        $results = DB::table('main_menus')
            ->select('main_menus.id', 'main_menus.title as label', 'main_menus.link as url', 'submenus.title as submenutitle', 'submenus.link as submenuUrl', 'submenus.id as submenuid', 'main_menus.status')
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

        #endregion

        $result = [
            'SlidesData' => $SlidesData,
            'newslettersdata' => $newsletters,
            'projectdata' => $resource,
            'testmonialdata' => $articles,
            'yotubedata' => $data,
            'allgallerydata' => $imagesData,
            'footercontactdata' => $contactdata ?? '',
            'headermenudata' => $response,
        ];

        // Use empty instead of count to check if the result is empty
        if (!empty($result)) {
            return response()->json([
                "status" => "success",
                "data" => $result
            ]);
        } else {
            return response()->json([
                "status" => "failed",
                "success" => false,
                "message" => "No records found"
            ]);
        }
    }

}
