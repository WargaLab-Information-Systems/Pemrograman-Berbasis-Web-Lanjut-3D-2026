<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PeminjamanController extends Controller
{
    public function store(Request $request, Buku $buku)
    {
        $user = $request->user();

        $anggota = $user->anggota;

        if (!$anggota) {
            abort(403, 'Data anggota tidak ditemukan.');
        }

        Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => null,
            'status' => 'menunggu',
        ]);

        return redirect()
            ->route('buku.index')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim dan sedang menunggu persetujuan.');
    }
    // modul 6
    public function show($id)
    {
        // Celah A4: Mengambil data tanpa memeriksa apakah peminjaman ini milik user yang login
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);
        
        // soal nomor 4 melakukan otorisasi view policy
        Gate::authorize('view', $peminjaman);

        return view('peminjaman.show', compact('peminjaman'));
    }
    // end modul 6
}
