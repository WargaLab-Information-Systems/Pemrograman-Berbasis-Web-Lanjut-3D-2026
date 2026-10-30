<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;

class RekapController extends Controller
{
    public function bukuPerKategori(): JsonResponse
    {
        $data = Kategori::withCount('buku')->get()->map(fn ($k) => [
            'kategori_id' => $k->id,
            'kategori'    => $k->nama,
            'jumlah_buku' => $k->buku_count,
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function peminjamanPerBuku(): JsonResponse
    {
        $data = Buku::withCount('peminjaman')->get()->map(fn ($b) => [
            'buku_id'          => $b->id,
            'judul'            => $b->judul,
            'total_peminjaman' => $b->peminjaman_count,
        ]);

        return response()->json(['data' => $data], 200);
    }
}