<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get();

        return view('components.buku.index', [
            'buku' => $buku
        ]);
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);

        return view('components.buku.show', [
            'buku' => $buku
        ]);
    }
}