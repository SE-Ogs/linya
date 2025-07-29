<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalPosts = Article::count();

        return view('admin-panel.admin-dashboard', compact('totalUsers', 'totalPosts'));
    }
}

// <!-- namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\User;

// class AdminDashboardController extends Controller
// {
//     public function index()
//     {
//         // Get active user count from the database
//         $userCount = User::where('status', 'Active')->count();

//         // Pass the value to the view
//         return view('dashboard', compact('userCount'));
//     }
// } -->

