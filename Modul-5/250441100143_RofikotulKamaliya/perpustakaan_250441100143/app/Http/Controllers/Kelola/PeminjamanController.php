<?php

namespace App\Http\Controllers\Kelola;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $peminjamans = Peminjaman::with(['anggota', 'buku'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest('id')
            ->get();

        return view('kelola.peminjaman.index', compact('peminjamans', 'status'));
    }

    public function setujui(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang dapat disetujui.');
        }

        $peminjaman->update([
            'status' => Peminjaman::STATUS_DIPINJAM,
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        return back()->with('success', 'Pengajuan peminjaman disetujui.');
    }

    public function tolak(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang dapat ditolak.');
        }

        $peminjaman->update(['status' => Peminjaman::STATUS_DITOLAK]);

        return back()->with('success', 'Pengajuan peminjaman ditolak.');
    }
}
