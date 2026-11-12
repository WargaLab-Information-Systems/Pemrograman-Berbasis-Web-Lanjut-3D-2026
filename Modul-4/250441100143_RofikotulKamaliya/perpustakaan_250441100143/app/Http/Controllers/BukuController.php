<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    private function getBuku()
    {
        return Buku::with('kategori')->get()->map(function ($buku) {
            return [
                'id' => $buku->id,
                'judul' => $buku->judul,
                'penulis' => $buku->penulis,
                'tahun' => $buku->tahun_terbit,
                'kategori' => $buku->kategori->nama,
            ];
        });
    }

    public function index()
    {
        $kategori = Kategori::all();
        return view('buku.index', compact('kategori'));
    }

    public function show($id)
    {
        $buku = $this->getBuku();
        $dataBuku = collect($buku)->firstWhere('id', $id);
        return view('buku.show', compact('dataBuku'));
    }
}