<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;

class PengembalianController extends Controller
{
    public function index()
    {
        $aktif = Peminjaman::with(['user', 'anggota', 'buku'])
            ->where('status', Peminjaman::STATUS_DIPINJAM)->latest()->get();

        $selesai = Peminjaman::with(['user', 'anggota', 'buku'])
            ->where('status', Peminjaman::STATUS_DIKEMBALIKAN)->latest()->take(20)->get();

        return view('pengembalian.index', compact('aktif', 'selesai'));
    }

    public function store(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DIPINJAM) {
            return back()->with('error', 'Buku ini tidak sedang dipinjam.');
        }

        $peminjaman->update([
            'status' => Peminjaman::STATUS_DIKEMBALIKAN,
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return back()->with('success', 'Pengembalian buku berhasil dicatat.');
    }
}
