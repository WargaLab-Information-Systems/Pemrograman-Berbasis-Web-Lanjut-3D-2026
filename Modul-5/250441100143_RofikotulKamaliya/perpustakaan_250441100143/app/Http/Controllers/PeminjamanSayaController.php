<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PeminjamanSayaController extends Controller
{
    /**
     * Status peminjaman milik sendiri (yang masih berjalan).
     */
    public function status(Request $request)
    {
        $anggota = $request->user()->ensureAnggota();

        $peminjamans = Peminjaman::with('buku.kategori')
            ->where('anggota_id', $anggota->id)
            ->whereIn('status', [Peminjaman::STATUS_DIAJUKAN, Peminjaman::STATUS_DIPINJAM])
            ->latest('id')
            ->get();

        return view('peminjaman.status', compact('peminjamans'));
    }

    /**
     * Riwayat seluruh peminjaman milik sendiri.
     * Selalu difilter berdasarkan user yang sedang login, bukan dari parameter URL.
     */
    public function riwayat(Request $request)
    {
        $anggota = $request->user()->ensureAnggota();

        $peminjamans = Peminjaman::with('buku.kategori')
            ->where('anggota_id', $anggota->id)
            ->latest('id')
            ->get();

        return view('peminjaman.riwayat', compact('peminjamans'));
    }

    /**
     * Detail satu peminjaman. Dicek lewat PeminjamanPolicy:
     * siswa hanya boleh melihat miliknya sendiri, selain itu 403.
     */
    public function show(Peminjaman $peminjaman)
    {
        Gate::authorize('view', $peminjaman);

        $peminjaman->load(['buku.kategori', 'anggota']);

        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Ajukan peminjaman buku. Status awal: diajukan (menunggu persetujuan petugas/admin).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'buku_id' => ['required', 'integer', 'exists:bukus,id'],
        ]);

        $anggota = $request->user()->ensureAnggota();

        $sudahAda = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $data['buku_id'])
            ->whereIn('status', [Peminjaman::STATUS_DIAJUKAN, Peminjaman::STATUS_DIPINJAM])
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Anda sudah mengajukan atau sedang meminjam buku ini.');
        }

        Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $data['buku_id'],
            'tanggal_pinjam' => now()->toDateString(),
            'status' => Peminjaman::STATUS_DIAJUKAN,
        ]);

        return redirect()->route('peminjaman.status')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim. Tunggu persetujuan petugas.');
    }

    /**
     * Batalkan pengajuan milik sendiri yang belum diproses.
     */
    public function batal(Peminjaman $peminjaman)
    {
        Gate::authorize('batalkan', $peminjaman);

        $peminjaman->delete();

        return back()->with('success', 'Pengajuan peminjaman dibatalkan.');
    }
}
