<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('admin.pengguna', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return back()->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    public function updateRole(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($user->email === 'admin@gmail.com') {
            return back()->with('error', 'Role Admin Utama tidak boleh diubah!');
        }

        $user->syncRoles($request->role);

        return back()->with('success', 'Role pengguna berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->email === 'admin@gmail.com') {
            return back()->with('error', 'Akun Admin Utama tidak boleh dihapus!');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang Anda gunakan!');
        }

        $user->delete();

        return back()->with('success', 'Akun pengguna berhasil dihapus!');
    }
}
