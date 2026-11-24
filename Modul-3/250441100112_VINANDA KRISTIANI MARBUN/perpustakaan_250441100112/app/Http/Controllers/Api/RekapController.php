<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
  
    public function bukuPerKategori()
    {
        $data = Kategori::withCount('bukus')
            ->get()
            ->map(function ($kategori) {
                return [
                    'kategori_id' => $kategori->id,
                    'kategori' => $kategori->nama,
                    'jumlah_buku' => $kategori->bukus_count,
                ];
            });

        return response()->json([
            'data' => $data,
        ], 200);
    }

  
    public function peminjamanPerBuku()
    {
        $data = Buku::withCount('peminjamans')
            ->get()
            ->map(function ($buku) {
                return [
                    'buku_id' => $buku->id,
                    'judul' => $buku->judul,
                    'total_peminjaman' => $buku->peminjamans_count,
                ];
            });

        return response()->json([
            'data' => $data,
        ], 200);
    }
}
