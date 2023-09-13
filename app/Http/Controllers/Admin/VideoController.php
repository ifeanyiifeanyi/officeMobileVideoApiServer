<?php

namespace App\Http\Controllers\Admin;

use App\Models\Genre;
use App\Models\rating;
use App\Models\Videos;
use App\Models\categories;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ParentControl;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function index()
    {
        // $videos = Videos::latest()->simplePaginate(2);
        $videos = DB::table('videos')
                    ->join('categories', 'videos.category_id', '=', 'categories.id')
                    ->join('genres', 'videos.genres_id', '=', 'genres.id')
                    ->join('ratings', 'videos.rating_id', '=', 'ratings.id')
                    ->join('parent_controls', 'videos.parent_control_id', '=', 'parent_controls.id')
                    ->select('videos.*','genres.name AS genName', 'categories.name AS catName', 'ratings.name AS rateName', 'parent_controls.name as PcName')
                    ->orderBy('id', 'desc')
                    ->simplePaginate(5);
                    
                    ;
       return view('admin.videosFolders.index', compact('videos'));
    }

    public function create(){
        $categories = categories::all();
        $genre = Genre::all();
        $ratings = rating::all();
        $parentControls = ParentControl::all();
        return view("admin.videosFolders.create", compact('categories','genre', 'ratings', 'parentControls'));
    }

    public function store(Request $request)
    {
        // return $_FILES['video']['error'];

        $validator = Validator::make($request->all(), [
            'title'                 => 'required|min:3|unique:videos|string',
            'category_id'           => 'required',
            'length'                => 'required',
            'genre_id'              => 'required',
            'rating_id'             => 'required',
            'parent_control_id'     => 'required',
            'short_description'     => 'required',
            'long_description'      => 'required',
            'video'                 => 'required|file|mimes:mp4,ogx,oga,ogv,ogg,webm|max:100000',
            'thumbnail'             => 'required|image|mimes:jpeg, png, jpg, webp|max:9048'
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->messages()
            ]);
        }

        $vidoeUploads = new Videos();


        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $ext = $video->getClientOriginalExtension();
            $videofile = time() .".".$ext;
            $video->move('uploads/videos/', $videofile);
            $vidoeUploads->video = 'uploads/videos/'.$videofile;
        }

        if($request->hasFile('thumbnail')){
            $thumb = $request->file('thumbnail');
            $extension = $thumb->getClientOriginalExtension();
            $thumbfile = time(). ".".$extension;
            $thumb->move('uploads/thumbnails/', $thumbfile);
            $vidoeUploads->thumbnail = 'uploads/thumbnails/'.$thumbfile;
        }

        $vidoeUploads->title    = $request->title;
        $vidoeUploads->slug     = Str::slug($request->title);
        $vidoeUploads->category_id = $request->category_id;
        $vidoeUploads->length      = $request->length;
        $vidoeUploads->genres_id          = $request->genre_id;
        $vidoeUploads->rating_id         = $request->rating_id;
        $vidoeUploads->parent_control_id = $request->parent_control_id;
        $vidoeUploads->short_description = $request->short_description;
        $vidoeUploads->long_description  = $request->long_description;
        $vidoeUploads->status            = $request->status ? 1 : 0;
        $vidoeUploads->save();
        //return redirect()->route("videos")->with("status", "Video Created");
       // return a response for js (success)
        return response()->json([
            'success' => true
        ]);

    }
    public function show($id)
    {
        
        $categories = categories::all();
        $genre = Genre::all();
        $ratings = rating::all();
        $parentControls = ParentControl::all();
        $video = Videos::find($id);
        return view('admin.videosFolders.show', compact('video', 'categories', 'genre', 'ratings', 'parentControls'));
    }
    public function edit($id)
    {
        $categories = categories::all();
        $genre = Genre::all();
        $ratings = rating::all();
        $parentControls = ParentControl::all();
        $video = Videos::find($id);
        return view('admin.videosFolders.edit', compact('video', 'categories', 'genre', 'ratings', 'parentControls'));
    }


    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title'                 => 'required|min:3|string',
            'category_id'           => 'required',
            'length'                => 'required',
            'genre_id'              => 'required',
            'rating_id'             => 'required',
            'parent_control_id'     => 'required',
            'short_description'     => 'required|string',
            'long_description'      => 'string',
            'video'                 => 'file|mimes:mp4,ogx,oga,ogv,ogg,webm|max:30000',
            'thumbnail'             => 'image|mimes:jpeg, png, jpg|max:2048'
        ]);
        // find the video to be updated
        $video = Videos::find($id);
  
        // return a json error message 
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        if ($request->hasFile('thumbnail')) {
            // if new thumbnail was added delete the old one
            $old_thumbnail = $video->thumbnail;
            if(!empty($old_thumbnail) && file_exists(public_path($old_thumbnail))){
                unlink(public_path($old_thumbnail));
            }

            $thumb = $request->file('thumbnail');
            $extension = $thumb->getClientOriginalExtension();
            $thumbfile = time(). ".".$extension;
            $thumb->move('uploads/thumbnails/', $thumbfile);
            $video->thumbnail = 'uploads/thumbnails/'.$thumbfile;
    
        }
        if ($request->hasFile('video')) {
            $old_video = $video->video;

            if(!empty($old_video) && file_exists(public_path($old_video))){
                unlink(public_path($old_video));
            }
            $video = $request->file('video');
            $ext = $video->getClientOriginalExtension();
            $videofile = time() .".".$ext;
            $video->move('uploads/videos/', $videofile);
            $video->video = 'uploads/videos/'.$videofile;
        }
        $video->title    = $request->title;
        $video->slug     = Str::slug($request->title);
        $video->category_id = $request->category_id;
        $video->length      = $request->length;
        $video->genres_id          = $request->genre_id;
        $video->rating_id         = $request->rating_id;
        $video->parent_control_id = $request->parent_control_id;
        $video->short_description = $request->short_description;
        $video->long_description  = $request->long_description;
        $video->status            = $request->status ? 1 : 0;
        $video->update();

       // return a response for js (success)
        return response()->json([
            'success' => true
        ]);

    }

    public function destroy($id)
    {
        $video = Videos::findOrFail($id);
        

        if(!empty($video->thumbnail) && file_exists(public_path($video->thumbnail))){
            unlink(public_path($video->thumbnail));
        }
        if(!empty($video->video) && file_exists(public_path($video->video))){
            unlink(public_path($video->video));
        }
        
        
        $video->delete();
        return redirect()->route("videos")->with("status", "Video Deleted");
    }

    public function draft($id)
    {
        $send_to_draft = Videos::find($id);
        $send_to_draft->status = 0;
        $send_to_draft->update();
        return redirect()->route("videos")->with("status", "Video status changed to draft");
    }
    public function activate($id)
    {
        $send_to_draft = Videos::find($id);
        $send_to_draft->status = 1;
        $send_to_draft->update();
        return redirect()->route("videos")->with("status", "Video status changed to Activate");
    }
}


#to restore
// $flight->restore();

// #to query trashed models
// Flight::withTrashed()
//         ->where('airline_id', 1)
//         ->restore();
// $flights = Flight::onlyTrashed()
//                 ->where('airline_id', 1)
//                 ->get();
