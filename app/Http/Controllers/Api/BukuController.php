<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Http\Resources\BukuResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BukuController extends Controller
{
    // GET /api/buku (Menggunakan BukuResource)
    public function index(Request $request): JsonResponse
    {
        $query = Buku::with('kategori')
            ->withCount('peminjamans')
            ->latest();

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

        $buku = $query->get();

        return response()->json([
            'status' => 'success',
            'data'   => BukuResource::collection($buku)
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kategori_id'  => 'required|exists:kategoris,id',
            'judul'        => 'required|string|min:3|max:150',
            'penulis'      => 'required|string|min:3|max:100',
            'tahun_terbit' => 'required|integer|between:1900,' . date('Y'),
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'string'   => 'Kolom :attribute harus berupa teks.',
            'integer'  => 'Kolom :attribute harus berupa angka.',
            'min'      => 'Kolom :attribute minimal :min karakter.',
            'max'      => 'Kolom :attribute maksimal :max karakter.',
            'between'  => 'Kolom :attribute harus antara :min sampai :max.',
            'exists'   => 'Kategori yang dipilih tidak valid.',
        ]);

        $buku = Buku::create($validated);
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data'    => new BukuResource($buku)
        ], 201);
    }


    public function show($id): JsonResponse
    {
        $buku = Buku::with(['kategori', 'peminjamans'])->find($id);

        if (!$buku) {
            return response()->json(['message' => 'Data buku tidak ditemukan'], 404);
        }

        return response()->json([
            'data' => new BukuResource($buku)
        ], 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'Data buku tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'kategori_id'  => 'required|exists:kategoris,id',
            'judul'        => 'required|string|min:3|max:150',
            'penulis'      => 'required|string|min:3|max:100,',
            'tahun_terbit' => 'required|integer|between:1900,' . date('Y'),
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'string'   => 'Kolom :attribute harus berupa teks.',
            'integer'  => 'Kolom :attribute harus berupa angka.',
            'min'      => 'Kolom :attribute minimal :min karakter.',
            'max'      => 'Kolom :attribute maksimal :max karakter.',
            'between'  => 'Kolom :attribute harus antara :min sampai :max.',
            'exists'   => 'Kategori yang dipilih tidak valid.',
        ]);

        $buku->update($validated);
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data'    => new BukuResource($buku)
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'Data buku tidak ditemukan'], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'buku berhasil dihapus'
        ], 200);
    }
}