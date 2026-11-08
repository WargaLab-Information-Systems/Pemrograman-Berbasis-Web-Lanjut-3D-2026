<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku beserta relasi kategori dan pagination.
     * Menggunakan BukuResource untuk menyajikan data dengan pemetaan atribut terstruktur.
     * Mendukung query parameter: 'cari', 'kategori', 'tahun', 'per_page', 'page'.
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

        // Opsi mengambil seluruh data tanpa paginasi jika 'all=1' atau 'per_page=all'
        if ($request->boolean('all') || $request->input('per_page') === 'all') {
            $books = $query->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Daftar buku berhasil diambil.',
                'data' => BukuResource::collection($books),
            ], 200);
        }

        // 4. Pagination (default 10 data per halaman) & sertakan query string saat berganti halaman
        $perPage = (int) $request->input('per_page', 10);
        $books = $query->paginate($perPage)->withQueryString();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar buku berhasil diambil.',
            'data' => BukuResource::collection($books),
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
     * Menggunakan $request->validate() langsung pada Controller sesuai ketentuan Modul IV.
     *
     * POST /api/buku
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'integer', 'exists:kategoris,id'],
            'judul' => ['required', 'string', 'min:3', 'max:150'],
            'penulis' => ['required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => ['required', 'integer', 'between:1900,'.date('Y')],
        ], [
            'kategori_id.required' => 'Kategori buku wajib dipilih.',
            'kategori_id.integer' => 'Kategori harus berupa ID bilangan bulat.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid di database.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.min' => 'Judul buku minimal :min karakter.',
            'judul.max' => 'Judul buku maksimal :max karakter.',
            'penulis.required' => 'Penulis buku wajib diisi.',
            'penulis.min' => 'Penulis minimal :min karakter.',
            'penulis.max' => 'Penulis maksimal :max karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa bilangan bulat.',
            'tahun_terbit.between' => 'Tahun terbit harus antara :min sampai :max.',
        ]);

        $book = Buku::create($validated);

        // Memuat relasi kategori dan hitungan peminjaman
        $book->load('kategoriRelasi')->loadCount('peminjamans');

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil ditambahkan.',
            'data' => new BukuResource($book),
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
            'data' => new BukuResource($book),
        ], 200);
    }

    /**
     * Memperbarui data buku berdasarkan ID.
     * Menggunakan $request->validate() langsung pada Controller sesuai ketentuan Modul IV.
     *
     * PUT/PATCH /api/buku/{id}
     */
    public function update(Request $request, int|string $id): JsonResponse
    {
        $book = Buku::find($id);

        if (! $book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'kategori_id' => ['sometimes', 'required', 'integer', 'exists:kategoris,id'],
            'judul' => ['sometimes', 'required', 'string', 'min:3', 'max:150'],
            'penulis' => ['sometimes', 'required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => ['sometimes', 'required', 'integer', 'between:1900,'.date('Y')],
        ], [
            'kategori_id.required' => 'Kategori buku wajib dipilih.',
            'kategori_id.integer' => 'Kategori harus berupa ID bilangan bulat.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid di database.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.min' => 'Judul buku minimal :min karakter.',
            'judul.max' => 'Judul buku maksimal :max karakter.',
            'penulis.required' => 'Penulis buku wajib diisi.',
            'penulis.min' => 'Penulis minimal :min karakter.',
            'penulis.max' => 'Penulis maksimal :max karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa bilangan bulat.',
            'tahun_terbit.between' => 'Tahun terbit harus antara :min sampai :max.',
        ]);

        $book->update($validated);
        $book->load('kategoriRelasi')->loadCount('peminjamans');

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil diperbarui.',
            'data' => new BukuResource($book),
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
