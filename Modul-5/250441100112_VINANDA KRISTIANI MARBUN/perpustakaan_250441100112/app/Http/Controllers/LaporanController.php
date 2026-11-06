<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalKategori = Kategori::count();
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalDikembalikan = Peminjaman::where('status', 'dikembalikan')->count();
        $totalDiajukan = Peminjaman::where('status', 'diajukan')->count();

        // Rekap Buku per Kategori
        $bukuPerKategori = Kategori::withCount('bukus')->get();

        // Rekap Peminjaman per Buku
        $peminjamanPerBuku = Buku::with('kategori')
            ->withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->paginate(15);

        return view('laporan.index', compact(
            'totalBuku',
            'totalKategori',
            'totalDipinjam',
            'totalDikembalikan',
            'totalDiajukan',
            'bukuPerKategori',
            'peminjamanPerBuku'
        ));
    }
}

