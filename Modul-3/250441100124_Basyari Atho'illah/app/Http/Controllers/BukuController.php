<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\View\View;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar semua buku beserta relasi kategorinya.
     */
    public function index(): View
    {
        // Mengambil semua buku sekaligus relasi kategorinya (Eager Loading) untuk menghemat query database
        $books = Buku::with('kategoriRelasi')->get();

        return view('buku.index', [
            'books' => $books,
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
