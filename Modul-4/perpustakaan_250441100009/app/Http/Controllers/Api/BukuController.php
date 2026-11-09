<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategoriRelasi')
            ->withCount('peminjamans')
            ->when($request->cari, fn ($q, $cari) => $q->where(fn ($q) => $q
                ->where('judul', 'like', "%$cari%")
                ->orWhere('penulis', 'like', "%$cari%")))
            ->when($request->kategori, fn ($q, $kategori) => $q->where('kategori_id', $kategori))
            ->when($request->tahun, fn ($q, $tahun) => $q->where('tahun_terbit', $tahun))
            ->latest();

        if (! $request->is('api/*') && ! $request->expectsJson()) {
            $kategoris = Kategori::all();

            return view('buku.index', compact('kategoris'));
        }

        $daftarBuku = $query->get();

        return BukuResource::collection($daftarBuku);
    }

    public function detail($id)
    {
        $buku = Buku::with(['kategoriRelasi', 'peminjamans'])->find($id);

        if (! $buku) {
            abort(404, 'Buku tidak ditemukan');
        }

        return view('buku.detail', compact('buku'));
    }

    // POST /api/buku
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|min:3|max:100',
            'tahun_terbit' => 'required|integer|between:1900,'.date('Y'),
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'judul.min' => 'Judul buku minimal :min karakter.',
            'judul.max' => 'Judul buku maksimal :max karakter.',
            'penulis.min' => 'Penulis minimal :min karakter.',
            'penulis.max' => 'Penulis maksimal :max karakter.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.between' => 'Tahun terbit harus antara :min sampai :max.',
        ], [
            'kategori_id' => 'kategori',
            'judul' => 'judul buku',
            'penulis' => 'penulis',
            'tahun_terbit' => 'tahun terbit',
        ]);

        $buku = Buku::create($validated);

        return (new BukuResource($buku->load('kategoriRelasi')))
            ->response()
            ->setStatusCode(201);
    }

    // GET /api/buku/{id}
    public function show($id)
    {
        $buku = Buku::with(['kategoriRelasi', 'peminjamans'])->find($id) ?? abort(404, 'Buku tidak ditemukan');

        return new BukuResource($buku);
    }

    // PUT /api/buku/{id}
    public function update(Request $request, $id)
    {
        $buku = Buku::find($id) ?? abort(404, 'Buku tidak ditemukan');

        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|min:3|max:100',
            'tahun_terbit' => 'required|integer|between:1900,'.date('Y'),
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'judul.min' => 'Judul buku minimal :min karakter.',
            'judul.max' => 'Judul buku maksimal :max karakter.',
            'penulis.min' => 'Penulis minimal :min karakter.',
            'penulis.max' => 'Penulis maksimal :max karakter.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.between' => 'Tahun terbit harus antara :min sampai :max.',
        ], [
            'kategori_id' => 'kategori',
            'judul' => 'judul buku',
            'penulis' => 'penulis',
            'tahun_terbit' => 'tahun terbit',
        ]);

        $buku->update($validated);

        return new BukuResource($buku->load('kategoriRelasi'));
    }

    // DELETE /api/buku/{id}
    public function destroy($id)
    {
        $buku = Buku::find($id) ?? abort(404, 'Buku tidak ditemukan');

        if ($buku->peminjamans()->exists()) {
            return response()->json(['message' => 'Buku masih memiliki riwayat peminjaman'], 422);
        }

        $buku->delete();

        return response()->json(['message' => 'Buku berhasil dihapus']);
    }
}
