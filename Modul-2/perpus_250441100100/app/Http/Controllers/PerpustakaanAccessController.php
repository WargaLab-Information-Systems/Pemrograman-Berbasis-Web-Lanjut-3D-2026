<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class PerpustakaanAccessController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function dashboardAdmin()
    {
        return view('dashboard.admin', $this->dataDashboardStaf());
    }

    /**
     * Dashboard Petugas
     */
    public function dashboardPetugas()
    {
        return view('dashboard.petugas', $this->dataDashboardStaf());
    }

    /**
     * Dashboard Siswa: hanya menampilkan data milik siswa yang login.
     */
    public function dashboardSiswa()
    {
        $userId = auth()->id();

        return view('dashboard.siswa', [
            'totalBuku' => Buku::count(),
            'pinjamanAktif' => Peminjaman::milik($userId)->aktif()->count(),
            'totalRiwayat' => Peminjaman::milik($userId)->count(),
        ]);
    }

    private function dataDashboardStaf(): array
    {
        $buku = Buku::with('kategori')->get();

        return [
            'totalBuku' => Buku::count(),
            'totalUser' => User::count(),
            'totalPeminjaman' => Peminjaman::count(),
            'menunggu' => Peminjaman::where('status', Peminjaman::STATUS_DIAJUKAN)->count(),
            'bukuTerbaru' => Buku::with('kategori')->latest()->take(5)->get(),
            'buku' => $buku,
            'bukus' => $buku,
        ];
    }

    /* ------------------------------------------------------------------
     | KELOLA PENGGUNA (ADMIN)
     |------------------------------------------------------------------ */

    public function indexUsers()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => ['required', Rule::in($this->namaRole())],
        ]);

        // Password di-hash otomatis oleh cast "hashed" pada model User.
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ]);

        $user->assignRole($validated['role']);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in($this->namaRole())],
        ]);

        if ($user->is($request->user())) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        $user->syncRoles([$validated['role']]);

        return redirect()->back()->with('success', 'Role pengguna berhasil diperbarui!');
    }

    public function destroyUser(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus!');
    }

    private function namaRole(): array
    {
        return Role::pluck('name')->all();
    }

    /* ------------------------------------------------------------------
     | LAPORAN (ADMIN & PETUGAS)
     |------------------------------------------------------------------ */

    public function laporanPerpustakaan()
    {
        $ringkasan = [
            'totalBuku' => Buku::count(),
            'totalUser' => User::count(),
            'totalPeminjaman' => Peminjaman::count(),
            'diajukan' => Peminjaman::where('status', Peminjaman::STATUS_DIAJUKAN)->count(),
            'dipinjam' => Peminjaman::where('status', Peminjaman::STATUS_DIPINJAM)->count(),
            'dikembalikan' => Peminjaman::where('status', Peminjaman::STATUS_DIKEMBALIKAN)->count(),
            'ditolak' => Peminjaman::where('status', Peminjaman::STATUS_DITOLAK)->count(),
        ];

        $bukuPopuler = Buku::with('kategori')
            ->withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->take(5)
            ->get();

        $peminjaman = Peminjaman::with(['user', 'anggota', 'buku'])->latest()->get();

        return view('peminjaman.laporan', array_merge($ringkasan, [
            'bukuPopuler' => $bukuPopuler,
            'peminjaman' => $peminjaman,
        ]));
    }

    /* ------------------------------------------------------------------
     | PROFIL MILIK SENDIRI
     |------------------------------------------------------------------ */

    /**
     * Profil Saya.
     * Parameter {userId} hanya diterima bila sama dengan pengguna login;
     * profil pengguna lain ditolak (403).
     */
    public function profilSendiri(Request $request, $userId = null)
    {
        if ($userId !== null && (int) $userId !== (int) $request->user()->id) {
            abort(403, 'Anda tidak berhak mengakses profil pengguna lain.');
        }

        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update Profil Saya (selalu pada akun yang sedang login).
     */
    public function updateProfilSendiri(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profil.saya')->with('status', 'profile-updated');
    }

    /**
     * Hapus Akun Profil Saya.
     */
    public function destroyProfilSendiri(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Jangan sampai sistem tidak punya admin sama sekali.
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return back()->withErrors(
                ['password' => 'Akun admin terakhir tidak dapat dihapus.'],
                'userDeletion'
            );
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
