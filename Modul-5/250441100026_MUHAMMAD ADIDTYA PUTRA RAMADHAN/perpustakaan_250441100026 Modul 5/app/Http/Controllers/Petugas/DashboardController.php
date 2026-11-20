<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBuku = Buku::count();
        $totalPeminjaman = Peminjaman::count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $pengembalianSelesai = Peminjaman::where('status', 'dikembalikan')->count();
        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])->latest()->take(5)->get();

        return view('petugas.dashboard', compact(
            'totalBuku',
            'totalPeminjaman',
            'peminjamanAktif',
            'pengembalianSelesai',
            'peminjamanTerbaru'
        ));
    }
}
