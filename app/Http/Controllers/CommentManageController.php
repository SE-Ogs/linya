<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\DB;


class CommentManageController extends Controller
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

}
