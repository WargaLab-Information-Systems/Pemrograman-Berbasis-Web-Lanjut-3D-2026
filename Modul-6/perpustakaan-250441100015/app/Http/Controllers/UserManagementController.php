<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun sendiri.');
        }

        $validated = $request->validate([
            'role' => [
                'required',
                Rule::in(['admin', 'petugas', 'siswa']),
            ],
        ]);

        $user->syncRoles([$validated['role']]);

        return back()->with('success', 'Role pengguna berhasil diperbarui.');
    }
}