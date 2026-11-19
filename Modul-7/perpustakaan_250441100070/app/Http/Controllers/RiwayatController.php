<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RiwayatController extends Controller
{
    /**
     * Status dan riwayat peminjaman seorang pengguna.
     *
     * Selain permission (middleware di route), akses dicek berdasarkan identitas:
     * siswa hanya boleh membuka riwayat miliknya sendiri. Membuka /riwayat/{id}
     * milik siswa lain menghasilkan 403.
     */
    public function show(User $user): View
    {
        Gate::authorize('viewRiwayat', $user);

        $peminjamans = $user->anggota
            ? $user->anggota->peminjamans()->with('buku.kategori')->latest('id')->get()
            : collect();

        $aktif = $peminjamans->whereIn('status', [Peminjaman::DIAJUKAN, Peminjaman::DIPINJAM]);
        $riwayat = $peminjamans->whereIn('status', [Peminjaman::DIKEMBALIKAN, Peminjaman::DITOLAK]);

        return view('riwayat.show', compact('user', 'aktif', 'riwayat'));
    }
}
