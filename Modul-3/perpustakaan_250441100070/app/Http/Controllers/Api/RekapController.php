<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;

class RekapController extends Controller
{
    /**
     * GET /api/rekap/buku-per-kategori
     * Jumlah buku per kategori (withCount).
     */
    public function bukuPerKategori(): JsonResponse
    {
        $data = Kategori::select('id', 'nama')
            ->withCount('bukus')
            ->get()
            ->map(fn ($kategori) => [
                'kategori_id' => $kategori->id,
                'kategori'    => $kategori->nama,
                'jumlah_buku' => $kategori->bukus_count,
            ]);

        return response()->json(['data' => $data], 200);
    }

    /**
     * GET /api/rekap/peminjaman-per-buku
     * Total peminjaman per buku (withCount).
     */
    public function peminjamanPerBuku(): JsonResponse
    {
        $data = Buku::select('id', 'judul')
            ->withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->get()
            ->map(fn ($buku) => [
                'buku_id'           => $buku->id,
                'judul'             => $buku->judul,
                'total_peminjaman'  => $buku->peminjamans_count,
            ]);

        return response()->json(['data' => $data], 200);
    }
}
