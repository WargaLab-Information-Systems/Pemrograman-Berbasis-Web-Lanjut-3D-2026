<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        // MODUL 4: data tidak lagi dikirim dari controller.
        // Halaman mengambil datanya sendiri dari GET /api/buku memakai Fetch API.
        return view('components.buku.index');
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);

        return view('components.buku.show', [
            'buku' => $buku
        ]);
    }
}