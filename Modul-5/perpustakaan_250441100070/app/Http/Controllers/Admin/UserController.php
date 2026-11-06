<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

/**
 * Kelola akun dan role pengguna. Seluruh route dilindungi
 * permission "kelola data pengguna" (hanya admin).
 */
class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->orderBy('name')->get();

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'role' => ['nullable', Rule::in(Role::pluck('name')->all())],
        ]);

        if (! empty($data['role'])) {
            // Menetapkan role butuh permission tersendiri
            abort_unless($request->user()->can('kelola role pengguna'), 403);

            // Admin tidak boleh mengubah role akunnya sendiri (mencegah terkunci)
            if ($request->user()->is($user) && ! $user->hasRole($data['role'])) {
                return back()->withInput()->with('galat', 'Anda tidak dapat mengubah role akun Anda sendiri.');
            }

            $user->syncRoles($data['role']);
        }

        $user->update(Arr::only($data, ['name', 'email']));

        return redirect()->route('admin.users.index')->with('sukses', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->with('galat', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('sukses', 'Pengguna berhasil dihapus.');
    }
}
