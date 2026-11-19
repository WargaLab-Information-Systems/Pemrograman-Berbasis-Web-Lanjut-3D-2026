<?php

namespace App\Http\Controllers\Kelola;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;

class PengembalianController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])
            ->where('status', Peminjaman::STATUS_DIPINJAM)
            ->latest('id')
            ->get();

        return view('kelola.pengembalian.index', compact('peminjamans'));
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
