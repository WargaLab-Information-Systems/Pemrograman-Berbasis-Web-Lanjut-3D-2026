<?php

namespace App\Http\Controllers;

use App\Models\Buku;
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
        $buku = $this->getBuku();
        return view('buku.index', compact('buku'));
    }

    public function show($id)
    {
        $buku = $this->getBuku();
        $dataBuku = collect($buku)->firstWhere('id', $id);
        return view('buku.show', compact('dataBuku'));
    }
}