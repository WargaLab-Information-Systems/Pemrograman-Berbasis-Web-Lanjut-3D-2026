<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;

class LaporanController extends Controller
{
    // Menampilkan laporan peminjaman
    public function index()
    {
        // Mengambil data peminjaman beserta anggota dan buku
        $peminjamans = Peminjaman::with(['anggota', 'buku'])
            ->latest()
            ->paginate(10);

        // Menghitung total seluruh peminjaman
        $totalPeminjaman = Peminjaman::count();

        // Menghitung buku yang sudah dikembalikan
        $totalDikembalikan = Peminjaman::whereNotNull('tanggal_kembali')
            ->count();

        // Menghitung buku yang belum dikembalikan
        $belumDikembalikan = Peminjaman::whereNull('tanggal_kembali')
            ->count();

        return view('laporan.index', compact(
            'peminjamans',
            'totalPeminjaman',
            'totalDikembalikan',
            'belumDikembalikan'
        ));
    }
}
