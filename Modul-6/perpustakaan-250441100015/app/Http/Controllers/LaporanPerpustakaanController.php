<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Anggota;
use App\Models\Peminjaman;

class LaporanPerpustakaanController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalKategori = Kategori::count();
        $totalAnggota = Anggota::count();
        $totalPeminjaman = Peminjaman::count();
        $peminjamanMenunggu = Peminjaman::where(
            'status',
            'menunggu'
        )->count();
        $peminjamanDisetujui = Peminjaman::where(
            'status',
            'disetujui'
        )->count();
        $peminjamanDitolak = Peminjaman::where(
            'status',
            'ditolak'
        )->count();
        $peminjamanDikembalikan = Peminjaman::where(
            'status',
            'dikembalikan'
        )->count();
        return view('admin.laporan-perpustakaan', compact(
            'totalBuku',
            'totalKategori',
            'totalAnggota',
            'totalPeminjaman',
            'peminjamanMenunggu',
            'peminjamanDisetujui',
            'peminjamanDitolak',
            'peminjamanDikembalikan'
        ));
    }
}
