<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Http\Request;

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
        $this->authorize('delete', $comment);
        $comment->delete();
        return back();
    }

    public function like(Request $request, Comment $comment)
{
    $user = auth()->user();

    // Remove existing dislike from same user
    $comment->likes()
        ->where('user_id', $user->id)
        ->where('is_like', false)
        ->delete();

    // Add or update like
    $comment->likes()->updateOrCreate(
        ['user_id' => $user->id],
        ['is_like' => true]
    );

    return response()->json([
        'success' => true,
        'new_count' => $comment->likes()->where('is_like', true)->count()
    ]);
}

    public function dislike(Request $request, Comment $comment)
{
    $user = auth()->user();

    // Remove existing like from same user
    $comment->likes()
        ->where('user_id', $user->id)
        ->where('is_like', true)
        ->delete();

    // Add or update dislike
    $comment->likes()->updateOrCreate(
        ['user_id' => $user->id],
        ['is_like' => false]
    );

    return response()->json([
        'success' => true,
        'new_count' => $comment->likes()->where('is_like', false)->count()
    ]);
}


    public function storeAjax(Request $request, Article $article)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'article_id' => $article->id,
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        $comment->load('user');

        return response()->json([
    'success' => true,
    'comment_html' => '<div class="animate-fade-in transition-opacity duration-500 rounded-md">' .
        view('partials.comment', [
            'comment' => $comment,
            'article' => $article,
        ])->render() .
    '</div>',
]);

    }
}
