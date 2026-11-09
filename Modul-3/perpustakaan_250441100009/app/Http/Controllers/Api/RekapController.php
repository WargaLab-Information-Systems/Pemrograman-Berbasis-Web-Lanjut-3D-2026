<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    /**
     * GET /api/rekap/buku-per-kategori
     */
    public function bukuPerKategori(): JsonResponse
    {
        $rekap = DB::table('kategoris')
            ->leftJoin('bukus', 'bukus.kategori_id', '=', 'kategoris.id')
            ->select(
                'kategoris.id',
                'kategoris.nama as kategori',
                DB::raw('COUNT(bukus.id) as jumlah_buku')
            )
            ->groupBy('kategoris.id', 'kategoris.nama')
            ->orderByDesc('jumlah_buku')
            ->get();

        return response()->json([
            'data' => $rekap,
            'message' => 'Rekap jumlah buku per kategori',
        ]);
    }

    /**
     * GET /api/rekap/peminjaman-per-buku
     */
    public function peminjamanPerBuku(): JsonResponse
    {
        $rekap = Buku::select('id', 'judul', 'penulis')
            ->withCount('peminjamans as total_peminjaman')
            ->with('kategoriRelasi:id,nama')
            ->orderByDesc('total_peminjaman')
            ->get()
            ->map(fn ($buku) => [
                'id' => $buku->id,
                'judul' => $buku->judul,
                'penulis' => $buku->penulis,
                'kategori' => $buku->kategoriRelasi?->nama ?? '-',
                'total_peminjaman' => $buku->total_peminjaman,
            ]);

        return response()->json([
            'data' => $rekap,
            'message' => 'Rekap total peminjaman per buku',
        ]);
    }
}
