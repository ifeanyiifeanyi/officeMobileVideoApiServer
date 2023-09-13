<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Videos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class VideoManagerController extends Controller
{

    // select all videos
    public function allVideos()
    {
        $videos = DB::table('videos')
            ->join('categories', 'videos.category_id', '=', 'categories.id')
            ->join('genres', 'videos.genres_id', '=', 'genres.id')
            ->join('ratings', 'videos.rating_id', '=', 'ratings.id')
            ->join('parent_controls', 'videos.parent_control_id', '=', 'parent_controls.id')
            ->select('videos.id', 'videos.title', 'videos.thumbnail', 'genres.name AS genName', 'categories.name AS catName', 'ratings.name AS rateName', 'parent_controls.name as PcName')
            ->orderBy('id', 'desc')
            ->where("videos.status", 1)
            // ->where("categories.name", "Communion")
            ->inRandomOrder()->get();

        if (!$videos) {
            return response()->json([
                'error' => $videos->errors()->messages()
            ], 404);
        } else {
            return response()->json($videos);
        }
    }

    // select videos based on rating
    public function allVideosByRating()
    {
        $videos = DB::table('videos')
            ->join('categories', 'videos.category_id', '=', 'categories.id')
            ->join('genres', 'videos.genres_id', '=', 'genres.id')
            ->join('ratings', 'videos.rating_id', '=', 'ratings.id')
            ->join('parent_controls', 'videos.parent_control_id', '=', 'parent_controls.id')
            ->select('videos.id', 'videos.title', 'videos.thumbnail', 'genres.name AS genName', 'categories.name AS catName', 'ratings.name AS rateName', 'parent_controls.name as PcName')
            ->orderBy('id', 'desc')
            ->where('videos.status', 1)
            ->where('categories.name', 'baptism') // add a condition to filter by category name
            ->inRandomOrder()
            ->get();



        return response()->json($videos);
    }
    // NOTE: This function, come back and remove the static category and status values
    public function allVideosByCategory()
    {
        $videos = DB::table('videos')
            ->join('categories', 'videos.category_id', '=', 'categories.id')
            ->join('genres', 'videos.genres_id', '=', 'genres.id')
            ->join('ratings', 'videos.rating_id', '=', 'ratings.id')
            ->join('parent_controls', 'videos.parent_control_id', '=', 'parent_controls.id')
            ->select('videos.id', 'videos.title', 'videos.thumbnail', 'genres.name AS genName', 'categories.name AS catName', 'ratings.name AS rateName', 'parent_controls.name as PcName')
            ->orderBy('id', 'desc')
            ->where("videos.status", 1)
            ->where("categories.name", "Catechism")
            ->inRandomOrder()->get();
        if (!$videos) {
            return response()->json([
                'error' => $videos->errors()->messages()
            ], 404);
        } else {
            return response()->json($videos);
        }
    }

     // fetch a video with id, category, genres, rating and parent_control
    // used this function to set the number of view for each video
    public function playVideo($id)
    {
        $video = DB::table('videos')
            ->join('categories', 'videos.category_id', '=', 'categories.id')
            ->join('genres', 'videos.genres_id', '=', 'genres.id')
            ->join('ratings', 'videos.rating_id', '=', 'ratings.id')
            ->join('parent_controls', 'videos.parent_control_id', '=', 'parent_controls.id')
            ->select('videos.*', 'genres.name AS genName', 'categories.name AS catName', 'ratings.name AS rateName', 'parent_controls.name as PcName')
            ->orderBy('id', 'desc')
            ->where("videos.id", $id)->get();

        if ($video) {
            // if video was found increment the number of views
            DB::table('videos')->where('id', $id)->increment('views');

            return response()->json($video);
        } else {
            return response()->json([
                'error' => $video->errors()->messages(),
            ], 404);
        }
    }
    //  intended for displaying list of thumbnails as carousel(not used yet)
    public function BannerThumbnail()
    {
        $videoThumbnail = Videos::select("id", "title", "thumbnail")
            ->latest()
            ->get();
        return response()->json($videoThumbnail);
    }
    public function VideoLikes(Request $request)
    {
        $videoId = $request->videoId;
        $userId = $request->userId;

        $video = Videos::find($videoId);
        $user = User::find($userId);

        $likes = DB::table('likes')
            ->where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->first();

        if ($likes) {
            return response()->json([
                'message' => 'You have already liked this video.'
            ], 400);
        }

        DB::table('likes')->insert(['user_id' => $user->id, 'video_id' => $video->id]);

        $video->likes += 1;
        $video->save();

        return response()->json([
            'likes' => $video->likes,
        ]);
    }
    public function VideoDislikes(Request $request)
    {
        // dislike not complete
        $videoId = $request->videoId;
        $userId = $request->userId;

        $video = Videos::find($videoId);
        $user = User::find($userId);

        $like = DB::table('likes')
            ->where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->first();

        if ($like) {
            DB::table('likes')
                ->where('user_id', $user->id)
                ->where('video_id', $video->id)
                ->delete();
            $video->likes -= 1;
            $video->save();
            return response()->json([
                'likes' => $video->likes,
            ]);
        } else {
            return response()->json([
                'message' => 'User has not liked this video.',
            ]);
        }
    }


}