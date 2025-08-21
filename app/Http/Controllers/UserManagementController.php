<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserBan;
use App\Models\UserUnban;
use App\Models\CommentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $type = $request->get('type', 'users');


        $query = User::withCount([
            'commentReports as comment_reports_count',
            'userBans as user_bans_count'
        ])
            ->when($type === 'users', function ($query) {
                $query->where('role', 'user');
            })
            ->when($type === 'writers', function ($query) {
                $query->where('role', 'writer');
            })
            ->when($type === 'admins', function ($query) {
                $query->where('role', 'admin');
            })
            ->when($request->get('query'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->get('status') && $request->get('status') !== 'All', function ($query, $status) {
                $query->where('status', $status);
            });

        $users = $query->paginate($perPage);

        return view('admin-panel.user-manage', compact('users'));
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

    public function unban(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            if ($user->status !== 'Banned') {
                throw new \Exception('User is not currently banned');
            }

            $user->status = 'Active';
            $user->save();

            if ($ban = $user->bans()->latest()->first()) {
                $ban->update(['unbanned_at' => now()]);

                UserUnban::create([
                    'user_id' => $user->id,
                    'user_ban_id' => $ban->id,
                    'reason' => $request->reason,
                    'unbanned_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User unbanned successfully',
                'redirect' => url()->previous()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Unban failed: ' . $e->getMessage()
            ], 500);
        }
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

    public function getUserReports($id)
    {
        try {
            \Log::info('Fetching reports for user ID: ' . $id);

            // First, let's check if the user exists
            $userExists = User::find($id);
            if (!$userExists) {
                \Log::error('User not found: ' . $id);
                return response()->json(['error' => 'User not found'], 404);
            }

            // Check if comment_reports table exists and has data
            $tableExists = \Schema::hasTable('comment_reports');
            \Log::info('comment_reports table exists: ' . ($tableExists ? 'yes' : 'no'));

            if (!$tableExists) {
                return response()->json(['reports' => []]);
            }

            $reportsCount = \DB::table('comment_reports')->where('user_id', $id)->count();
            \Log::info('Reports count for user ' . $id . ': ' . $reportsCount);

            $reports = \DB::table('comment_reports')
                ->leftJoin('users as reviewed_by_user', 'comment_reports.reviewed_by', '=', 'reviewed_by_user.id')
                ->where('comment_reports.user_id', $id)
                ->select([
                    'comment_reports.id',
                    'comment_reports.reason',
                    'comment_reports.additional_info',
                    'comment_reports.status',
                    'comment_reports.created_at',
                    'comment_reports.reviewed_at',
                    'reviewed_by_user.id as reviewed_by_id',
                    'reviewed_by_user.name as reviewed_by_name'
                ])
                ->orderBy('comment_reports.created_at', 'desc')
                ->get();

            \Log::info('Reports fetched: ' . $reports->count());

            $formattedReports = $reports->map(function ($report) {
                return [
                    'id' => $report->id,
                    'reason' => $report->reason,
                    'additional_info' => $report->additional_info,
                    'status' => $report->status,
                    'created_at' => $report->created_at,
                    'reviewed_at' => $report->reviewed_at,
                    'reviewed_by' => $report->reviewed_by_id ? [
                        'id' => $report->reviewed_by_id,
                        'name' => $report->reviewed_by_name
                    ] : null
                ];
            });

            return response()->json(['reports' => $formattedReports]);
        } catch (\Exception $e) {
            \Log::error('Error fetching user reports: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Failed to load reports', 'details' => $e->getMessage()], 500);
        }
    }

    public function getBanHistory($id)
    {
        try {
            \Log::info('Fetching ban history for user ID: ' . $id);

            // Check if user exists
            $userExists = User::find($id);
            if (!$userExists) {
                \Log::error('User not found: ' . $id);
                return response()->json(['error' => 'User not found'], 404);
            }

            // Check if user_bans table exists
            $tableExists = \Schema::hasTable('user_bans');
            \Log::info('user_bans table exists: ' . ($tableExists ? 'yes' : 'no'));

            if (!$tableExists) {
                return response()->json(['bans' => []]);
            }

            $bansCount = \DB::table('user_bans')->where('user_id', $id)->count();
            \Log::info('Bans count for user ' . $id . ': ' . $bansCount);

            $bans = \DB::table('user_bans')
                ->leftJoin('users as banned_by_user', 'user_bans.banned_by', '=', 'banned_by_user.id')
                ->leftJoin('user_unbans', 'user_bans.id', '=', 'user_unbans.user_ban_id')
                ->leftJoin('users as unbanned_by_user', 'user_unbans.unbanned_by', '=', 'unbanned_by_user.id')
                ->where('user_bans.user_id', $id)
                ->select([
                    'user_bans.id',
                    'user_bans.reason',
                    'user_bans.banned_at',
                    'user_bans.unbanned_at',
                    'banned_by_user.name as banned_by_name',
                    'user_unbans.reason as unban_reason',
                    'user_unbans.unbanned_at as actual_unban_date',
                    'unbanned_by_user.name as unbanned_by_name'
                ])
                ->orderBy('user_bans.banned_at', 'desc')
                ->get();

            \Log::info('Bans fetched: ' . $bans->count());

            $formattedBans = $bans->map(function ($ban) {
                return [
                    'id' => $ban->id,
                    'reason' => $ban->reason,
                    'banned_at' => $ban->banned_at,
                    'banned_by_name' => $ban->banned_by_name,
                    'unbanned_at' => $ban->actual_unban_date ?? $ban->unbanned_at,
                    'unbanned_by_name' => $ban->unbanned_by_name,
                    'unban_reason' => $ban->unban_reason
                ];
            });

            return response()->json(['bans' => $formattedBans]);
        } catch (\Exception $e) {
            \Log::error('Error fetching ban history: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Failed to load ban history', 'details' => $e->getMessage()], 500);
        }
    }

    private function handleAvatarUpload(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            // Store without ACL parameter
            $path = $request->file('avatar')->store('profile_pictures', 's3');

            auth()->user()->update(['avatar' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated!',
                'avatar_url' => Storage::disk('s3')->url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
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
