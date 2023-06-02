<?php

namespace App\Http\Controllers\API;

use App\Models\CommentReply;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    
    public function getComments(Request $request)
    {
        $comments = Comment::where('blog_id', $request->postId)->where('status', 1)->get();
        $user = User::whereIn('id', $comments->pluck('user_id'))->get();
        $replies = CommentReply::whereIn('comment_id', $comments->pluck('id'))->get();

        if($comments->count() > 0 && $replies->count() > 0){
            //post has both comments and replies
            return response()->json([
                'comments' => $comments,
                'user'     => $user,
                'replies'  => $replies
            ]);
        }elseif ($comments->count() > 0) {
            # post has only comments 
            return response()->json([
                'comments' => $comments,
                'user'     => $user,
            ]);

        }else{
            return response()->json([
                'message' => 'Post has no comments or replies.',
                
            ]);
        }


    }
}