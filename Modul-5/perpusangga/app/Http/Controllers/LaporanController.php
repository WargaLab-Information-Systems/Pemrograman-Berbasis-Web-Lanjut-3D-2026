<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;

class LaporanController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalPeminjaman = Peminjaman::count();
        $totalDipinjam = Peminjaman::where('status', 'Dipinjam')->count();
        $totalDikembalikan = Peminjaman::where('status', 'Dikembalikan')->count();
        $peminjamans = Peminjaman::with(['user', 'buku'])->latest()->get();

        return view('laporan.index', compact(
            'totalBuku',
            'totalPeminjaman',
            'totalDipinjam',
            'totalDikembalikan',
            'peminjamans'
        ));
    }
}