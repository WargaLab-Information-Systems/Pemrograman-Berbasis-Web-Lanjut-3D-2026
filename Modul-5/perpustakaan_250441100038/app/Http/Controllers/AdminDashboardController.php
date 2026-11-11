<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'totalBuku' => Buku::count(),
            'totalPengguna' => User::count(),
            'totalPeminjaman' => Peminjaman::count(),
            'menunggu' => Peminjaman::where('status', Peminjaman::STATUS_DIAJUKAN)->count(),
            'dipinjam' => Peminjaman::where('status', Peminjaman::STATUS_DIPINJAM)->count(),
        ]);
    }
}
