<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class RekapController extends Controller
{
    public function bukuPerKategori()
    {
        $rekap = Kategori::withCount('bukus')->get();

        return response()->json($rekap, 200);
    }
    public function peminjamanPerBuku()
    {
        $rekap = \App\Models\Buku::withCount('peminjamans')
            ->with('kategori')
            ->get();

        return response()->json($rekap, 200);
    }
}
