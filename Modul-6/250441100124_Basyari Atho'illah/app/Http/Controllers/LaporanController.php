<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Menampilkan laporan perpustakaan (Admin & Petugas).
     */
    public function index(): View
    {
        $totalPeminjaman = Peminjaman::count();
        $totalMenunggu = Peminjaman::where('status', 'menunggu')->count();
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalDikembalikan = Peminjaman::where('status', 'dikembalikan')->count();
        $totalDitolak = Peminjaman::where('status', 'ditolak')->count();

        // 5 Buku paling sering dipinjam
        $bukuPopuler = Buku::withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->take(5)
            ->get();

        // Riwayat peminjaman lengkap untuk rekap
        $riwayatLengkap = Peminjaman::with(['user', 'buku', 'anggota'])
            ->latest()
            ->paginate(15);

        return view('laporan.index', compact(
            'totalPeminjaman',
            'totalMenunggu',
            'totalDipinjam',
            'totalDikembalikan',
            'totalDitolak',
            'bukuPopuler',
            'riwayatLengkap'
        ));
    }
}
