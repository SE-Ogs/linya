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
        return redirect('/login');
    }
}