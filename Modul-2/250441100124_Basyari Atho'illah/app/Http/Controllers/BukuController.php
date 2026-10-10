<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $books = Buku::with('kategoriRelasi')->get();

        return view('buku.index', [
            'books' => $books,
        ]);
    }

    public function show($id)
    {
        $book = Buku::with('kategoriRelasi')->find($id);

        return view('buku.show', [
            'book' => $book,
        ]);
    }
}
