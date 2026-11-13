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

        $buku = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return BukuResource::collection($buku);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());

        return response()->json(
            $buku->load('kategori'),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buku = Buku::with('kategori', 'peminjamans')
            ->findOrFail($id);

        return response()->json($buku, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBukuRequest $request, string $id)
    {
        $buku = Buku::findOrFail($id);

        $buku->update($request->validated());

        return response()->json(
            $buku->load('kategori'),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::findOrFail($id);
        if ($buku->peminjamans()->exists()) {
            return response()->json([
                'message' => 'Buku tidak dapat dihapus karena masih memiliki riwayat peminjaman.'
            ], 409);
        }
        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }
}