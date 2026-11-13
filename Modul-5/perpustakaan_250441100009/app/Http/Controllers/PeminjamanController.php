<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function riwayat()
    {
        $user = auth()->user();
        $peminjamans = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('siswa.riwayat', compact('peminjamans'));
    }

    public function detail($id)
    {
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);

        if ($peminjaman->user_id !== auth()->id() && ! auth()->user()->hasRole(['admin', 'petugas'])) {
            abort(403, 'Akses ditolak. Anda tidak berhak melihat riwayat peminjaman pengguna lain.');
        }

        return view('siswa.detail_riwayat', compact('peminjaman'));
    }

    public function ajukanPinjam($bukuId)
    {
        $user = auth()->user();

        $sedangPinjam = Peminjaman::where('user_id', $user->id)
            ->where('buku_id', $bukuId)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        if ($sedangPinjam) {
            return redirect()->back()->with('status', 'Anda sudah mengajukan atau sedang meminjam buku ini.');
        }

        $anggotaId = Anggota::first()?->id ?? 1;

        Peminjaman::create([
            'user_id' => $user->id,
            'anggota_id' => $anggotaId,
            'buku_id' => $bukuId,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => null,
            'status' => 'menunggu',
        ]);

        return redirect()->route('siswa.riwayat')->with('status', 'Pengajuan peminjaman berhasil dikirim! Menunggu persetujuan admin atau petugas.');
    }
}
