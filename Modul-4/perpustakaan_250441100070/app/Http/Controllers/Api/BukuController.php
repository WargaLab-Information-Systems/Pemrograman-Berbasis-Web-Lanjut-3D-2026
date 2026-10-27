<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BukuController extends Controller
{
    /**
     * GET /api/buku
     * Query parameter: cari, kategori, tahun, page (10 data per halaman).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Buku::with('kategori')
            ->withCount('peminjamans');

        if ($request->filled('cari')) {
            $cari = $request->query('cari');
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->query('kategori'));
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->query('tahun'));
        }

        $bukus = $query->latest()
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        // Status 200. Struktur: data (hasil mapping BukuResource), links, dan meta pagination.
        return BukuResource::collection($bukus);
    }

    /**
     * POST /api/buku
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validasi($request, new StoreBukuRequest());

        $buku = Buku::create($validated);
        $buku->load('kategori')->loadCount('peminjamans');

        return (new BukuResource($buku))
            ->additional(['message' => 'Buku berhasil ditambahkan'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/buku/{id}
     */
    public function show(string $id): JsonResponse|BukuResource
    {
        $buku = Buku::with(['kategori', 'peminjamans'])
            ->withCount('peminjamans')
            ->find($id);

        if (! $buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        return new BukuResource($buku);
    }

    /**
     * PUT /api/buku/{id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (! $buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        $validated = $this->validasi($request, new UpdateBukuRequest());

        $buku->update($validated);
        $buku->load('kategori')->loadCount('peminjamans');

        return (new BukuResource($buku))
            ->additional(['message' => 'Buku berhasil diperbarui'])
            ->response()
            ->setStatusCode(200);
    }

    /**
     * DELETE /api/buku/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (! $buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        $buku->delete();

        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }

    /**
     * Validasi memakai $request->validate() (ketentuan Modul 4).
     * Rule, pesan, dan nama atribut diambil dari Form Request Modul 3
     * supaya aturan validasi hanya ditulis di satu tempat.
     */
    private function validasi(Request $request, FormRequest $aturan): array
    {
        return $request->validate(
            $aturan->rules(),
            $aturan->messages(),
            $aturan->attributes(),
        );
    }
}
