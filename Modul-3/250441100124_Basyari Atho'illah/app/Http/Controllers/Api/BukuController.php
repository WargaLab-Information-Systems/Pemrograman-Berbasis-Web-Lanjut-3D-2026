<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku beserta relasi kategori dan pagination.
     * Mendukung query parameter: 'cari', 'kategori', 'tahun', 'page'.
     *
     * GET /api/buku
     */
    public function index(Request $request): JsonResponse
    {
        $query = Buku::with('kategoriRelasi')
            ->withCount('peminjamans')
            ->latest('id');

        // 1. Parameter 'cari': cari berdasarkan judul atau penulis (menggunakan LIKE)
        if ($request->filled('cari')) {
            $cari = $request->input('cari');
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        // 2. Parameter 'kategori': filter berdasarkan kategori_id
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->input('kategori'));
        }

        // 3. Parameter 'tahun': filter berdasarkan tahun_terbit
        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->input('tahun'));
        }

        // 4. Pagination (10 data per halaman) & sertakan query string saat berganti halaman
        $books = $query->paginate(10)->withQueryString();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar buku berhasil diambil.',
            'data' => $books->items(),
            'meta' => [
                'current_page' => $books->currentPage(),
                'from' => $books->firstItem(),
                'last_page' => $books->lastPage(),
                'per_page' => $books->perPage(),
                'to' => $books->lastItem(),
                'total' => $books->total(),
            ],
            'links' => [
                'first' => $books->url(1),
                'last' => $books->url($books->lastPage()),
                'prev' => $books->previousPageUrl(),
                'next' => $books->nextPageUrl(),
            ],
        ], 200);
    }

    /**
     * Menyimpan data buku baru ke database.
     *
     * POST /api/buku
     */
    public function store(StoreBukuRequest $request): JsonResponse
    {
        // Hanya menggunakan data yang telah tervalidasi untuk proteksi Mass Assignment
        $book = Buku::create($request->validated());

        // Memuat relasi kategori dan hitungan peminjaman
        $book->load('kategoriRelasi')->loadCount('peminjamans');

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil ditambahkan.',
            'data' => $book,
        ], 201);
    }

    /**
     * Menampilkan detail satu buku berdasarkan ID.
     *
     * GET /api/buku/{id}
     */
    public function show(int|string $id): JsonResponse
    {
        $book = Buku::with(['kategoriRelasi', 'peminjamans.anggota'])
            ->withCount('peminjamans')
            ->find($id);

        if (! $book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail buku berhasil diambil.',
            'data' => $book,
        ], 200);
    }

    /**
     * Memperbarui data buku berdasarkan ID.
     *
     * PUT/PATCH /api/buku/{id}
     */
    public function update(UpdateBukuRequest $request, int|string $id): JsonResponse
    {
        $book = Buku::find($id);

        if (! $book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        // Update hanya dengan field yang tervalidasi
        $book->update($request->validated());
        $book->load('kategoriRelasi')->loadCount('peminjamans');

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil diperbarui.',
            'data' => $book,
        ], 200);
    }

    /**
     * Menghapus data buku dari database berdasarkan ID.
     *
     * DELETE /api/buku/{id}
     */
    public function destroy(int|string $id): JsonResponse
    {
        $book = Buku::find($id);

        if (! $book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        $book->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil dihapus.',
        ], 200);
    }
}
