<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;

class RekapController extends Controller
{
    public function bukuPerKategori()
    {
        $data = Kategori::withCount('bukus')->get();

        return response()->json([
            'message' => 'Rekap buku per kategori',
            'data' => $data,
        ], 200);
    }

    public function peminjamanPerBuku()
    {
        $data = Buku::withCount('peminjamans')
            ->with('kategori')
            ->orderByDesc('peminjamans_count')
            ->get();

        return response()->json([
            'message' => 'Rekap peminjaman per buku',
            'data' => $data,
        ], 200);
    }
}
