<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ResetPasswordController extends Controller
{
    /**
     * Show the password reset form.
     */
    public function showResetForm()
    {
        // Optional debug: check if email exists in session
        if (!session()->has('email')) {
            return redirect('/forgot-password')->withErrors([
                'email' => 'Session expired. Please start the reset process again.',
            ]);
        }

        return view('partials.reset_password');
    }

    /**
     * Handle password reset submission.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = session('email');

        if (!$email) {
            return redirect('/forgot-password')->withErrors([
                'email' => 'Session expired or invalid. Please try again.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect('/reset-password')->withErrors([
                'email' => 'No user found with that email.',
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Clear token and session
        DB::table('password_resets')->where('email', $email)->delete();
        session()->forget('email');

        return redirect('/resetsuccess')->with('success', 'Password reset successful!');
    }
}
