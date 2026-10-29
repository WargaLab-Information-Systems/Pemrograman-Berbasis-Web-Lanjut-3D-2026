<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
        $bukus = $query->orderByDesc('created_at')
        ->paginate(10)
        ->withQueryString();

        return response()->json([
            'data' => $bukus->items(),
            'meta' => [
                'current_page' => $bukus->currentPage(),
                'last_page' => $bukus->lastPage(),
                'per_page' => $bukus->perPage(),
                'total' => $bukus->total(),
            ],
            'links' => [
                'first' => $bukus->url(1),
                'last' => $bukus->url($bukus->lastPage()),
                'prev' => $bukus->previousPageUrl(),
                'next' => $bukus->nextPageUrl(),
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());
        return response()->json([
            'message' => 'Buku berhasil ditambahkan.',
            'data' => $buku->load('kategori'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buku = Buku::with([
            'kategori',
            'peminjamans',
        ])->withCount('peminjamans')->find($id);
        
        if (!$buku) {
            return response()->json([
                'message' => 'not found.',
            ], 404);
        }

        return response()->json([
            'data' => $buku,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBukuRequest $request, string $id)
    {
        $buku = Buku::find($id);
        if (!$buku) {
            return response()->json([
                'message' => 'not found.',
            ], 404);
        }

        $buku->update($request->validated());
        return response()->json([
            'message' => 'Buku berhasil diperbarui.',
            'data' => $buku->load('kategori')
            ->loadCount('peminjamans'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::find($id);
        if (!$buku) {
            return response()->json([
                'message' => 'not found.',
            ], 404);
        }

        $buku->delete();
        return response()->json([
            'message' => 'Buku berhasil dihapus.',
        ], 200);
    }
}
