<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Menampilkan rekapitulasi laporan perpustakaan (Admin & Petugas).
     */
    public function index(): View
    {
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $totalUser = User::count();
        $totalPeminjaman = Peminjaman::count();
        $bukuDipinjam = Peminjaman::whereNull('tanggal_kembali')->count();
        $bukuKembali = Peminjaman::whereNotNull('tanggal_kembali')->count();

        $transaksiTerbaru = Peminjaman::with(['anggota', 'buku'])
            ->latest('id')
            ->take(10)
            ->get();

        return view('laporan.index', compact(
            'totalBuku',
            'totalAnggota',
            'totalUser',
            'totalPeminjaman',
            'bukuDipinjam',
            'bukuKembali',
            'transaksiTerbaru'
        ));
    }
}
