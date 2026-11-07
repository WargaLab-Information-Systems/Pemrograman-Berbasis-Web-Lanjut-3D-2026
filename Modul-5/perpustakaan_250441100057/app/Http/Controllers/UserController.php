<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
 
    public function index()
    {
        $users = User::with('roles')->latest()->get();

        return view('users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => [
                'required',
                Rule::in(['admin', 'petugas', 'siswa']),
            ],
        ]);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Role akun sendiri tidak dapat diubah.');
        }

        $user->syncRoles([$request->role]);

        return back()->with('success', 'Role pengguna berhasil diubah.');
    }
}
