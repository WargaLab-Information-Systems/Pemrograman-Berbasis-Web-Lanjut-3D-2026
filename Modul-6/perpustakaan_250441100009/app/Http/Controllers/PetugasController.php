<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;

class PetugasController extends Controller
{
    public function dashboard()
    {
        $totalBuku = Buku::count();
        $peminjamanAktif = Peminjaman::where('status', 'disetujui')->count();
        $peminjamanMenunggu = Peminjaman::where('status', 'menunggu')->count();
        $totalPeminjaman = Peminjaman::count();

        return view('petugas.dashboard', compact('totalBuku', 'peminjamanAktif', 'peminjamanMenunggu', 'totalPeminjaman'));
    }

    public function peminjaman()
    {
        $peminjamans = Peminjaman::with(['user', 'buku'])->latest()->get();

        return view('petugas.peminjaman', compact('peminjamans'));
    }

    public function setujuiPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'disetujui',
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        return redirect()->back()->with('status', 'Peminjaman berhasil disetujui.');
    }

    public function tolakPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'ditolak',
        ]);

        return redirect()->back()->with('status', 'Peminjaman berhasil ditolak.');
    }

    public function prosesKembali($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return redirect()->back()->with('status', 'Buku berhasil dicatat pengembaliannya.');
    }
}
