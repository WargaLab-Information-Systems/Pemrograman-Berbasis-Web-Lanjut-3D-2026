<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalAdmin = User::role('admin')->count();
        $totalPetugas = User::role('petugas')->count();
        $totalSiswa = User::role('siswa')->count();
        $totalPengguna = User::count();
        $totalPeminjaman = Peminjaman::count();
        $menungguPersetujuan = Peminjaman::where('status', Peminjaman::STATUS_DIAJUKAN)->count();

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalPengguna',
            'totalAdmin',
            'totalPetugas',
            'totalSiswa',
            'totalPeminjaman',
            'menungguPersetujuan'
        ));
    }
}
