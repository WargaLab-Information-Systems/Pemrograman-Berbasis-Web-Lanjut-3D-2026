<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Menampilkan daftar seluruh akun pengguna beserta role masing-masing.
     */
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Menyimpan pengguna baru sekaligus menetapkan role oleh Admin.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        // Sinkronkan ke tabel anggotas
        Anggota::firstOrCreate(
            ['email' => $user->email],
            [
                'nama' => $user->name,
                'no_telepon' => $request->input('no_telepon'),
            ]
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$user->name} berhasil ditambahkan dengan role {$validated['role']}.");
    }

    /**
     * Memperbarui data pengguna dan role oleh Admin.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', 'exists:roles,name'],
            'password' => ['nullable', Rules\Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Tetapkan role baru
        $user->syncRoles([$validated['role']]);

        // Perbarui juga data anggota jika ada
        $anggota = Anggota::where('email', $user->getOriginal('email'))->first();
        if ($anggota) {
            $anggota->update([
                'nama' => $user->name,
                'email' => $user->email,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Data dan role pengguna {$user->name} berhasil diperbarui.");
    }

    /**
     * Menghapus akun pengguna oleh Admin.
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $nama = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$nama} berhasil dihapus.");
    }
}

