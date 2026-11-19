<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;

class PetugasController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $menungguPersetujuan = Peminjaman::where('status', Peminjaman::STATUS_DIAJUKAN)->count();
        $sedangDipinjam = Peminjaman::where('status', Peminjaman::STATUS_DIPINJAM)->count();

        return view('petugas.dashboard', compact('totalBuku', 'menungguPersetujuan', 'sedangDipinjam'));
    }
}
