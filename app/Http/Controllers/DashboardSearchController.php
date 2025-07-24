<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class DashboardSearchController extends Controller{
    public function search(Request $request){
        $query = $request->input('q');

        $articles = Article::with('tags')
            ->where('title', 'LIKE', "%{query}%")
            ->orWhere('summary', 'LIKE', "%{query}%")
            ->orWhereHas('tags', function ($tagQuery) use ($query){
                $tagQuery->where('name', 'LIKE', "%{query}%");
            })
            ->where('status', 'published')
            ->limit(10)
            ->get();

        return response()->json($articles);

    }
}
