<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /**
     * Show the password reset form.
     */
    public function showResetForm()
    {
        // This can stay the same or be skipped if testing only
        return view('partials.reset_password');
    }

    /**
     * Handle password reset submission.
     * Temporary: Bypass user lookup and go to /resetsuccess if passwords match
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // TEMP: Just go to success screen for UI testing
        return redirect('/resetsuccess')->with('success', 'Password reset successful (demo).');
    }
}
