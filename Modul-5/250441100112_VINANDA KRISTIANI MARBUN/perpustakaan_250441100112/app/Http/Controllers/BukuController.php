<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;


class BukuController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('nama')->get();

        return view('buku.index', compact('kategoris'));
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        $dataBuku = $buku;

        return view('buku.detail', compact('buku', 'dataBuku'));
    }
}