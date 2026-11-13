<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;

class ManajemenPeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])
            ->latest()
            ->get();

        return view('peminjaman.index', compact('peminjamans'));
    }
    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with(
                'error',
                'Pengajuan ini sudah diproses.'
            );
        }

        $peminjaman->update([
            'status' => 'disetujui',
        ]);

        return back()->with(
            'success',
            'Pengajuan peminjaman berhasil disetujui.'
        );
    }

    public function reject(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with(
                'error',
                'Pengajuan ini sudah diproses.'
            );
        }

        $peminjaman->update([
            'status' => 'ditolak',
        ]);

        return back()->with(
            'success',
            'Pengajuan peminjaman berhasil ditolak.'
        );
    }
    public function recordReturn(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'disetujui') {
            return back()->with(
                'error',
                'Buku hanya dapat dikembalikan jika peminjaman sudah disetujui.'
            );
        }

        $peminjaman->update([
            'tanggal_kembali' => now()->toDateString(),
            'status' => 'dikembalikan',
        ]);

        return back()->with(
            'success',
            'Pengembalian buku berhasil dicatat.'
        );
    }
}
