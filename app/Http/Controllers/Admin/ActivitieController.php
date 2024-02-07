<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Option;
use Illuminate\Http\Request;
use Throwable;
use App\Models\Slug;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
class ActivitieController extends Controller
{
   public function index(){
   
    try {
        $articles = Activity::all();
        return view('admin.activitie.index',compact('articles'));
    } catch (Throwable $th) {
        Log::create([
            'model' => 'activitie',
            'message' => 'activitie page could not be loaded.',
            'th_message' => $th->getMessage(),
            'th_file' => $th->getFile(),
            'th_line' => $th->getLine(),
        ]);
        return redirect()->back()->with(['type' => 'error', 'message' =>'activitie page could not be loaded.']);
    }

   }
   public function create()
    {
        try {
            $categories = Category::where('parent' ,'services')->get();
            $languages = Option::where('key','=','language')->orderBy('id','desc')->get();
            return view('admin.activitie.create',compact('categories','languages'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'activitie',
                'message' => 'The activitie create page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The activitie create page could not be loaded.']);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'slug' => 'required|min:3|max:255',
            'language' => 'required',
            'no_index' => 'nullable|in:on',
            'no_follow' => 'nullable|in:on',
            'media_id' => 'nullable|numeric|min:1',
            'category_id' => 'nullable|numeric|min:1',
        ]);
        try {
           
            $slug = Slug::create([
                'slug' => slugCheck($request->slug),
                'owner' => 'article',
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'no_index' => $request->no_index=='on' ? 1 : 0,
                'no_follow' => $request->no_follow=='on' ? 1 : 0,
            ]);

             Activity::create([
                'slug_id' => $slug->id,
                'user_id' => Auth::id(),
                'media_id' => $request->media_id ?? 1,
                'category_id' => $request->category_id ?? 1,
                'title' => $request->title,
                'content' => $request->content,
                'activitydate' =>$request->activitydate,
                'language' => $request->language,
            ]);
            return redirect()->route('admin.activitie.index')->with(['type' => 'success', 'message' =>'activitie Saved.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'activitie',
                'message' => 'The activitie could not be saved.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The activitie could not be saved.']);
        }
    }
    public function edit($id)
    {
        

        try {
            $article = Activity::where('id',$id)->first();
            $categories = Category::where('parent' ,'services')->get();
            $languages = Option::where('key','=','language')->get();
            return view('admin.activitie.edit',compact('categories','article','languages'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'activitie',
                'message' => 'The activitie edit page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The activitie edit page could not be loaded.']);
        }
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'slug' => 'required|min:3|max:255',
            'language' => 'required',
            'no_index' => 'nullable|in:on',
            'no_follow' => 'nullable|in:on',
            'media_id' => 'nullable|numeric|min:1',
            'category' => 'nullable|numeric|min:1',
        ]);
        try {
            $article = Activity::where('id',$id)->first();
            $article->getSlug()->update([
                'slug' => slugCheck($request->slug, $article->slug_id),
                'owner' => 'activitie',
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'no_index' => $request->no_index=='on' ? 1 : 0,
                'no_follow' => $request->no_follow=='on' ? 1 : 0,
            ]);

            $article->update([
                'media_id' => $request->media_id ?? 1,
                'category_id' => $request->category ?? 1,
                'title' => $request->title,
                'content' => $request->content,
                'activitydate' =>$request->activitydate,
                'language' => $request->language,
            ]);
            return redirect()->route('admin.activitie.index')->with(['type' => 'success', 'message' =>'The activitie Has Been Updated.']);
            // return redirect()->route('admin.article.edit',$article->id)->with(['type' => 'success', 'message' =>'The Post Has Been Updated.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'activitie',
                'message' => 'The activitie could not be updated.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The activitie could not be updated.']);
        }
    }
    public function delete($id)
    {
        try {
            $articledata = Activity::where('id',$id)->first();
            $articledata->delete();
            return redirect()->route('admin.activitie.index')->with(['type' => 'success', 'message' =>'activitie Moved To Recycle Bin.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'activitie',
                'message' => 'The activitie could not be deleted.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The activitie could not be deleted.']);
        }
    }

    public function show()
    {
       
        try {
            $articles = Activity::onlyTrashed()->get();
            return view('admin.activitie.trash',compact('articles'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'activitie',
                'message' => 'activitie trash page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'activitie trash page could not be loaded.']);
        }
    }

    public function trashed(){
        dd('dsgj');
    }
    public function recover($id)
    {
        try {
            Activity::withTrashed()->find($id)->restore();
            return redirect()->route('admin.activitie.trashed')->with(['type' => 'success', 'message' =>'Post Recovered.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'activitie',
                'message' => 'The activitie could not be recovered.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The activitie could not be recovered.']);
        }
    }
    public function destroy($id)
    {
        try {
            $article = Activity::withTrashed()->find($id);
            $article->getSlug()->delete();
            $article->forceDelete();
            return redirect()->route('admin.activitie.trashed')->with(['type' => 'warning', 'message' =>'activitie Deleted.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'activitie',
                'message' => 'The activitie could not be destroyed.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The activitie could not be destroyed.']);
        }
    }

    public function switchdata(Request $request)
    {
        try {
            Activity::find($request->id)->update([
                'status' => $request->status=="true" ? 1 : 0
            ]);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'activitie',
                'message' => 'The activitie could not be switched.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
        }
        return $request->status;
    }

}
