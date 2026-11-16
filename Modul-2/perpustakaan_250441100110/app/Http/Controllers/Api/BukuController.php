<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BukuController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $buku = Buku::with('kategori')
            ->withCount('peminjamans')
            ->when($request->filled('cari'), function ($query) use ($request) {
                $cari = $request->cari;
                $query->where(function ($q) use ($cari) {
                    $q->where('judul', 'like', '%' . $cari . '%')
                      ->orWhere('penulis', 'like', '%' . $cari . '%');
                });
            })
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori_id', $request->kategori))
            ->when($request->filled('tahun'), fn ($q) => $q->where('tahun_terbit', $request->tahun))
            ->latest()
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'data' => $buku->items(),
            'meta' => [
                'current_page' => $buku->currentPage(),
                'per_page'     => $buku->perPage(),
                'total'        => $buku->total(),
                'last_page'    => $buku->lastPage(),
            ],
            'links' => [
                'next' => $buku->nextPageUrl(),
                'prev' => $buku->previousPageUrl(),
            ],
        ], 200);
    }


    public function store(StoreBukuRequest $request): JsonResponse
    {
        $buku = Buku::create($request->validated());
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data'    => $buku,
        ], 201);
    }


    public function show(string $id): JsonResponse
    {
        $buku = Buku::with(['kategori', 'peminjamans'])->find($id);

        if (! $buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        return response()->json(['data' => $buku], 200);
    }


    public function update(UpdateBukuRequest $request, string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (! $buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        $buku->update($request->validated());
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data'    => $buku,
        ], 200);
    }


    public function destroy(string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (! $buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        if ($buku->peminjamans()->exists()) {
            return response()->json([
                'message' => 'Buku tidak bisa dihapus karena masih punya data peminjaman',
            ], 409);
        }

        $buku->delete();

        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }
}