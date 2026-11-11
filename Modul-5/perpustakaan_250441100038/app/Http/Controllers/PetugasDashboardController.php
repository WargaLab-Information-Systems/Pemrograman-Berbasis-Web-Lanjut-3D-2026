<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;

class PetugasDashboardController extends Controller
{
    public function __invoke()
    {
        return view('petugas.dashboard', [
            'totalBuku' => Buku::count(),
            'menunggu' => Peminjaman::where('status', Peminjaman::STATUS_DIAJUKAN)->count(),
            'dipinjam' => Peminjaman::where('status', Peminjaman::STATUS_DIPINJAM)->count(),
        ]);
    }
}
