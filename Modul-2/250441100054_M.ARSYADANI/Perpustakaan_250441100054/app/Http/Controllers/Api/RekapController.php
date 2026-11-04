<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function bukuPerKategori(): JsonResponse
    {
        $data = DB::table('kategoris')
            ->leftJoin('bukus', 'bukus.kategori_id', '=', 'kategoris.id')
            ->select('kategoris.id', 'kategoris.nama', DB::raw('COUNT(bukus.id) as jumlah_buku'))
            ->groupBy('kategoris.id', 'kategoris.nama')
            ->orderByDesc('jumlah_buku')
            ->get();

        return response()->json(['data' => $data], 200);
    }

    public function peminjamanPerBuku(): JsonResponse
    {
        $data = Buku::select('id', 'judul')
            ->withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->get();

        return response()->json(['data' => $data], 200);
    }
}