<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::with('roles')
            ->latest()
            ->get();
        $roles = Role::query()->orderBy('name')->get();
        $editingUser = $request->filled('edit')
            ? User::with('roles')->findOrFail($request->query('edit'))
            : null;

        return view('admin.users.index', compact('users', 'roles', 'editingUser'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        if ($validated['role'] === 'siswa') {
            $user->anggota()->firstOrCreate([], [
                'nama' => $user->name,
                'email' => $user->email,
                'no_telepon' => '-',
            ]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Menampilkan formulir edit pengguna
    public function edit(User $user): RedirectResponse
    {
        return redirect()->route('admin.users.index', ['edit' => $user->id]);
    }

    // Memperbarui data pengguna
    public function update(Request $request, User $user): RedirectResponse
    {
        if ($user->is(auth()->user()) && $request->input('role') !== 'admin') {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Role akun admin yang sedang digunakan tidak dapat diturunkan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => 'required|exists:roles,name',
        ]);

        if ($validated['role'] !== $user->getRoleNames()->first() && $user->hasActivePeminjamans()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Role pengguna tidak dapat diubah karena masih memiliki peminjaman berstatus menunggu, disetujui, atau dipinjam. Ubah atau tunggu status peminjaman menjadi ditolak atau dikembalikan terlebih dahulu.');
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $user->syncRoles([$validated['role']]);

        if ($validated['role'] === 'siswa') {
            $user->anggota()->firstOrCreate([], [
                'nama' => $user->name,
                'email' => $user->email,
                'no_telepon' => '-',
            ]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
