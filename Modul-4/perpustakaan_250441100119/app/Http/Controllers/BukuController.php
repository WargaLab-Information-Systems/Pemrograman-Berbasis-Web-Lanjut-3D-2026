<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku.
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori')
            ->withCount('peminjamans');

        // Pencarian berdasarkan judul atau penulis
        if ($request->filled('cari')) {
            $cari = $request->cari;

            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter berdasarkan tahun terbit
        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        // Urutkan buku terbaru dan tampilkan 10 buku per halaman
        $buku = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('buku.index', compact('buku'));
    }

    /**
     * Menampilkan detail buku.
     */
    public function show(string $id)
    {
        $buku = Buku::with([
            'kategori',
            'peminjamans',
        ])
            ->withCount('peminjamans')
            ->findOrFail($id);

        return view('buku.show', compact('buku'));
    }
}