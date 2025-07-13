<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentManageController extends Controller
{
    public function index(Request $request)
    {
        return view('admin-panel.comment-manage-article');
    }

    public function show($slug)
    {
        return view('admin-panel.comment-manage-deniella', compact('slug')); 
        // ERROR: View [admin-panel.comment-manage-article-show] not found. This is normal for now, I think. 
        // Daniella, please route this to your "Comment Management Page.
    }
}
