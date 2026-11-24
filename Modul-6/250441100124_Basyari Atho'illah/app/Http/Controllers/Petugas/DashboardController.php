<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard operasional petugas.
     */
    public function index(): View
    {
        $totalBuku = Buku::count();
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalMenunggu = Peminjaman::where('status', 'menunggu')->count();
        $totalDikembalikan = Peminjaman::where('status', 'dikembalikan')->count();

        // Antrean peminjaman yang menunggu persetujuan petugas
        $antreanPeminjaman = Peminjaman::with(['user', 'buku', 'anggota'])
            ->where('status', 'menunggu')
            ->latest()
            ->take(8)
            ->get();

        // Peminjaman aktif
        $peminjamanAktif = Peminjaman::with(['user', 'buku', 'anggota'])
            ->where('status', 'dipinjam')
            ->latest()
            ->take(8)
            ->get();

        return view('petugas.dashboard', compact(
            'totalBuku',
            'totalDipinjam',
            'totalMenunggu',
            'totalDikembalikan',
            'antreanPeminjaman',
            'peminjamanAktif'
        ));
    }
}
