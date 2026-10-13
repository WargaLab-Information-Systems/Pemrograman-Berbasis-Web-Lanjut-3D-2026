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
        $buku = Buku::with('kategori')
            ->withCount('peminjaman')
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->where('judul', 'like', '%' . $request->cari . '%')
                      ->orWhere('penulis', 'like', '%' . $request->cari . '%');
            })
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori_id', $request->kategori))
            ->when($request->filled('tahun'), fn ($q) => $q->where('tahun_terbit', $request->tahun))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return response()->json($buku, 200);
    }

    /**
     * Menyimpan data buku baru (Store).
     */
    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());

        return response()->json([
            'message' => 'Buku berhasil ditambahkan.',
            'data' => $buku->load('kategori')
        ], 201);
    }

    /**
     * Menampilkan detail satu buku (Show).
     */
    public function show($id)
    {
        $buku = Buku::with(['kategori', 'peminjaman'])->find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Not Found'
            ], 404);
        }

        return response()->json($buku, 200);
    }

    /**
     * Memperbarui data buku (Update).
     */
    public function update(UpdateBukuRequest $request, $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Not Found'
            ], 404);
        }

        $buku->update($request->validated());

        return response()->json([
            'message' => 'Buku berhasil diperbarui.',
            'data' => $buku->load('kategori')
        ], 200);
    }

    /**
     * Menghapus data buku (Destroy).
     */
    public function destroy($id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Not Found'
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus.'
        ], 200);
    }
}