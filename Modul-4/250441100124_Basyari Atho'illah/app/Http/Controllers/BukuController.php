<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\View\View;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar semua buku beserta relasi kategorinya.
     */
    public function index(): View
    {
        // Mengambil kategori untuk pilihan formulir buku
        $kategoris = Kategori::orderBy('nama')->get();

        return view('buku.index', [
            'kategoris' => $kategoris,
        ]);
    }

    /**
     * Menampilkan detail buku berdasarkan ID.
     */
    public function show(int|string $id): View
    {
        // Mencari data buku berdasarkan ID beserta relasi kategorinya
        $book = Buku::with('kategoriRelasi')->find($id);

        return view('buku.show', [
            'book' => $book,
        ]);
    }
}
