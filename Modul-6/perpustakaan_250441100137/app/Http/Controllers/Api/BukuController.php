<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Http\Resources\BukuResource;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check() && !auth()->user()->can('Lihat daftar buku')) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $query = Buku::with('kategori')->withCount('peminjaman');

        if ($request->has('cari')) {
            $cari = $request->cari;
            $query->where(function($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        if ($request->has('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->has('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        // Menggunakan get() agar langsung berupa collection data untuk JavaScript
        $buku = $query->latest()->get();

        return BukuResource::collection($buku)->additional([
            'message' => 'Daftar buku berhasil diambil'
        ]);
    }

    public function store(StoreBukuRequest $request)
    {
        if (!auth()->check() || !auth()->user()->can('Kelola data buku')) {
            return response()->json(['message' => 'Unauthorized action. Anda tidak memiliki izin untuk mengelola data buku.'], 403);
        }

        $buku = Buku::create($request->validated());

        return (new BukuResource($buku->load('kategori')))
            ->additional(['message' => 'Buku berhasil ditambahkan'])
            ->response()
            ->setStatusCode(201);
    }

    public function show($id)
    {
        if (auth()->check() && !auth()->user()->can('Lihat daftar buku')) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $buku = Buku::with('kategori')->withCount('peminjaman')->find($id);

        if (!$buku) {
            return response()->json(['message' => 'Data buku tidak ditemukan'], 404);
        }

        return (new BukuResource($buku))
            ->additional(['message' => 'Detail buku berhasil diambil']);
    }

    public function update(UpdateBukuRequest $request, $id)
    {
        if (!auth()->check() || !auth()->user()->can('Kelola data buku')) {
            return response()->json(['message' => 'Unauthorized action. Anda tidak memiliki izin untuk mengelola data buku.'], 403);
        }

        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'Data buku tidak ditemukan'], 404);
        }

        $buku->update($request->validated());

        return (new BukuResource($buku->load('kategori')))
            ->additional(['message' => 'Buku berhasil diperbarui']);
    }

    public function destroy($id)
    {
        if (!auth()->check() || !auth()->user()->can('Kelola data buku')) {
            return response()->json(['message' => 'Unauthorized action. Anda tidak memiliki izin untuk mengelola data buku.'], 403);
        }

        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'Data buku tidak ditemukan'], 404);
        }

        $buku->delete();

        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }
}
