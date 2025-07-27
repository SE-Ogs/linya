<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'users'); // 'users' or 'admins'

        // Sanitize per_page: allow only between 1 and 100
        $perPage = (int) $request->input('per_page', 5);
        $perPage = max(1, min($perPage, 100)); // clamp to [1, 100]

        $query = $request->input('query');

        // Role filtering
        $usersQuery = User::query()
            ->where('role', $type === 'admins' ? 'admin' : 'user');

        // Optional search
        if ($query) {
            $usersQuery->where(function ($q) use ($query) {
                $q->where('name', 'ILIKE', "%{$query}%")
                    ->orWhere('email', 'ILIKE', "%{$query}%");
            });
        }

        // Paginate and preserve query params
        $users = $usersQuery->paginate($perPage)->appends($request->all());

        return view('admin-panel.user-manage', compact('users', 'type'));
    }

    // Handle actual update from modal form
    public function update(Request $request)
    {
        switch ($request->input('form_type')) {
            case 'avatar':
                return $this->handleAvatarUpload($request);
            case 'profile_name':
                return $this->handleProfileNameUpdate($request);
            case 'password':
                return $this->handlePasswordChange($request);
            case 'email':
                return $this->handleEmailChange($request);
            default:
                return back()->with('error', 'Invalid form submission');
        }
    }

    // Set user's status to 'Reported'
    public function report($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'Reported';
        $user->save();

        return back()->with('message', "User {$user->id} has been reported.");
    }

    // Set user's status to 'Suspended'
    public function suspend($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'Suspended';
        $user->save();

        return back()->with('message', "User {$user->id} has been suspended.");
    }

    // Delete the user from the database
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('message', "User {$user->id} has been deleted.");
    }

    private function handleAvatarUpload(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('avatar')->store('profile_pictures', 'public');

        auth()->user()->update(['avatar' => $path]);

        return back()->with('success', 'Profile picture updated!');
    }

    private function handleProfileNameUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        auth()->user()->update(['name' => $request->name]);

        return back()->with('success', 'Profile name updated successfully');
    }

    private function handlePasswordChange(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully');
    }

    private function handleEmailChange(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'email' => 'required|email|confirmed|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update(['email' => $request->email]);

        return back()->with('success', 'Email updated successfully');
    }
}
