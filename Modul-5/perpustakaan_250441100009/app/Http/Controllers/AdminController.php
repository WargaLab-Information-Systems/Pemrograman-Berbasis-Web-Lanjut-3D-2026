<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalBuku = Buku::count();
        $totalPengguna = User::count();
        $totalPeminjaman = Peminjaman::count();

        return view('admin.dashboard', compact('totalBuku', 'totalPengguna', 'totalPeminjaman'));
    }

    public function users()
    {
        $users = User::with('roles')->latest()->get();
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users')->with('status', 'Role pengguna '.$user->name.' berhasil diperbarui.');
    }

    public function peminjaman()
    {
        $peminjamans = Peminjaman::with(['user', 'buku'])->latest()->get();

        return view('admin.peminjaman', compact('peminjamans'));
    }

    public function setujuiPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'disetujui',
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        return redirect()->back()->with('status', 'Pengajuan peminjaman berhasil disetujui.');
    }

    public function tolakPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'ditolak',
        ]);

        return redirect()->back()->with('status', 'Pengajuan peminjaman telah ditolak.');
    }

    public function prosesKembali($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return redirect()->back()->with('status', 'Pengembalian buku berhasil divalidasi.');
    }

    public function laporan()
    {
        $totalBuku = Buku::count();
        $totalPengguna = User::count();
        $totalPeminjaman = Peminjaman::count();
        $bukuDipinjam = Peminjaman::where('status', 'disetujui')->count();
        $bukuKembali = Peminjaman::where('status', 'dikembalikan')->count();

        return view('admin.laporan', compact(
            'totalBuku',
            'totalPengguna',
            'totalPeminjaman',
            'bukuDipinjam',
            'bukuKembali'
        ));
    }
}
