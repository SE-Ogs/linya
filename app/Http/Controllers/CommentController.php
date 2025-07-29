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
        $comment->likes()->updateOrCreate(
            ['user_id' => $user->id],
            ['is_like' => true]
        );

        $count = $comment->likes()->where('is_like', true)->count();

        return response()->json(['success' => true, 'new_count' => $count]);
    }

    public function dislike(Request $request, Comment $comment)
    {
        $user = auth()->user();
        $comment->likes()->updateOrCreate(
            ['user_id' => $user->id],
            ['is_like' => false]
        );

        $count = $comment->likes()->where('is_like', false)->count();

        return response()->json(['success' => true, 'new_count' => $count]);
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
    'comment_html' => view('partials.comment', [
        'comment' => $comment,
        'article' => $article, // ✅ Fix: pass the missing variable
    ])->render()
]);

    }
}
