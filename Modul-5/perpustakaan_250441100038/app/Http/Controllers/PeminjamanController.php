<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    /** Admin/Petugas: semua data peminjaman. */
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'anggota', 'buku'])->latest()->get();

        return view('peminjaman.index', compact('peminjamans'));
    }

    /** Semua role: mengajukan peminjaman atas nama akun yang sedang login. */
    public function ajukan(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $sudahAda = Peminjaman::where('user_id', $request->user()->id)
            ->where('buku_id', $buku->id)
            ->whereIn('status', [Peminjaman::STATUS_DIAJUKAN, Peminjaman::STATUS_DIPINJAM])
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Anda sudah mengajukan/meminjam buku ini.');
        }

        Peminjaman::create([
            'user_id' => $request->user()->id, // selalu dari user login, bukan dari input
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => Peminjaman::STATUS_DIAJUKAN,
        ]);

        return redirect()->route('riwayat.index')->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }

    public function setujui(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang bisa disetujui.');
        }

        $peminjaman->update([
            'status' => Peminjaman::STATUS_DIPINJAM,
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function tolak(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang bisa ditolak.');
        }

        $peminjaman->update(['status' => Peminjaman::STATUS_DITOLAK]);

        return back()->with('success', 'Pengajuan ditolak.');
    }
}
