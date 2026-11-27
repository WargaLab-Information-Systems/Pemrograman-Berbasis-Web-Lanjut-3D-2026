<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->latest()->get();
        $kategoris = Kategori::all(); // 2. Ambil data kategori untuk pilihan dropdown

        // 3. Sertakan 'kategoris' ke dalam compact
        return view('buku.index', compact('buku', 'kategoris'));
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->find($id);
        return view('buku.show', compact('buku'));
    }
}