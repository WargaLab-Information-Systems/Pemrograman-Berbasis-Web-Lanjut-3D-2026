<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function __invoke(): View
    {
        return view('laporan.index', [
            'totalBuku' => Buku::count(),
            'totalAnggota' => Anggota::count(),
            'perStatus' => Peminjaman::select('status', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('status')
                ->pluck('jumlah', 'status'),
            'bukuPerKategori' => Kategori::withCount('bukus')->orderBy('nama')->get(),
            'terpopuler' => Buku::withCount('peminjamans')
                ->orderByDesc('peminjamans_count')
                ->limit(5)
                ->get(),
        ]);
    }
}
