<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;

class RekapController extends Controller
{
    public function bukuPerKategori()
    {
        $data = Kategori::withCount('bukus')->get();

        return response()->json([
            'message' => 'Rekap buku per kategori berhasil diambil',
            'data' => $data,
        ], 200);
    }

    public function peminjamanPerBuku()
    {
        $data = Buku::withCount('peminjamans')
            ->with('kategori')
            ->orderBy('judul')
            ->get();

        return response()->json([
            'message' => 'Rekap peminjaman per buku berhasil diambil',
            'data' => $data,
        ], 200);
    }
}
