<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BukuController extends Controller
{
    // no 1: menampilkan buku melalui API resource
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

        $data = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return BukuResource::collection($data);
    }

    // no 2: menambahkan buku
    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|min:3|max:100',
            'tahun_terbit' => 'required|integer|between:1900,2026',
        ]);

        $buku = Buku::create($data);

        return (new BukuResource($buku->load('kategori')))
            ->additional([
                'message' => 'Buku berhasil ditambahkan.',
            ])
            ->response()
            ->setStatusCode(201);
    }

    // Menampilkan detail buku
    public function show(string $id)
    {
        $buku = Buku::with('kategori')
            ->withCount('peminjamans')
            ->find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        return new BukuResource($buku);
    }

    // no 3: mengubah buku
    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        $data = $request->validate([
            'kategori_id' => 'sometimes|required|exists:kategoris,id',
            'judul' => 'sometimes|required|string|min:3|max:150',
            'penulis' => 'sometimes|required|string|min:3|max:100',
            'tahun_terbit' => 'sometimes|required|integer|between:1900,2026',
        ]);

        $buku->update($data);

        return (new BukuResource($buku->fresh()->load('kategori')))
            ->additional([
                'message' => 'Buku berhasil diperbarui.',
            ]);
    }

    // no 4: menghapus buku
    public function destroy(string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        if ($buku->peminjamans()->exists()) {
            return response()->json([
                'message' => 'Buku tidak dapat dihapus karena masih memiliki data peminjaman.',
            ], 409);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus.',
        ], 200);
    }
}