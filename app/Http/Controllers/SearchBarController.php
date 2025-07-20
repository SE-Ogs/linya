<?php
/**
 * This controller is meant for the Comment Management Article & User Management Search Bar Function
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchBarController extends Controller
{
    /**
     * Handle search and filter logic
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $type = $request->input('type'); // "users" or "comments"
        $query = $request->input('query');
        $tags = $request->input('tags', []);
        $year = $request->input('year');

        if ($type === 'users') {
            $results = DB::table('users');

            if (!empty($query)) {
                $results->where(function($q) use ($query) {
                    $q->where('name', 'ILIKE', "%{$query}%")
                    ->orWhere('email', 'ILIKE', "%{$query}%");
                });
            }

            if (!empty($year)) {
                $results->whereYear('created_at', $year);
            }

            $paginatedResults = $results
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->withQueryString();

            return view('admin-panel.user-manage', [
                'users' => $paginatedResults,
                'type' => 'users',
            ]);
        }

        if ($type === 'comments') {
            $queryBuilder = DB::table('articles')
                ->select('articles.*')
                ->when($query, function ($q) use ($query) {
                    $q->where('articles.title', 'ILIKE', "%{$query}%")
                    ->orWhere('articles.article', 'ILIKE', "%{$query}%");
                })
                ->orderBy('articles.created_at', 'desc');

            $paginatedResults = $queryBuilder->paginate(10)->withQueryString();

            // Add default comments_count = 0 to each article
            $paginatedResults->transform(function ($article) {
                $article->comments_count = 0;

                // Make sure excerpt exists to avoid undefined error
                if (!property_exists($article, 'excerpt')) {
                    $article->excerpt = \Illuminate\Support\Str::limit(strip_tags($article->article ?? ''), 100);
                }

                return $article;
            });

            return view('admin-panel.comment-manage-article', [
                'articles' => $paginatedResults
            ]);
        }

        // fallback if type is not set or invalid
        abort(404);
    }
}