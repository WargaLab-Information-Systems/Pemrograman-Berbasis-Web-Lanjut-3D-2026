<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
   
    public function index(Request $request)
{
    $query = Buku::with('kategori')
        ->withCount('peminjamans')
        ->latest();

    if ($request->filled('cari')) {
        $cari = $request->cari;

        $query->where(function ($q) use ($cari) {
            $q->where('judul', 'like', "%{$cari}%")
              ->orWhere('penulis', 'like', "%{$cari}%");
        });
    }

    if ($request->filled('kategori')) {
        $query->where('kategori_id', $request->kategori);
    }

    if ($request->filled('tahun')) {
        $query->where('tahun_terbit', $request->tahun);
    }

    $perPage = $request->input('per_page', 50);
    $buku = $query->paginate($perPage)->withQueryString();

    return response()->json([
        'data' => BukuResource::collection(
            $buku->getCollection()
        )->resolve(),

        'meta' => [
            'current_page' => $buku->currentPage(),
            'last_page' => $buku->lastPage(),
            'per_page' => $buku->perPage(),
            'total' => $buku->total(),
        ],

        'links' => [
            'first' => $buku->url(1),
            'last' => $buku->url($buku->lastPage()),
            'prev' => $buku->previousPageUrl(),
            'next' => $buku->nextPageUrl(),
        ],
    ], 200);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'integer', 'exists:categorys,id'],
            'judul' => ['required', 'string', 'min:3', 'max:150'],
            'penulis' => ['required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => ['required', 'integer', 'between:1900,' . date('Y')],
        ], [
            'kategori_id.required' => 'Kategori wajib diisi.',
            'kategori_id.integer' => 'Kategori harus berupa angka.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.min' => 'Judul buku minimal 3 karakter.',
            'judul.max' => 'Judul buku maksimal 150 karakter.',
            'penulis.required' => 'Penulis wajib diisi.',
            'penulis.min' => 'Nama penulis minimal 3 karakter.',
            'penulis.max' => 'Nama penulis maksimal 100 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa bilangan bulat.',
            'tahun_terbit.between' => 'Tahun terbit harus antara 1900 sampai ' . date('Y') . '.',
        ]);

        $buku = Buku::create($validated);
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan.',
            'data' => new BukuResource($buku),
        ], 201);
    }


    public function show(string $id)
    {
        $buku = Buku::with(['kategori', 'peminjamans'])
            ->withCount('peminjamans')
            ->find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'not found',
            ], 404);
        }

        return response()->json([
            'data' => new BukuResource($buku),
        ], 200);
    }


    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'not found',
            ], 404);
        }

        $validated = $request->validate([
            'kategori_id' => ['required', 'integer', 'exists:categorys,id'],
            'judul' => ['required', 'string', 'min:3', 'max:150'],
            'penulis' => ['required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => ['required', 'integer', 'between:1900,' . date('Y')],
        ], [
            'kategori_id.required' => 'Kategori wajib diisi.',
            'kategori_id.integer' => 'Kategori harus berupa angka.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.min' => 'Judul buku minimal 3 karakter.',
            'judul.max' => 'Judul buku maksimal 150 karakter.',
            'penulis.required' => 'Penulis wajib diisi.',
            'penulis.min' => 'Nama penulis minimal 3 karakter.',
            'penulis.max' => 'Nama penulis maksimal 100 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa bilangan bulat.',
            'tahun_terbit.between' => 'Tahun terbit harus antara 1900 sampai ' . date('Y') . '.',
        ]);

        $buku->update($validated);
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil diperbarui.',
            'data' => new BukuResource($buku),
        ], 200);
    }

    public function destroy(string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'not found',
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus',
        ], 200);
    }
}
