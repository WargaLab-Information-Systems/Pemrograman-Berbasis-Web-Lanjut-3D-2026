<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBuku = Buku::count();
        $totalUser = User::count();
        $totalPeminjaman = Peminjaman::count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalUser',
            'totalPeminjaman',
            'peminjamanAktif',
            'peminjamanTerbaru'
        ));
    }
}
