<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Http\Resources\BukuResource;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Mengambil semua buku
    public function apiIndex()
    {
        $buku = Buku::with('kategori')
            ->orderBy('id', 'desc')
            ->get();

        return BukuResource::collection($buku);
    }

    // Menambahkan buku
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1000|max:2100',
        ]);

        $buku = Buku::create($validated);

        return (new BukuResource($buku->load('kategori')))
            ->response()
            ->setStatusCode(201);
    }

    // Mengubah buku
    public function apiUpdate(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1000|max:2100',
        ]);

        $buku->update($validated);

        return new BukuResource($buku->load('kategori'));
    }

    // Menghapus buku
    public function apiDestroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus.'
        ]);
    }
}