<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // GET /api/buku (Index with Search, Filter, Eager Loading, Pagination, withCount)
    public function index(Request $request): JsonResponse
    {
        $query = Buku::with('kategori')
            ->withCount('peminjamans')
            ->latest();

        // Filter pencarian berdasarkan judul atau penulis
        if ($request->filled('cari')) {
            $keyword = $request->cari;
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('penulis', 'like', "%{$keyword}%");
            });
        }

        // Filter berdasarkan kategori_id
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter berdasarkan tahun_terbit
        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        $bukus = $query->paginate(10)->withQueryString();

        return response()->json($bukus, 200);
    }

    //  POST /api/buku (Store)
    public function store(StoreBukuRequest $request): JsonResponse
    {
        $buku = Buku::create($request->validated());

        return response()->json([
            'message' => 'Buku berhasil ditambahkan.',
            'data'    => $buku->load('kategori'),
        ], 201);
    }

    // GET /api/buku/{id} (Show)
    public function show(string $id): JsonResponse
    {
        $buku = Buku::with(['kategori', 'peminjamans'])->find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'not found',
            ], 404);
        }

        return response()->json([
            'data' => $buku,
        ], 200);
    }

    // PUT /api/buku/{id} (Update)
    public function update(UpdateBukuRequest $request, string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'not found',
            ], 404);
        }

        $buku->update($request->validated());

        return response()->json([
            'message' => 'Buku berhasil diperbarui.',
            'data'    => $buku->load('kategori'),
        ], 200);
    }

    // DELETE /api/buku/{id} (Destroy)
    public function destroy(string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'not found',
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus',
        ], 200);
    }

    // Rekap Jumlah Buku per Kategori
    public function rekapBukuPerKategori(): JsonResponse
    {
        $rekap = Kategori::withCount('bukus')->get(['id', 'nama_kategori']);

        return response()->json([
            'data' => $rekap,
        ], 200);
    }

    // Rekap Total Peminjaman per Buku
    public function rekapPeminjamanPerBuku(): JsonResponse
    {
        $rekap = Buku::withCount('peminjamans')
            ->select('id', 'judul', 'penulis')
            ->get();

        return response()->json([
            'data' => $rekap,
        ], 200);
    }
}