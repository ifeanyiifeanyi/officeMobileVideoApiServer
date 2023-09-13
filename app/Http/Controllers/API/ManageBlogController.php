<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ManageBlogController extends Controller
{
    public function blogContent()
    {
        $blogs = Blog::latest()->get();
        if (!$blogs) {
            return response()->json([
                'error' => $blogs->errors()->messages()
            ], 404);
        } else {
            return response()->json($blogs);
        }
    }

    public function blogComment(Request $request)
    {

        $userId = $request->userId;
        $postId = $request->postId;
        $comment = $request->comment;

        // Check if the user with the given ID exists
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        
        DB::table('comments')->insert(['user_id' => $userId, 'post_id' => $postId, 'comment' => $comment, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);


        return response()->json(['success' => true]);


    }
}
