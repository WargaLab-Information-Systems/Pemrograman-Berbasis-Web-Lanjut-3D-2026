<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori')
            ->withCount('peminjamans');

        // Pencarian berdasarkan judul atau penulis
        if ($request->filled('cari')) {
            $cari = $request->query('cari');

            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where(
                'kategori_id',
                $request->query('kategori')
            );
        }

        // Filter berdasarkan tahun terbit
        if ($request->filled('tahun')) {
            $query->where(
                'tahun_terbit',
                $request->query('tahun')
            );
        }

        // Buku terbaru lebih dahulu
        $query->orderByDesc('id');

        // Pagination 10 data per halaman
        $bukus = $query->paginate(10)->withQueryString();

        return response()->json($bukus, 200);
    }

    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());

        $buku->load('kategori');
        $buku->loadCount('peminjamans');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data' => $buku,
        ], 201);
    }

    public function show(string $id)
    {
        $buku = Buku::with([
            'kategori',
            'peminjamans',
        ])->withCount('peminjamans')->findOrFail($id);

        return response()->json([
            'data' => $buku,
        ], 200);
    }

    public function update(
        UpdateBukuRequest $request,
        string $id
    ) {
        $buku = Buku::findOrFail($id);

        $buku->update($request->validated());

        $buku->load('kategori');
        $buku->loadCount('peminjamans');

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data' => $buku,
        ], 200);
    }

    public function destroy(string $id)
    {
        $buku = Buku::findOrFail($id);

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus',
        ], 200);
    }
}
