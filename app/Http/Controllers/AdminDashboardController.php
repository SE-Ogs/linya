<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalPosts = Article::count();
        $currentUser = Auth::user();

        // Grabs the top 3 most viewed articles and its description
        $trendingPosts = Article::orderBy('views', 'desc')->limit(3)->get();

        return view('admin-panel.admin-dashboard', compact(
            'totalUsers',
            'totalPosts',
            'currentUser',
            'trendingPosts'
        ));
    }
}
