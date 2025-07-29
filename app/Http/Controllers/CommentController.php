<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\CommentLike;
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

public function like(Comment $comment)
{
    $existing = $comment->likes()->where('user_id', auth()->id())->first();

    if ($existing && $existing->is_like) {
        $existing->delete(); // toggle like off
    } else {
        CommentLike::updateOrCreate(
            ['comment_id' => $comment->id, 'user_id' => auth()->id()],
            ['is_like' => true]
        );
    }

    return back();
}

public function dislike(Comment $comment)
{
    $existing = $comment->likes()->where('user_id', auth()->id())->first();

    if ($existing && !$existing->is_like) {
        $existing->delete(); // toggle dislike off
    } else {
        CommentLike::updateOrCreate(
            ['comment_id' => $comment->id, 'user_id' => auth()->id()],
            ['is_like' => false]
        );
    }

    return back();
}

public function storeAjax(Request $request, Article $article)
{
    $request->validate([
        'content' => 'required|string|max:1000',
        'parent_id' => 'nullable|exists:comments,id'
    ]);

    $comment = \App\Models\Comment::create([
        'user_id' => auth()->id(),
        'article_id' => $article->id,
        'content' => $request->content,
        'parent_id' => $request->parent_id,
    ]);

    $comment->load('user');

    return response()->json([
        'success' => true,
        'comment_html' => view('partials.comment', ['comment' => $comment])->render()
    ]);
}

}
