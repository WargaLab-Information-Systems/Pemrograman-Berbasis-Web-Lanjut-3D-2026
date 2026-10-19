<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Buku::with('ketgori')->withCount('peminjamans')->latest();
        if ($request->has('cari')) {
            $query->where('judul', 'like', '%' . $request->cari . '%')
                  ->orWhere('penulis', 'like', '%' . $request->cari . '%');
        }
        if ($request->has('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }
        if ($request->has('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }
        $buku = $query->paginate(10)->appends($request->query());
        return response()->json($buku, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());
        return response()->json(['message' => 'Buku berhasil ditambahkan', 'data' => $buku], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $buku = Buku::with(['kategori', 'peminjamans'])->find($id);
        
        if (!$buku) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($buku, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBukuRequest $request, $id)
    {
        $buku = Buku::find($id);
        if (!$buku) return response()->json(['message' => 'Not found'], 404);

        $buku->update($request->validated());
        return response()->json(['message' => 'Buku berhasil diperbarui', 'data' => $buku], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $buku = Buku::find($id);
        if (!$buku) return response()->json(['message' => 'Not found'], 404);

        $buku->delete();
        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }
    
    public function rekapBukuPerKategori()
    {
        $rekap = Kategori::withCount('bukus')->get();
        return response()->json($rekap, 200);
    }

    public function rekapPeminjamanPerBuku()
    {
        $rekap = Buku::withCount('peminjamans')->get();
        return response()->json($rekap, 200);
    }
}
