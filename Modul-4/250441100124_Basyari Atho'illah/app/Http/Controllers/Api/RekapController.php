<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;

class RekapController extends Controller
{
    /**
     * Menampilkan rekapitulasi jumlah buku per kategori.
     * Menggunakan withCount('bukus') untuk menghitung relasi secara efisien.
     *
     * GET /api/rekap/buku-per-kategori
     */
    public function bukuPerKategori(): JsonResponse
    {
        $rekap = Kategori::withCount('bukus')
            ->get(['id', 'nama'])
            ->map(fn ($kategori) => [
                'kategori_id' => $kategori->id,
                'nama_kategori' => $kategori->nama,
                'total_buku' => $kategori->bukus_count,
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Rekap jumlah buku per kategori berhasil diambil.',
            'data' => $rekap,
        ], 200);
    }

    /**
     * Menampilkan rekapitulasi total peminjaman per buku.
     * Menggunakan withCount('peminjamans') untuk menghitung peminjaman per buku.
     *
     * GET /api/rekap/peminjaman-per-buku
     */
    public function peminjamanPerBuku(): JsonResponse
    {
        $rekap = Buku::with('kategoriRelasi:id,nama')
            ->withCount('peminjamans')
            ->get(['id', 'kategori_id', 'judul', 'penulis', 'tahun_terbit'])
            ->map(fn ($buku) => [
                'buku_id' => $buku->id,
                'judul' => $buku->judul,
                'penulis' => $buku->penulis,
                'kategori' => $buku->kategoriRelasi?->nama ?? '-',
                'total_peminjaman' => $buku->peminjamans_count,
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Rekap total peminjaman per buku berhasil diambil.',
            'data' => $rekap,
        ], 200);
    }
}
