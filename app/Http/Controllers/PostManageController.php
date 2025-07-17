<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Tag;

class PostManageController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('tags');

        // Apply filters
        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tags')) {
            $tagIds = $request->tags;
            $query->whereHas('tags', function($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            });
        }

        $articles = $query->get();
        $tags = Tag::all();

        return view('admin-panel.post_management', compact('articles', 'tags'));
    }

    public function destroy($id)
    {
        Article::destroy($id);
        return redirect()->route('admin.posts')->with('success', 'Article deleted successfully.');
    }
}
