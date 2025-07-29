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

        return view('admin-panel.admin-dashboard', compact('totalUsers', 'totalPosts', 'currentUser'));
    }
}
