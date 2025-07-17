<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    public function showLogin()
    {
        return view('layout.login-and-signup', ['show' => 'login']);
    }

    public function showSignup()
    {
        return view('layout.login-and-signup', ['show' => 'signup']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); // TODO: make separate route for admins when logging innn
        }

        return back()->withErrors([
            'username' => 'Incorrect credentials. Don\'t hesitate to click forgot password! :)',

        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/dashboard');
    }

    public function preview(Request $request)
    {
        // Handle the preview logic here
        // For example, you might want to return a view with the article data
        $articleData = $request->all(); // Get all input data for preview

        $article = (object) [
            'title' => $articleData['title'] ?? '',
            'content' => $articleData['content'] ?? '',
            'tags' => $articleData['tags'] ?? [],
            'status' => 'preview', // Set status to preview
            'author_id' => Auth::user(),
            'created_at' => now(), // Assuming the user is logged in
        ];

        return view('article-management.preview', compact('articleData'));
    }
}
