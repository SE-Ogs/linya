<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'users');

        $perPage = (int) $request->input('per_page', 5);
        $perPage = max(1, min($perPage, 100));

        $searchQuery = $request->input('query');
        $statusFilter = $request->input('status');

        $role = match ($type) {
            'admins' => 'admin',
            'writers' => 'writer',
            default => 'user',
        };

        $usersQuery = User::query()->where('role', $role);

        if ($searchQuery) {
            $usersQuery->where(function ($q) use ($searchQuery) {
                $q->where('name', 'ILIKE', "%{$searchQuery}%")
                    ->orWhere('email', 'ILIKE', "%{$searchQuery}%");
            });
        }

        if ($statusFilter && in_array($statusFilter, ['Active', 'Banned'])) {
            $usersQuery->where('status', $statusFilter);
        }

        $users = $usersQuery->paginate($perPage)->appends($request->all());

        return view('admin-panel.user-manage', compact('users', 'type'));
    }

    // User self-service update (settings page)
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

    // Admin updates another user's profile (name/email)
    public function adminUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update($validated);

        return back()->with('message', "User {$user->id} has been updated.");
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

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'Active';
        $user->save();

        return back()->with('message', "User {$user->id} has been activated.");
    }


    public function ban(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason'  => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($request) {
            // Update user status
            $user = User::findOrFail($request->user_id);
            $user->status = 'Banned';
            $user->save();

            // Create ban record
            UserBan::create([
                'user_id'   => $user->id,
                'reason'    => $request->reason,
                'banned_by' => auth()->id(),
                'banned_at' => now(),
            ]);
        });

        return redirect()->back()->with('success', 'User has been banned successfully.');
    }

    public function unban($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'Active';
        $user->save();

        return back()->with('message', "User {$user->id} has been unbanned.");
    }

    public function setRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => 'required|in:user,writer,admin',
        ]);

        $user = User::findOrFail($id);

        // Safety checks
        if (auth()->id() === $user->id && $validated['role'] !== 'admin') {
            return back()->with('error', 'You cannot change your own role to non-admin.');
        }
        if ($user->role === 'admin' && auth()->id() !== $user->id && $validated['role'] !== 'admin') {
            return back()->with('error', 'You cannot change another admin\'s role.');
        }

        $user->role = $validated['role'];
        $user->save();

        return back()->with('message', "User {$user->id} role set to {$user->role}.");
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
