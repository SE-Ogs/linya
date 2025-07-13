<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostManageController extends Controller
{
    public function index()
    {
        return view('admin-panel.post_management');
    }
}
