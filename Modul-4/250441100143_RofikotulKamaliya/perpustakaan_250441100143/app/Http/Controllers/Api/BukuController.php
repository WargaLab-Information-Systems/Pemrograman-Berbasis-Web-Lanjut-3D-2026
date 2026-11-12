<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Http\Resources\BukuResource;
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
            'data' => BukuResource::collection($bukus->getCollection()),
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
    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1901|max:2155',
        ]);
        $buku = Buku::create($data);
        return response()->json([
            'message' => 'Buku berhasil ditambahkan.',
            'data' => new BukuResource($buku->load('kategori')),
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
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'kategori_id' => 'sometimes|exists:kategoris,id',
            'judul' => 'sometimes|string|max:255',
            'penulis' => 'sometimes|string|max:255',
            'tahun_terbit' => 'sometimes|integer|min:1901|max:2155',
        ]);
        
        $buku = Buku::findOrFail($id);
        $buku->update($data);
        return response()->json([
            'message' => 'Buku berhasil diperbarui.',
            'data' => new BukuResource($buku->load('kategori')),
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
