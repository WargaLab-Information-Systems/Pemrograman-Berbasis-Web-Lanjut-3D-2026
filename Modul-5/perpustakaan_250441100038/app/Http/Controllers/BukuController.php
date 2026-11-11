<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get(); 
        
        return response()->json([
            'message' => 'Berhasil mengambil data',
            'data' => $buku
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|integer',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1901|max:2026',
        ]);

        $buku = Buku::create($validated);

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data' => $buku
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $validated = $request->validate([
            'kategori_id' => 'required|integer',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1901|max:2026',
        ]);

        $buku->update($validated);

        return response()->json([
            'message' => 'Buku berhasil diubah',
            'data' => $buku
        ], 200);
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return response()->json([
            'message' => 'Detail buku',
            'data' => $buku
        ], 200);
    }
}