<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Slug;
use App\Models\Ourteam;
use App\Models\Log;
use App\Models\Option;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Auth;
use Throwable;

class OurteamController extends Controller
{
    public function index()
    {
        try {
            $articles = Ourteam::all();
            return view('admin.ourteam.index',compact('articles'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'TeamMember',
                'message' => 'Team Member page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Team Member page could not be loaded.']);
        }
    }

    public function create()
    { 
        try {
            $categories = Category::where('type','=','article-category')->get();
            $languages = Option::where('key','=','language')->orderBy('id','desc')->get();
            return view('admin.ourteam.create',compact('categories','languages'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'TeamMember',
                'message' => 'Team Member create page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Team Member create page could not be loaded.']);
        }
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required|min:3|max:255',
        //     'slug' => 'required|min:3|max:255',
        //     'file_id' => 'required',
        //     'language' => 'required',
        //     'no_index' => 'nullable|in:on',
        //     'no_follow' => 'nullable|in:on',
        //     'media_id' => 'nullable|numeric|min:1',
        //     'category_id' => 'nullable|numeric|min:1',
        // ]);
        // dd($request->all());
        try {
           
            $slug = Slug::create([
                'slug' => slugCheck($request->slug),
                'owner' => 'ourteam',
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'no_index' => $request->no_index=='on' ? 1 : 0,
                'no_follow' => $request->no_follow=='on' ? 1 : 0,
            ]);
            Ourteam::create([
                'slug_id' => $slug->id,
                'user_id' => Auth::id(),
                'media_id' => $request->media_id ?? 1,
                'category_id' => $request->category_id ?? 1,
                'title' => $request->title,
                'email' =>$request->email,
                'phone' => $request->phone,
                'content' => $request->content,
                'language' => $request->language,
            ]);
            return redirect()->route('admin.ourteam.index')->with(['type' => 'success', 'message' =>'Team Meamber Saved.']);

        } catch (Throwable $th) {
            Log::create([
                'model' => 'TeamMember',
                'message' => 'Team Meamber could not be saved.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Team Meamber could not be saved.']);
        }
    }

    public function edit($ourteam)
    { 
        try {
            $categories = Category::all();
            $languages = Option::where('key','=','language')->get();
            $value =Ourteam::where('id',$ourteam)->first();
            return view('admin.ourteam.edit',compact('categories','value','languages'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'TeamMember',
                'message' => 'Team Member edit page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Team Member edit page could not be loaded.']);
        }
    }

    public function update(Request $request, $id)
    {
        
        $article =Ourteam::where('id' ,$id)->first();

        // foreach ($data as $key => $value) {
        //     $article = $value;
        // }
       
        $request->validate([
            'title' => 'required|max:255',
            'language' => 'required',
            'no_index' => 'nullable|in:on',
            'no_follow' => 'nullable|in:on',
            'media_id' => 'nullable|numeric|min:1',
            'category_id' => 'nullable|numeric|min:1',
        ]);
        try {
            $article->getSlug()->update([
                'slug' => slugCheck($request->slug, $article->slug_id),
                'owner' => 'newletter',
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'no_index' => $request->no_index=='on' ? 1 : 0,
                'no_follow' => $request->no_follow=='on' ? 1 : 0,
            ]);
                $article->update([
                    'media_id' => $request->media_id ?? 1,
                    'category_id' => $request->category_id ?? 1,
                    'title' => $request->title,
                    'email' =>$request->email,
                    'phone' => $request->phone,
                    'content' => $request->content,
                    'language' => $request->language,
                ]);
          
            return redirect()->route('admin.ourteam.index')->with(['type' => 'success', 'message' =>'Team Member Has Been Updated.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'TeamMember',
                'message' => 'Team Member could not be updated.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Team Member could not be updated.']);
        }
    }

    public function delete($article)
    {
        try {
            $articledata = Ourteam::where('id',$article)->first();
            $articledata->delete();
            return redirect()->route('admin.ourteam.index')->with(['type' => 'success', 'message' =>'Team Member To Recycle Bin.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'TeamMember',
                'message' => 'Team Member could not be deleted.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Team Member could not be deleted.']);
        }
    }

    public function trash()
    {
        try {
            $articles = Ourteam::onlyTrashed()->get();
            return view('admin.ourteam.trash',compact('articles'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'TeamMember',
                'message' => 'Team Member page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Team Member page could not be loaded.']);
        }
    }

    public function recover($id)
    {
        try {
            Ourteam::withTrashed()->find($id)->restore();
            return redirect()->route('admin.ourteam.trash')->with(['type' => 'success', 'message' =>'Team Member Recovered.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'TeamMember',
                'message' => 'Team Member could not be recovered.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Team Member could not be recovered.']);
        }
    }

    public function destroy($id)
    {
        try {
            $article = Ourteam::withTrashed()->find($id);
            $article->getSlug()->delete();
            $article->forceDelete();
            $filepath = 'newletter/'.$article->file_id;
            unlink($filepath);
            return redirect()->route('admin.ourteam.trash')->with(['type' => 'warning', 'message' =>'Team Member Deleted.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'TeamMember',
                'message' => 'Team Member could not be destroyed.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Team Member could not be destroyed.']);
        }
    }

    public function switch(Request $request)
    {
        try {
            Ourteam::find($request->id)->update([
                'status' => $request->status=="true" ? 1 : 0
            ]);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'TeamMember',
                'message' => 'Team Member could not be switched.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
        }
        return $request->status;
    }
}
