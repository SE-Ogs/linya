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

     public function signup(Request $request)
    {
        // $request->session()->regenerate();

        $credentials = $request->validate([
            'username_signup' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Store signup data in session temporarily
        $request->session()->put('signup_data', [
            'username' => $credentials['username_signup'],
            'email' => $credentials['email'],
            'password' => bcrypt($credentials['password']),
        ]);

        return redirect('/set-display-name');
    }

    public function showDisplayName()
    {
        // Check if signup data exists in session
        if (!session()->has('signup_data')) {
            return redirect('/signup');
        }

        return view('layout.login-and-signup', ['show' => 'set-display-name']);
    }

    public function storeDisplayName(Request $request)
    {
        // Check if signup data exists in session
        if (!session()->has('signup_data')) {
            return redirect('/signup');
        }

        $request->validate([
            'display_name' => ['nullable', 'string', 'max:255'],
        ]);

        // Get signup data from session
        $signupData = session('signup_data');

        // Create the user
        $user = \App\Models\User::create([
            'name' => $request->display_name ?: $signupData['username'], 
            'username' => $signupData['username'],
            'email' => $signupData['email'],
            'password' => $signupData['password'],
        ]);

        // Clear signup data from session
        session()->forget('signup_data');

        // Log the user in
        Auth::login($user);

        // Redirect to dashboard
        return redirect('/dashboard')->with('success', 'Account created successfully!');
    }

    public function clearSignupData(Request $request)
    {
        // Clear signup data from session
        $request->session()->forget('signup_data');
        return redirect('/signup')->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
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
        return redirect('/login');
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
            'author_id' => Auth() -> user(),
            'created_at' => now(), // Assuming the user is logged in
        ];
        
        return view('article-management.preview', compact('articleData'));
    }
}