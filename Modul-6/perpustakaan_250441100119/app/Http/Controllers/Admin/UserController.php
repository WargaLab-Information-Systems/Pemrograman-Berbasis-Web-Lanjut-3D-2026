<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')
            ->orderBy('id')
            ->paginate(10);

        $roles = Role::where('guard_name', 'web')
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')
                    ->where('guard_name', 'web'),
            ],
        ]);

        // Admin tidak boleh mengubah role akun sendiri.
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Tidak bisa mengubah role akun sendiri.');
        }

        // Hindari menghapus akses Admin terakhir.
        if (
            $user->hasRole('admin') &&
            $request->role !== 'admin' &&
            User::role('admin')->count() <= 1
        ) {
            return back()->with('error', 'Role Admin terakhir tidak boleh dihapus.');
        }

        $user->syncRoles([$request->role]);

        return back()->with('success', 'Role pengguna berhasil diperbarui.');
    }
}