<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 5);
        $perPage = max(1, min($perPage, 100));
        $search = $request->input('query');

        $query = DB::table('articles')
            ->select('articles.*')
            ->when($search, function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                    ->orWhere('article', 'ILIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc');

        $articles = $query->paginate($perPage)->withQueryString();

        $articles->transform(function ($article) {
            $article->comments_count = 0;

            if (!property_exists($article, 'excerpt')) {
                $article->excerpt = \Illuminate\Support\Str::limit(strip_tags($article->article ?? ''), 100);
            }

            return $article;
        });

        return view('admin-panel.comment-manage-article', compact('articles'));
    }

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

        // Check if user already has a reaction to this comment
        $existingReaction = $comment->likes()->where('user_id', $user->id)->first();

        if ($existingReaction) {
            if ($existingReaction->is_like) {
                // User already liked, remove the like
                $existingReaction->delete();
                $liked = false;
            } else {
                // User disliked, change to like
                $existingReaction->update(['is_like' => true]);
                $liked = true;
            }
        } else {
            // No existing reaction, create a like
            $comment->likes()->create([
                'user_id' => $user->id,
                'is_like' => true
            ]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'disliked' => false,
            'like_count' => $comment->likeCount(),
            'dislike_count' => $comment->dislikeCount()
        ]);
    }

    public function dislike(Request $request, Comment $comment)
    {
        $user = auth()->user();

        // Check if user already has a reaction to this comment
        $existingReaction = $comment->likes()->where('user_id', $user->id)->first();

        if ($existingReaction) {
            if (!$existingReaction->is_like) {
                // User already disliked, remove the dislike
                $existingReaction->delete();
                $disliked = false;
            } else {
                // User liked, change to dislike
                $existingReaction->update(['is_like' => false]);
                $disliked = true;
            }
        } else {
            // No existing reaction, create a dislike
            $comment->likes()->create([
                'user_id' => $user->id,
                'is_like' => false
            ]);
            $disliked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => false,
            'disliked' => $disliked,
            'like_count' => $comment->likeCount(),
            'dislike_count' => $comment->dislikeCount()
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

        $comment->load('user', 'replies.user');

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'user_name' => $comment->user->name,
                'created_at' => $comment->created_at->diffForHumans(),
                'like_count' => $comment->likeCount(),
                'dislike_count' => $comment->dislikeCount(),
                'parent_id' => $comment->parent_id,
                'can_delete' => auth()->user()->can('delete', $comment)
            ]
        ]);
    }

    public function getComments(Article $article, Request $request)
    {
        $sort = $request->get('sort', 'all');

        $query = $article->comments()->whereNull('parent_id')->with(['user', 'replies.user', 'replies.replies']);

        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'most_liked':
                $query->withCount(['likes as like_count' => function ($q) {
                    $q->where('is_like', true);
                }])->orderBy('like_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $comments = $query->get();

        return response()->json([
            'success' => true,
            'html' => view('partials.comments_list', compact('comments', 'article', 'sort'))->render()
        ]);
    }
}
