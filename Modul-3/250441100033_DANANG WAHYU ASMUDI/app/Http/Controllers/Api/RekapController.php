<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;

class RekapController extends Controller
{
    public function rekapBukuPerKategori()
    {
        $rekap = Kategori::withCount('bukus')->get()->map(function ($kategori) {
            return [
                'kategori_id' => $kategori->id,
                'nama'        => $kategori->nama,
                'total_buku'  => $kategori->bukus_count,
            ];
        });

        return response()->json([
            'message' => 'Rekap jumlah buku per kategori',
            'data'    => $rekap
        ], 200);
    }

    public function rekapPeminjamanPerBuku()
    {
        $rekap = Buku::withCount('peminjamans')->get()->map(function ($buku) {
            return [
                'buku_id'          => $buku->id,
                'judul'            => $buku->judul,
                'total_peminjaman' => $buku->peminjamans_count,
            ];
        });

        return response()->json([
            'message' => 'Rekap total peminjaman per buku',
            'data'    => $rekap
        ], 200);
    }
}