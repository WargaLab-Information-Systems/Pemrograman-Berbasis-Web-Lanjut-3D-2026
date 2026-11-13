<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Halaman Laporan Perpustakaan (Admin & Petugas)
     */
    public function index(): View
    {
        $kategoris = Kategori::withCount('buku')->get();
        $bukuPopuler = Buku::with('kategori')->withCount('peminjaman')->orderByDesc('peminjaman_count')->take(10)->get();

        $stats = [
            'total_buku'        => Buku::count(),
            'total_peminjaman'  => Peminjaman::count(),
            'sedang_dipinjam'   => Peminjaman::whereNull('tanggal_kembali')->where('status', '!=', 'ditolak')->count(),
            'sudah_dikembalikan'=> Peminjaman::whereNotNull('tanggal_kembali')->count(),
            'menunggu'          => Peminjaman::where('status', 'menunggu')->count(),
        ];

        $riwayatLengkap = Peminjaman::with(['buku', 'user', 'anggota'])->latest()->take(20)->get();

        return view('laporan.index', [
            'kategoris'      => $kategoris,
            'bukuPopuler'    => $bukuPopuler,
            'stats'          => $stats,
            'riwayatLengkap' => $riwayatLengkap,
        ]);
    }
}

