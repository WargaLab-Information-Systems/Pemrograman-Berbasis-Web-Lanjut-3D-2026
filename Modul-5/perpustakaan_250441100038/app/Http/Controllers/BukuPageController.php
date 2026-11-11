<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;

/** Halaman web detail buku (daftar buku memakai view + API yang sudah ada). */
class BukuPageController extends Controller
{
    public function show($id)
    {
        $model = Buku::with('kategori')->find($id);

        $buku = $model ? [
            'id' => $model->id,
            'judul' => $model->judul,
            'penulis' => $model->penulis,
            'tahun_terbit' => $model->tahun_terbit,
            'kategori' => $model->kategori->nama ?? '-',
        ] : null;

        $sudahAktif = $model
            ? Peminjaman::where('user_id', auth()->id())
                ->where('buku_id', $model->id)
                ->whereIn('status', [Peminjaman::STATUS_DIAJUKAN, Peminjaman::STATUS_DIPINJAM])
                ->exists()
            : false;

        return response()->view('buku.show', compact('buku', 'id', 'sudahAktif'), $model ? 200 : 404);
    }
}
