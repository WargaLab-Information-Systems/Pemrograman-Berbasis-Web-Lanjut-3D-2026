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
        $query = Buku::with('kategoriRelasi')
            ->withCount('peminjamans')
            ->when($request->cari, fn ($q, $cari) => $q->where(fn ($q) => $q
                ->where('judul', 'like', "%$cari%")
                ->orWhere('penulis', 'like', "%$cari%")))
            ->when($request->kategori, fn ($q, $kategori) => $q->where('kategori_id', $kategori))
            ->when($request->tahun, fn ($q, $tahun) => $q->where('tahun_terbit', $tahun))
            ->latest();

        if (! $request->is('api/*') && ! $request->expectsJson()) {
            $daftarBuku = $query->get()->map(fn ($b) => [
                'id' => $b->id,
                'judul' => $b->judul,
                'penulis' => $b->penulis,
                'tahun_terbit' => $b->tahun_terbit,
                'kategori' => $b->kategoriRelasi?->nama ?? '-',
            ]);

            return view('buku.index', compact('daftarBuku'));
        }

        $daftarBuku = $query->paginate(10)->withQueryString();

        return response()->json($daftarBuku);
    }

    public function detail($id)
    {
        $b = Buku::with(['kategoriRelasi', 'peminjamans'])->find($id);

        if (! $b) {
            abort(404, 'Buku tidak ditemukan');
        }

        $buku = [
            'id' => $b->id,
            'judul' => $b->judul,
            'penulis' => $b->penulis,
            'tahun_terbit' => $b->tahun_terbit,
            'kategori' => $b->kategoriRelasi?->nama ?? '-',
        ];

        return view('buku.detail', compact('buku'));
    }

    // POST /api/buku
    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());

        return response()->json(['data' => $buku->load('kategoriRelasi')], 201);
    }

    // GET /api/buku/{id}
    public function show($id)
    {
        $buku = Buku::with(['kategoriRelasi', 'peminjamans'])->find($id) ?? abort(404, 'not found');

        return response()->json(['data' => $buku]);
    }

    // PUT /api/buku/{id}
    public function update(UpdateBukuRequest $request, $id)
    {
        $buku = Buku::find($id) ?? abort(404, 'not found');
        $buku->update($request->validated());

        return response()->json(['data' => $buku->load('kategoriRelasi')]);
    }

    // DELETE /api/buku/{id}
    public function destroy($id)
    {
        $buku = Buku::find($id) ?? abort(404, 'not found');

        if ($buku->peminjamans()->exists()) {
            return response()->json(['message' => 'Buku masih memiliki riwayat peminjaman'], 422);
        }

        $buku->delete();

        return response()->json(['message' => 'Buku berhasil dihapus']);
    }
}
