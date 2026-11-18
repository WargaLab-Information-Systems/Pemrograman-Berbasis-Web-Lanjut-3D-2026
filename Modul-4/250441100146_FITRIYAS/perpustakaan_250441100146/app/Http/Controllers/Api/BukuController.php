<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Buku::with('kategori')
            ->withCount('peminjamans');

        if ($request->filled('cari')) {
            $cari = $request->cari;

            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        $buku = $query->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'data' => BukuResource::collection(
                $buku->getCollection()
            ),
            'meta' => [
                'current_page' => $buku->currentPage(),
                'last_page' => $buku->lastPage(),
                'per_page' => $buku->perPage(),
                'total' => $buku->total(),
            ],
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kategori_id' => 'required|integer|exists:kategoris,id',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|min:3|max:100',
            'tahun_terbit' => 'required|integer|between:1900,' . date('Y'),
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.min' => 'Judul buku minimal 3 karakter.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penulis.min' => 'Nama penulis minimal 3 karakter.',
            'tahun_terbit.between' => 'Tahun terbit tidak valid.',
        ]);

        $buku = Buku::create($validated);
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data' => new BukuResource($buku),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $buku = Buku::with(['kategori', 'peminjamans'])
            ->find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'data' => new BukuResource($buku),
        ], 200);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'kategori_id' => 'sometimes|required|integer|exists:kategoris,id',
            'judul' => 'sometimes|required|string|min:3|max:150',
            'penulis' => 'sometimes|required|string|min:3|max:100',
            'tahun_terbit' => 'sometimes|required|integer|between:1900,' . date('Y'),
        ], [
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'judul.min' => 'Judul buku minimal 3 karakter.',
            'penulis.min' => 'Nama penulis minimal 3 karakter.',
            'tahun_terbit.between' => 'Tahun terbit tidak valid.',
        ]);

        $buku->update($validated);
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data' => new BukuResource($buku),
        ], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan',
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus',
        ], 200);
    }
}
