<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index(){
        $comments = Comment::latest()->get();
        return view('admin.Comments.index', ['comments' => $comments]);
    }
}
