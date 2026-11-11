<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        // Hanya Admin dan Petugas yang boleh melihat laporan.
        abort_unless(
            $request->user()->can('lihat laporan perpustakaan'),
            403
        );

        // Statistik utama.
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $totalPeminjaman = Peminjaman::count();

        // Rekap berdasarkan status peminjaman.
        $rekapStatus = [
            'menunggu' => Peminjaman::where('status', 'menunggu')->count(),
            'disetujui' => Peminjaman::where('status', 'disetujui')->count(),
            'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'dikembalikan' => Peminjaman::where('status', 'dikembalikan')->count(),
            'ditolak' => Peminjaman::where('status', 'ditolak')->count(),
        ];

        // Daftar peminjaman terbaru.
        $peminjamans = Peminjaman::with(['anggota', 'buku'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('laporan.index', compact(
            'totalBuku',
            'totalAnggota',
            'totalPeminjaman',
            'rekapStatus',
            'peminjamans'
        ));
    }
}