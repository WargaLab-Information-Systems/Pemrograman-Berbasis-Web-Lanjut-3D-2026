<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

/**
 * Kelola akun pengguna (permission "kelola data pengguna", dijaga di route).
 * Penetapan/perubahan role hanya untuk yang punya "kelola role pengguna".
 */
class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = User::with('roles')->orderBy('name')->get();

        return view('pengguna.index', compact('pengguna'));
    }

    public function create()
    {
        return view('pengguna.create', ['roles' => Role::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::exists('roles', 'name')],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Tanpa "kelola role pengguna", akun baru otomatis siswa
        $user->assignRole($request->user()->can('kelola role pengguna') ? $data['role'] : 'siswa');

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $pengguna)
    {
        return view('pengguna.edit', ['pengguna' => $pengguna, 'roles' => Role::orderBy('name')->get()]);
    }

    public function update(Request $request, User $pengguna)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($pengguna->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::exists('roles', 'name')],
        ]);

        $pengguna->name = $data['name'];
        $pengguna->email = $data['email'];

        if (! empty($data['password'])) {
            $pengguna->password = Hash::make($data['password']);
        }

        $pengguna->save();

        // Perubahan role hanya jika berwenang dan role memang berubah
        if ($request->user()->can('kelola role pengguna') && ! $pengguna->hasRole($data['role'])) {
            if ($pengguna->is($request->user())) {
                return back()->withInput()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
            }

            $pengguna->syncRoles([$data['role']]);
        }

        return redirect()->route('pengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $pengguna)
    {
        if ($pengguna->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $pengguna->delete();

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
