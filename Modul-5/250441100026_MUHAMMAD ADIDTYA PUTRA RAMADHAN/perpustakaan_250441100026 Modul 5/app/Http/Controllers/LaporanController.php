<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $totalBuku = Buku::count();
        $totalUser = User::count();
        $totalPeminjaman = Peminjaman::count();
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalDikembalikan = Peminjaman::where('status', 'dikembalikan')->count();

        $query = Peminjaman::with(['user', 'buku', 'anggota'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $riwayatLaporan = $query->paginate(15);

        return view('laporan.index', compact(
            'totalBuku',
            'totalUser',
            'totalPeminjaman',
            'totalDipinjam',
            'totalDikembalikan',
            'riwayatLaporan'
        ));
    }
}
