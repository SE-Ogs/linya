<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CommentController extends Controller
{
public function store(Request $request, Article $article)
{
    $request->validate([
        'content' => 'required|string|max:1000',
        'parent_id' => 'nullable|exists:comments,id'
    ]);

    Comment::create([
        'user_id' => auth()->id(),
        'article_id' => $article->id,
        'content' => $request->content,
        'parent_id' => $request->parent_id,
    ]);

    return back();
}

public function destroy(Comment $comment)
{
    $this->authorize('delete', $comment); // optional: ensure only owner/admin can delete
    $comment->delete();
    return back();
}
}
