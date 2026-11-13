<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Http\Resources\BukuResource; // 1. Import BukuResource

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Memuat relasi kategori dan menghitung jumlah peminjaman per buku
        $query = Buku::with('kategori')->withCount('peminjaman');

        // a. Fitur pencarian berdasarkan judul atau penulis (dengan LIKE)
        if ($request->has('cari')) {
            $cari = $request->cari;
            $query->where(function($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        // b. Filter berdasarkan kategori_id
        if ($request->has('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // c. Filter berdasarkan tahun_terbit
        if ($request->has('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        // Urutkan data terbaru, pagination 10 data, dan pastikan query string terbawa
        $buku = $query->latest()->paginate(10)->appends($request->query());

        // 2. Menggunakan BukuResource untuk format collection data pagination
        return BukuResource::collection($buku)->additional([
            'message' => 'Daftar buku berhasil diambil'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBukuRequest $request)
    {
        // Menggunakan $request->validated() untuk perlindungan mass assignment
        $buku = Buku::create($request->validated());

        // 3. Menggunakan BukuResource untuk data tunggal yang baru dibuat
        return (new BukuResource($buku->load('kategori')))
            ->additional(['message' => 'Buku berhasil ditambahkan'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $buku = Buku::with('kategori')->withCount('peminjaman')->find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Data buku tidak ditemukan'
            ], 404);
        }

        return (new BukuResource($buku))
            ->additional(['message' => 'Detail buku berhasil diambil']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBukuRequest $request, $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Data buku tidak ditemukan'
            ], 404);
        }

        $buku->update($request->validated());

        return (new BukuResource($buku->load('kategori')))
            ->additional(['message' => 'Buku berhasil diperbarui']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Data buku tidak ditemukan'
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }

    /**
     * Endpoint Rekap: Total peminjaman per buku
     */
    public function rekapPeminjamanPerBuku()
    {
        $rekap = Buku::withCount('peminjaman')->get();

        return response()->json([
            'message' => 'Rekap total peminjaman per buku berhasil diambil',
            'data' => $rekap
        ], 200);
    }
}