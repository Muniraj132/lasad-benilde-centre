<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Slug;
use App\Models\Article;
use App\Models\Log;
use App\Models\Option;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Auth;
use Throwable;

class NewsletterController extends Controller
{
    public function index()
    {
        try {
            $articles = Newsletter::all();
            return view('admin.newsletter.index',compact('articles'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'News & Events',
                'message' => 'News & Events page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'News & Events page could not be loaded.']);
        }
    }

    public function create()
    { 
        try {
            $categories = Category::where('type','=','article-category')->get();
            $languages = Option::where('key','=','language')->orderBy('id','desc')->get();
            return view('admin.newsletter.create',compact('categories','languages'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'News & Events',
                'message' => 'The News & Events create page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The News & Events create page could not be loaded.']);
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
        try {
            $slug = Slug::create([
                'slug' => slugCheck($request->slug),
                'owner' => 'newsletter',
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'no_index' => $request->no_index=='on' ? 1 : 0,
                'no_follow' => $request->no_follow=='on' ? 1 : 0,
            ]);

            $file = $request->file('file_id');
            if ($file != null) {
                
                $filename = $file->getClientOriginalName();
                 Newsletter::create([
                    'slug_id' => $slug->id,
                     'user_id' => Auth::id(),
                    'media_id' => $request->media_id ?? 1,
                    'file_id' => $filename,
                    'category_id' => $request->category ?? 1,
                    'title' => $request->title,
                    'content' => $request->content,
                    'language' => $request->language,
                    'eventdate' => $request->eventdate,
                ]);
                $destinationPath ="newletter";
                $file->move($destinationPath, $filename);
            }else{
                 Newsletter::create([
                    'slug_id' => $slug->id,
                     'user_id' => Auth::id(),
                    'media_id' => $request->media_id ?? 1,
                    'category_id' => $request->category ?? 1,
                    'title' => $request->title,
                    'content' => $request->content,
                    'language' => $request->language,
                    'eventdate' => $request->eventdate,
                ]);
            }
            return redirect()->route('admin.newletter.index')->with(['type' => 'success', 'message' =>'News & Events Saved.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'News & Events',
                'message' => 'The News & Events could not be saved.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The News & Events could not be saved.']);
        }
    }

    public function edit($newsletter)
    { 
        try {
            $categories = Category::all();
            $languages = Option::where('key','=','language')->get();
            $value =Newsletter::where('id',$newsletter)->first();
            return view('admin.newsletter.edit',compact('categories','value','languages'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'News & Events',
                'message' => 'The News & Events edit page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The News & Events edit page could not be loaded.']);
        }
    }

    public function update(Request $request, $id)
    {
        
        $article =Newsletter::where('id',$id)->first();
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
            $article->getSlug()->update([
                'slug' => slugCheck($request->slug, $article->slug_id),
                'owner' => 'newletter',
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'no_index' => $request->no_index=='on' ? 1 : 0,
                'no_follow' => $request->no_follow=='on' ? 1 : 0,
            ]);
            
            $file = $request->file('file_id');
            if ($file != null) {
                
                $filename = $file->getClientOriginalName();
                $article->update([
                    'media_id' => $request->media_id ?? 1,
                    'file_id' => $filename,
                    'category_id' => $request->category ?? 1,
                    'title' => $request->title,
                    'content' => $request->content,
                    'language' => $request->language,
                    'eventdate' => $request->eventdate,
                ]);
                $destinationPath ="newletter";
                $file->move($destinationPath, $filename);
            }else{
                $article->update([
                    'media_id' => $request->media_id ?? 1,
                    'category_id' => $request->category ?? 1,
                    'title' => $request->title,
                    'content' => $request->content,
                    'language' => $request->language,
                    'eventdate' => $request->eventdate,
                ]);
            }
            return redirect()->route('admin.newletter.index')->with(['type' => 'success', 'message' =>'The News & Events Has Been Updated.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'News & Events',
                'message' => 'The News & Events could not be updated.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The News & Events could not be updated.']);
        }
    }

    public function delete($article)
    {
        try {
            $articledata = Newsletter::where('id',$article)->first();
            $articledata->delete();
            return redirect()->route('admin.newletter.index')->with(['type' => 'success', 'message' =>'News & Events Moved To Recycle Bin.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'News & Events',
                'message' => 'The News & Events could not be deleted.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The News & Events could not be deleted.']);
        }
    }

    public function trash()
    {
        try {
            $articles = Newsletter::onlyTrashed()->get();
            return view('admin.newsletter.trash',compact('articles'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'News & Events',
                'message' => 'News & Events trash page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'News & Events trash page could not be loaded.']);
        }
    }

    public function recover($id)
    {
        try {
            Newsletter::withTrashed()->find($id)->restore();
            return redirect()->route('admin.newsletter.trash')->with(['type' => 'success', 'message' =>'Post Recovered.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'News & Events',
                'message' => 'The News & Events could not be recovered.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The News & Events could not be recovered.']);
        }
    }

    public function destroy($id)
    {
        try {
            $article = Newsletter::withTrashed()->find($id);
            $article->getSlug()->delete();
            $article->forceDelete();
            $filepath = 'newletter/'.$article->file_id;
            unlink($filepath);
            return redirect()->route('admin.newsletter.trash')->with(['type' => 'warning', 'message' =>'Post Deleted.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'News & Events',
                'message' => 'The News & Events could not be destroyed.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The News & Events could not be destroyed.']);
        }
    }

    public function switch(Request $request)
    {
        try {
            Newsletter::find($request->id)->update([
                'status' => $request->status=="true" ? 1 : 0
            ]);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'News & Events',
                'message' => 'The News & Events could not be switched.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
        }
        return $request->status;
    }
}
