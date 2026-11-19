<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $anggota = $request->user()->ensureAnggota();

        $totalBuku = Buku::count();
        $pengajuan = Peminjaman::where('anggota_id', $anggota->id)
            ->where('status', Peminjaman::STATUS_DIAJUKAN)->count();
        $sedangDipinjam = Peminjaman::where('anggota_id', $anggota->id)
            ->where('status', Peminjaman::STATUS_DIPINJAM)->count();

        return view('siswa.dashboard', compact('totalBuku', 'pengajuan', 'sedangDipinjam'));
    }
}
