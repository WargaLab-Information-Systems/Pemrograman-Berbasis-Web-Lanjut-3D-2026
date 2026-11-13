<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RiwayatPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $anggota = $request->user()->anggota;

        if (!$anggota) {
            abort(403, 'Data anggota tidak ditemukan.');
        }

        $peminjamans = $anggota->peminjamans()
            ->with('buku')
            ->latest()
            ->get();

        return view('siswa.riwayat-peminjaman', compact('peminjamans'));
    }
}