<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
   public function send(Request $request)
    {
        if (auth()->check()) {
            // Logged-in user
            $validated = $request->validate([
                'message' => 'required|string|max:2000',
            ]);

            $user = auth()->user();

            $payload = [
                'name' => $user->name,
                'email' => $user->email,
                'message' => $validated['message'],
            ];
        } else {
            // Guest
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:255',
                'message' => 'required|string|max:2000',
            ]);

            $payload = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'message' => $validated['message'],
            ];
        }

        Mail::to('koh.lim718@gmail.com')
            ->send((new ContactFormMail($payload))
            ->replyTo($payload['email'], $payload['name']));

        return back()->with('success', 'Your message has been sent!');
    }
}
