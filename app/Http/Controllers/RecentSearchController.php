<?php

namespace App\Http\Controllers;

use App\Models\RecentSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecentSearchController extends Controller
{
    // Get recent searches

    public function index(Request $request)
    {
        $query = $request->input('q');

        $recent = RecentSearch::where('user_id', Auth::id())->when($query, fn($q2) => $q2->where('query', 'ILIKE', "%{$query}%"))->orderByDesc('updated_at')->limit(10)->get();

        return response()->json($recent);
    }

    // Store new search query
    public function store(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $query = $request->input('query');

        $recent = RecentSearch::where('user_id', Auth::id())->where('query', $query)->first();

        if ($recent) {
            $recent->touch();
        } else {
            RecentSearch::create([
                'user_id' => Auth::id(),
                'query' => $request->input('query'),
            ]);
        }

        return response()->json(['message' => 'Saved']);
    }

    public function clear(Request $request){
        RecentSearch::where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Cleared']);

    }
}
