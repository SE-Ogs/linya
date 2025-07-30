<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'users');

        $perPage = (int) $request->input('per_page', 5);
        $perPage = max(1, min($perPage, 100));

        $query = $request->input('query');

        $usersQuery = User::query()
            ->where('role', $type === 'admins' ? 'admin' : 'user');

        if ($query) {
            $usersQuery->where(function ($q) use ($query) {
                $q->where('name', 'ILIKE', "%{$query}%")
                    ->orWhere('email', 'ILIKE', "%{$query}%");
            });
        }

        $users = $usersQuery->paginate($perPage)->appends($request->all());

        return view('admin-panel.user-manage', compact('users', 'type'));
    }

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

    public function report($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'Reported';
        $user->save();

        return back()->with('message', "User {$user->id} has been reported.");
    }

    public function suspend($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'Suspended';
        $user->save();

        return back()->with('message', "User {$user->id} has been suspended.");
    }

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

        // Logout the user after password change and redirect
        Auth::logout();

        return redirect()->route('resetsuccess')->with('success', 'Password updated successfully!');
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
