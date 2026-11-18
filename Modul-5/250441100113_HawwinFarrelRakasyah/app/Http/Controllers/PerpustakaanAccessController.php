<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PerpustakaanAccessController extends Controller
{
    public function dashboardAdmin()
    {
        $totalBuku = Buku::count();
        $totalUser = User::count();
        $users = User::with('roles')->latest()->take(5)->get();

        return view('dashboard.admin', compact('totalBuku', 'totalUser', 'users'));
    }

    public function dashboardPetugas()
    {
        $totalBuku = Buku::count();
        $totalPeminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $totalPengajuan = Peminjaman::where('status', 'menunggu')->count();

        return view('dashboard.petugas', compact('totalBuku', 'totalPeminjamanAktif', 'totalPengajuan'));
    }

    public function dashboardSiswa()
    {
        $totalBuku = Buku::count();
        $user = Auth::user();
        $pinjamanAktif = Peminjaman::where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->count();

        return view('dashboard.siswa', compact('totalBuku', 'user', 'pinjamanAktif'));
    }

    public function indexUsers()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return back()->with('success', "Pengguna {$user->name} dengan role {$request->role} berhasil ditambahkan.");
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->syncRoles([$request->role]);

        return back()->with('success', "Role untuk {$user->name} berhasil diperbarui menjadi {$request->role}.");
    }

    public function destroyUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return back()->with('success', 'Data pengguna berhasil dihapus.');
    }

    // 1. Fitur Siswa: Mengajukan Peminjaman Buku
    public function ajukanPeminjaman(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        // Cek apakah buku sedang dipinjam atau dalam status menunggu oleh user yang sama
        $cekPinjaman = Peminjaman::where('user_id', Auth::id())
            ->where('buku_id', $request->buku_id)
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->first();

        if ($cekPinjaman) {
            return back()->with('error', 'Anda sudah mengajukan atau sedang meminjam buku ini.');
        }

        Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'menunggu',
        ]);

        return redirect()->route('peminjaman.riwayat.sendiri')->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan petugas.');
    }

    // 2. Fitur Petugas/Admin: Melihat Antrean Peminjaman & Pengembalian
    public function kelolaPeminjaman()
    {
        $peminjamans = Peminjaman::with(['user', 'buku.kategori'])->latest()->get();
        return view('peminjaman.index', compact('peminjamans'));
    }

    // 3. Fitur Petugas/Admin: Menyetujui Peminjaman
    public function setujuiPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        return back()->with('success', 'Peminjaman buku berhasil disetujui.');
    }

    // 4. Fitur Petugas/Admin: Menolak Peminjaman
    public function tolakPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'ditolak',
        ]);

        return back()->with('success', 'Pengajuan peminjaman buku telah ditolak.');
    }

    // 5. Fitur Petugas/Admin: Mencatat Pengembalian Buku
    public function kembalikanBuku($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return back()->with('success', 'Pengembalian buku berhasil dicatat.');
    }

    // 6. Laporan Perpustakaan
    public function laporanPerpustakaan()
    {
        $bukus = Buku::with('kategori')->get();
        $totalUser = User::count();
        $totalPeminjaman = Peminjaman::count();
        $totalDikembalikan = Peminjaman::where('status', 'dikembalikan')->count();

        return view('peminjaman.laporan', compact('bukus', 'totalUser', 'totalPeminjaman', 'totalDikembalikan'));
    }

    // 7. Riwayat Peminjaman Milik Sendiri (Validasi Kepemilikan Data Pribadi)
    public function riwayatSendiri($userId = null)
    {
        if ($userId !== null && (int) $userId !== Auth::id()) {
            abort(403, 'Akses ditolak: Anda tidak diizinkan melihat riwayat peminjaman milik pengguna lain.');
        }

        $user = Auth::user();
        $riwayats = Peminjaman::with('buku.kategori')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('peminjaman.riwayat-sendiri', compact('user', 'riwayats'));
    }

    // 8. Profil Sendiri (Validasi Kepemilikan Data Pribadi)
    public function profilSendiri($userId = null)
    {
        if ($userId !== null && (int) $userId !== Auth::id()) {
            abort(403, 'Akses ditolak: Anda hanya dapat melihat dan mengubah profil milik Anda sendiri.');
        }

        $user = Auth::user();
        return view('profile.saya', compact('user'));
    }

    public function updateProfilSendiri(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}