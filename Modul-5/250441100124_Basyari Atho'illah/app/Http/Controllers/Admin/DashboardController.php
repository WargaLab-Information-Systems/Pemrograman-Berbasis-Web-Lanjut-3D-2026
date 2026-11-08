<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama admin dengan ringkasan statistik.
     */
    public function index(): View
    {
        $totalBuku = Buku::count();
        $totalPengguna = User::count();
        $totalPeminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $totalMenunggu = Peminjaman::where('status', 'menunggu')->count();

        $peminjamanTerbaru = Peminjaman::with(['user', 'buku', 'anggota'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalPengguna',
            'totalPeminjamanAktif',
            'totalMenunggu',
            'peminjamanTerbaru'
        ));
    }
}
