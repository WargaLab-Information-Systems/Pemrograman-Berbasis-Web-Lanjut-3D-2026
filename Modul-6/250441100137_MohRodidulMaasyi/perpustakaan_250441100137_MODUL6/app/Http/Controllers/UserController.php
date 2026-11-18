<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Tampilkan daftar akun pengguna
     */
    public function index(): View
    {
        $users = User::with('roles')->latest()->paginate(10);
        $roles = Role::all();

        return view('users.index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    /**
     * Form tambah pengguna baru
     */
    public function create(): View
    {
        return view('users.create', [
            'roles' => Role::all(),
        ]);
    }

    /**
     * Simpan pengguna baru beserta penetapan role
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::defaults()],
            'role'     => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        $user->assignRole($request->role);

        // Jika role siswa, buatkan entri anggota
        if ($request->role === 'siswa') {
            Anggota::firstOrCreate(
                ['email' => $user->email],
                [
                    'user_id'    => $user->id,
                    'nama'       => $user->name,
                    'no_telepon' => '-',
                ]
            );
        }

        return redirect()->route('users.index')->with('success', "Pengguna {$user->name} berhasil ditambahkan dengan role {$request->role}.");
    }

    /**
     * Form ubah role dan akun pengguna
     */
    public function edit(User $user): View
    {
        return view('users.edit', [
            'user'  => $user,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Perbarui akun dan role pengguna
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'  => ['required', 'exists:roles,name'],
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

        if ($request->filled('password')) {
            $request->validate(['password' => Password::defaults()]);
            $user->password = Hash::make($request->password);
        }

        $user->save();
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', "Data dan role pengguna {$user->name} berhasil diperbarui.");
    }

    /**
     * Hapus pengguna
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}

