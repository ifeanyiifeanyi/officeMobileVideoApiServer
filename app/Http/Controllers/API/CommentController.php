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
        $comments = Comment::with('user')
            ->where('blog_id', $request->postId)
            ->where('comments.status', 1)
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->select('comments.*','users.username')
            ->get();

        $replies = CommentReply::whereIn('comment_id', $comments->pluck('id'))->get();

        if ($comments->count() > 0 && $replies->count() > 0) {
            // Post has both comments and replies
            return response()->json([
                'comments' => $comments,
                'replies' => $replies
            ]);
        } elseif ($comments->count() > 0) {
            // Post has only comments
            return response()->json([
                'comments' => $comments,
            ]);
        } else {
            return response()->json([
                'message' => 'Post has no comments or replies.',
            ]);
        }
    }

}