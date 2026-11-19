<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;

class LaporanController extends Controller
{
    public function __invoke()
    {
        $ringkasan = [
            'Total Buku' => Buku::count(),
            'Total Anggota' => Anggota::count(),
            'Pengajuan Menunggu' => Peminjaman::where('status', Peminjaman::STATUS_DIAJUKAN)->count(),
            'Sedang Dipinjam' => Peminjaman::where('status', Peminjaman::STATUS_DIPINJAM)->count(),
            'Sudah Dikembalikan' => Peminjaman::where('status', Peminjaman::STATUS_DIKEMBALIKAN)->count(),
            'Ditolak' => Peminjaman::where('status', Peminjaman::STATUS_DITOLAK)->count(),
        ];

        $bukuPerKategori = Kategori::withCount('bukus')->orderBy('nama')->get();

        $peminjamanPerBuku = Buku::with('kategori')
            ->withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->get();

        $transaksiTerbaru = Peminjaman::with(['anggota', 'buku'])
            ->latest('id')
            ->take(5)
            ->get();

        return view('laporan.index', compact(
            'ringkasan',
            'bukuPerKategori',
            'peminjamanPerBuku',
            'transaksiTerbaru'
        ));
    }
}