<?php
/**
 * This controller is meant for the Comment Management Article Search Bar Function
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
        $query = $request->input('query');
        $tags = $request->input('tags', []);
        $year = $request->input('year');

        $results = DB::table('items'); // Replace 'items' with your actual table

        // Apply search filter
        if (!empty($query)) {
            $results->where('name', 'ILIKE', "%{$query}%");
        }

        // Apply tag filter
        if (!empty($tags)) {
            $results->whereIn('tag', $tags); // Assumes 'tag' is a string column
        }

        // Apply year filter
        if (!empty($year)) {
            $results->whereYear('created_at', $year); // Assumes a 'created_at' timestamp column
        }

        // Paginate results (5 per page), preserving filters in query string
        $paginatedResults = $results
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->withQueryString();

        return view('search', [
            'results' => $paginatedResults
        ]);
    }
}