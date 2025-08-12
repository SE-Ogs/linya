<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HomeSearchController extends Controller
{
     public function search(Request $request) {
        $query = strtolower($request->input('q'));

        $articles = Article::with('tags')
        ->where('status', 'Published')
        ->where(function ($q2) use ($query) {
            $q2->where(DB::raw('LOWER(title)'), 'LIKE', "%{$query}%")
                ->orWhere(DB::raw('LOWER(summary)'), 'LIKE', "%{$query}%")
                ->orWhereHas('tags', function ($tagQuery) use ($query) {
                    $tagQuery->where(DB::raw('LOWER(name)'), 'LIKE', "%{$query}%");
                });
        })
        ->limit(10)
        ->get();

        return response()->json($articles);
    }
}
