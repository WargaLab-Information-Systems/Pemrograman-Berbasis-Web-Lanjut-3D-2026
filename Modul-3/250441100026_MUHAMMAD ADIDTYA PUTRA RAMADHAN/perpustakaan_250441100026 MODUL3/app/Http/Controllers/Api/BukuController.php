<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;

class BukuController extends Controller
{
    // 1. GET /api/buku (Menampilkan Semua + Filter + Search)
    public function index(Request $request)
    {
        // Query dasar dengan relasi dan jumlah peminjaman (Eager Loading)
        $query = Buku::with('kategori')->withCount('peminjamans');

        // Filter: Cari Judul/Penulis
        if ($request->has('cari')) {
            $query->where('judul', 'like', '%' . $request->cari . '%')
                  ->orWhere('penulis', 'like', '%' . $request->cari . '%');
        }

        // Filter: Kategori
        if ($request->has('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter: Tahun
        if ($request->has('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        // Urutkan dari terbaru, paginate 10, dan bawa query string (biar url filter tidak hilang saat ganti halaman)
        $buku = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return response()->json($buku, 200);
    }

    // 2. POST /api/buku (Menyimpan Data Baru)
    public function store(StoreBukuRequest $request)
    {
        // $request->validated() adalah fitur Mass Assignment Protection. Hanya data yang lolos validasi yang diambil.
        $buku = Buku::create($request->validated());
        
        return response()->json($buku, 201); // 201 = Created
    }

    // 3. GET /api/buku/{id} (Melihat Detail 1 Buku)
    public function show($id)
    {
        $buku = Buku::with(['kategori', 'peminjamans'])->find($id);

        if (!$buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        return response()->json($buku, 200);
    }

    // 4. PUT /api/buku/{id} (Mengubah Data)
    public function update(UpdateBukuRequest $request, $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        $buku->update($request->validated());

        return response()->json($buku, 200);
    }

    // 5. DELETE /api/buku/{id} (Menghapus Data)
    public function destroy($id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        $buku->delete();

        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }
}