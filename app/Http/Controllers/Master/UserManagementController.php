<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('full_name')->paginate(20);
        return view('master.users', compact('users'));
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'You cannot deactivate your own account.']);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        AuditLogger::log('user_active_toggled', 'user', $user->id, [
            'is_active' => $user->is_active,
        ]);

        return back()->with('success', 'User activation status updated.');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,parent,student',
        ]);

        if ($user->role === 'super_admin') {
            return back()->withErrors(['user' => 'Super Admin role cannot be changed here.']);
        }

        $oldRole = $user->role;
        $user->role = $request->role;
        $user->save();

        AuditLogger::log('user_role_changed', 'user', $user->id, [
            'old_role' => $oldRole,
            'new_role' => $user->role,
        ]);

        return back()->with('success', 'User role updated.');
    }
}

