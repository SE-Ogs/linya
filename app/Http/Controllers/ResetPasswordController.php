<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{
    /**
     * Show the password reset form.
     */
    public function showResetForm(Request $request)
    {
        // Check if user has completed code verification
        $email = session('email');
        $codeVerified = session('success'); // This comes from the code verification success

        if (!$email || !$codeVerified) {
            return redirect('/forgot-password')->withErrors([
                'email' => 'Please complete email verification first.'
            ]);
        }

        return view('partials.reset-password', compact('email'));
    }

    /**
     * Handle password reset submission.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Get email from session
        $email = session('email');

        if (!$email) {
            return redirect('/forgot-password')->withErrors([
                'email' => 'Session expired. Please start the process again.'
            ]);
        }

        // Find the user
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect('/forgot-password')->withErrors([
                'email' => 'No user found with this email address.'
            ]);
        }

        // Update the user's password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Clear the session data
        session()->forget(['email', 'verification_code', 'verification_expires_at']);

        return redirect('/resetsuccess')->with('success', 'Your password has been reset successfully.');
    }
}
