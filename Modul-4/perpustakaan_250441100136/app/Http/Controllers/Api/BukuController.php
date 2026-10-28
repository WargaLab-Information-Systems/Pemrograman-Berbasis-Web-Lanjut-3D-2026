<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class BukuController extends Controller
{
    // GET /api/buku
    public function index(Request $request): JsonResponse
    {
        $query = Buku::with('kategori')->latest();

        if ($request->filled('cari')) {
            $keyword = $request->cari;
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                    ->orWhere('penulis', 'like', "%{$keyword}%");
            });
        }

        return response()->json([
            'data' => BukuResource::collection($query->get()),
        ], 200);
    }

    // Store POST /api/buku
    public function store(Request $request): JsonResponse
    {
        $errors = [];

        // Validasi Kategori
        if (! $request->filled('kategori')) {
            $errors['kategori'][] = 'Kategori buku wajib diisi.';
        } elseif (! is_numeric($request->kategori)) {
            $errors['kategori'][] = 'Kategori harus berupa angka.';
        } elseif (! in_array((int) $request->kategori, [1, 2], true)) {
            $errors['kategori'][] = 'Kategori yang diperbolehkan hanya angka 1 atau 2.';
        }

        // Validasi Judul
        if (! $request->filled('judul')) {
            $errors['judul'][] = 'Judul buku wajib diisi.';
        } elseif (! is_string($request->judul) || strlen($request->judul) > 255) {
            $errors['judul'][] = 'Judul buku harus berupa teks maksimal 255 karakter.';
        }

        // Validasi Penulis
        if (! $request->filled('penulis')) {
            $errors['penulis'][] = 'Nama penulis buku wajib diisi.';
        } elseif (! is_string($request->penulis) || strlen($request->penulis) > 100) {
            $errors['penulis'][] = 'Nama penulis harus berupa teks maksimal 100 karakter.';
        }

        // Validasi Tahun Terbit
        if (! $request->filled('tahun_terbit')) {
            $errors['tahun_terbit'][] = 'Tahun terbit buku wajib diisi.';
        } elseif (! is_numeric($request->tahun_terbit)) {
            $errors['tahun_terbit'][] = 'Tahun terbit harus berupa angka.';
        } elseif (strlen((string) $request->tahun_terbit) !== 4) {
            $errors['tahun_terbit'][] = 'Tahun terbit harus berupa 4 digit angka yang valid.';
        }

        if (! empty($errors)) {
            return response()->json([
                'message' => 'Data yang diberikan tidak valid.',
                'errors' => $errors,
            ], 422);
        }

        try {
            $kategori = Kategori::find($request->kategori);
            if (! $kategori) {
                return response()->json([
                    'message' => 'Data yang diberikan tidak valid.',
                    'errors' => [
                        'kategori' => ["Kategori dengan ID {$request->kategori} tidak ditemukan dalam sistem."],
                    ],
                ], 422);
            }

            $buku = Buku::create([
                'kategori_id' => $kategori->id,
                'judul' => $request->judul,
                'penulis' => $request->penulis,
                'tahun_terbit' => $request->tahun_terbit,
            ]);

            return response()->json([
                'message' => 'Buku berhasil ditambahkan.',
                'data' => new BukuResource($buku->load('kategori')),
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal menyimpan buku: Pastikan kategori dan data yang dimasukkan sudah sesuai.',
            ], 422);
        }
    }

    // GET /api/buku/{buku}
    public function show(Buku $buku): JsonResponse
    {
        return response()->json([
            'data' => new BukuResource($buku->load('kategori')),
        ], 200);
    }

    // PUT /api/buku/{buku}
    public function update(Request $request, Buku $buku): JsonResponse
    {
        $errors = [];

        // Validasi Kategori: wajib, harus angka, dan hanya boleh 1 atau 2
        if (! $request->filled('kategori')) {
            $errors['kategori'][] = 'Kategori buku wajib diisi.';
        } elseif (! is_numeric($request->kategori)) {
            $errors['kategori'][] = 'Kategori harus berupa angka.';
        } elseif (! in_array((int) $request->kategori, [1, 2], true)) {
            $errors['kategori'][] = 'Kategori yang diperbolehkan hanya angka 1 atau 2.';
        }

        // Validasi Judul
        if (! $request->filled('judul')) {
            $errors['judul'][] = 'Judul buku wajib diisi.';
        } elseif (! is_string($request->judul) || strlen($request->judul) > 255) {
            $errors['judul'][] = 'Judul buku harus berupa teks maksimal 255 karakter.';
        }

        // Validasi Penulis
        if (! $request->filled('penulis')) {
            $errors['penulis'][] = 'Nama penulis buku wajib diisi.';
        } elseif (! is_string($request->penulis) || strlen($request->penulis) > 100) {
            $errors['penulis'][] = 'Nama penulis harus berupa teks maksimal 100 karakter.';
        }

        // Validasi Tahun Terbit
        if (! $request->filled('tahun_terbit')) {
            $errors['tahun_terbit'][] = 'Tahun terbit buku wajib diisi.';
        } elseif (! is_numeric($request->tahun_terbit)) {
            $errors['tahun_terbit'][] = 'Tahun terbit harus berupa angka.';
        } elseif (strlen((string) $request->tahun_terbit) !== 4) {
            $errors['tahun_terbit'][] = 'Tahun terbit harus berupa 4 digit angka yang valid.';
        }

        if (! empty($errors)) {
            return response()->json([
                'message' => 'Data yang diberikan tidak valid.',
                'errors' => $errors,
            ], 422);
        }

        try {
            $kategori = Kategori::find($request->kategori);
            if (! $kategori) {
                return response()->json([
                    'message' => 'Data yang diberikan tidak valid.',
                    'errors' => [
                        'kategori' => ["Kategori dengan ID {$request->kategori} tidak ditemukan dalam sistem."],
                    ],
                ], 422);
            }

            $buku->update([
                'kategori_id' => $kategori->id,
                'judul' => $request->judul,
                'penulis' => $request->penulis,
                'tahun_terbit' => $request->tahun_terbit,
            ]);

            return response()->json([
                'message' => 'Buku berhasil diperbarui.',
                'data' => new BukuResource($buku->load('kategori')),
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal memperbarui buku: Pastikan kategori dan data yang dimasukkan sudah sesuai.',
            ], 422);
        }
    }

    // DELETE /api/buku/{buku}
    public function destroy(Buku $buku): JsonResponse
    {
        try {
            $buku->delete();

            return response()->json([
                'message' => 'Buku berhasil dihapus',
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal menghapus buku: Data buku ini mungkin masih terikat dengan data peminjaman.',
            ], 422);
        }
    }
}
