<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Http\Resources\BukuResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class BukuController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user(); 

        if ($user && $user->hasRole('siswa')) {
            $buku = Buku::whereDoesntHave('peminjamans', function ($query) use ($user) {
                $query->whereIn('status', ['Menunggu Persetujuan', 'Disetujui'])
                      ->where('anggota_id', '!=', $user->id);
            })->latest()->get();
        } else {
            $buku = Buku::latest()->get();
        }

        return response()->json([
            'message' => 'Berhasil mengambil data buku',
            'data'    => BukuResource::collection($buku)
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'  => 'required|integer|exists:kategoris,id',
            'judul'        => 'required|string|min:3',
            'penulis'      => 'required|string',
            'tahun_terbit' => 'required|integer|between:1900,2026',
        ], [
            'kategori_id.required'  => 'Kategori wajib diisi.',
            'kategori_id.exists'    => 'Kategori yang dipilih tidak valid (ID tidak ada di database).',
            'judul.required'        => 'Judul buku wajib diisi.',
            'judul.min'             => 'Judul buku minimal 3 karakter.',
            'penulis.required'      => 'Penulis wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.between'  => 'Tahun terbit harus antara 1900 sampai 2026.',
        ]);

        $buku = Buku::create($validated);

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data'    => new BukuResource($buku)
        ], 201);
    }

    // Menambahkan 'string' untuk menghilangkan peringatan biru Intelephense
    public function show(string $id)
    {
        $buku = Buku::find($id);
        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Berhasil mengambil detail buku',
            'data'    => new BukuResource($buku)
        ], 200);
    }

    // Menambahkan 'string' untuk menghilangkan peringatan biru Intelephense
    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);
        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'kategori_id'  => 'required|integer|exists:kategoris,id',
            'judul'        => 'required|string|min:3',
            'penulis'      => 'required|string',
            'tahun_terbit' => 'required|integer|between:1900,2026',
        ], [
            'kategori_id.required'  => 'Kategori wajib diisi.',
            'kategori_id.exists'    => 'Kategori yang dipilih tidak valid (ID tidak ada di database).',
            'judul.required'        => 'Judul buku wajib diisi.',
            'judul.min'             => 'Judul buku minimal 3 karakter.',
            'penulis.required'      => 'Penulis wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.between'  => 'Tahun terbit harus antara 1900 sampai 2026.',
        ]);

        $buku->update($validated);

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data'    => new BukuResource($buku)
        ], 200);
    }

    // Menambahkan 'string' untuk menghilangkan peringatan biru Intelephense
    public function destroy(string $id)
    {
        $buku = Buku::find($id);
        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }
}