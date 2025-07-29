<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin-panel.admin-dashboard');
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

