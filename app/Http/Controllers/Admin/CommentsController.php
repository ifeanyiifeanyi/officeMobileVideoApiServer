<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReply;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index(){
        $comments = Comment::latest()->get();
        return view('admin.Comments.index', ['comments' => $comments]);
    }

    public function show($id){
        $comment = Comment::findOrFail($id);
        $replies = CommentReply::where('comment_id','=' ,$comment->id)->get();
        return view('admin.Comments.show', ['comment' => $comment, 'replies' => $replies]);
    }

    public function updateStatus(Request $request){
        $request->validate([
            'status'     => 'required',
        ]);
        $status = (int) $request->status;
        $comment_id = $request->comment_id;

        $update_status = Comment::findOrFail($comment_id);
        $update_status->status = $status;
        $update_status->save();

        return redirect()->route('comments')->with('status', 'Comment Status Updated!');
    }

    public function submitCommentReply(Request $request){
        $request->validate([
            'reply'     => 'required|string|max:255',
        ]);

        $comment_id = $request->comment_id;
        $reply = $request->reply;
        $user_id = Auth()->user()->id;

        $reply_comment = new CommentReply();
        $reply_comment->reply  = $reply;
        $reply_comment->comment_id = $comment_id;
        $reply_comment->user_id = $user_id;
        $reply_comment->save();
        return redirect()->route('comments')->with('status', 'Comment Reply Saved!');
    }

}
