<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::select('id', 'judul', 'penulis', 'tahun_terbit as tahun', 'kategori_id as kategori')
                    ->get()
                    ->toArray();

        return view('buku.index', compact('buku'));
    }

    public function show($id)
    {
        $buku = Buku::select('id', 'judul', 'penulis', 'tahun_terbit as tahun', 'kategori_id as kategori')
                    ->find($id);
                    
        $detailBuku = $buku ? $buku->toArray() : null;

        return view('buku.detail', compact('detailBuku'));
    }
}