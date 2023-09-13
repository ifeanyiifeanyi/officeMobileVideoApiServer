<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->get();
        return view('admin.blog.index', ['blogs' => $blogs]);
    }


    public function show(){
        return view('admin.blog.create');
    }

    public function store(Request $request){
        $request->validate([
            'title'     => 'required|string|unique:blogs|min:5|max:199',
            'author'     => 'required|string|unique:blogs|min:5|max:199',
            'description' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg, png, jpg|max:2048'
        ]);
        $blog = new Blog();

        if($request->hasFile('thumbnail')){
            $thumb = $request->file('thumbnail');
            $extension = $thumb->getClientOriginalExtension();
            $thumbfile = time(). ".".$extension;
            $thumb->move('uploads/blogs/', $thumbfile);
            $blog->thumbnail = 'uploads/blogs/'.$thumbfile;
        }

        $slug = Str::slug($request->title);
        
        $blog->slug = $slug;
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->author = $request->author;
        $blog->author = $request->author;
        $blog->save();
        return redirect()->route('blog')->with('status', 'New Blog Created');
    }

    public function edit($slug){
        $blog = Blog::where('slug', $slug)->firstOrFail();
        return view('admin.blog.edit', ['blog' => $blog]);
    }
    public function update(Request $request,$slug)
    {
        $request->validate([
            'title'     => 'required|string|min:5|max:199',
            'author'     => 'required|string|min:5|max:199',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg, png, jpg|max:2048'
        ]);
        $blog = Blog::where('slug', $slug)->firstOrFail(); 

        if ($request->hasFile('thumbnail')) {
            $old_thumbnail = $blog->thumbnail;

            if(!empty($old_thumbnail) && file_exists(public_path($old_thumbnail))){
                unlink(public_path($old_thumbnail));
            }
            
            $thumb = $request->file('thumbnail');
            $extension = $thumb->getClientOriginalExtension();
            $thumbfile = time(). ".".$extension;
            $thumb->move('uploads/blogs/', $thumbfile);
            $blog->thumbnail = 'uploads/blogs/'.$thumbfile;
    
        }

        $slug = Str::slug($request->title);
        // $blog = new Blog();
        $blog->slug = $slug;
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->author = $request->author;
        $blog->save();
        return redirect()->route('blog')->with('status', 'Blog Updated');

    }
    public function destroy($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
      
        if(!empty($blog->thumbnail) && file_exists(public_path($blog->thumbnail))){
            unlink(public_path($blog->thumbnail));
        }
        $blog->delete();
       return redirect()->route('blog')->with('status', 'Blog Deleted!');

        
    }
}
