<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $dataBuku = Buku::with('kategori')->get();

        $buku = [];

        foreach ($dataBuku as $item) {
            $buku[] = [
                'id' => $item->id,
                'judul' => $item->judul,
                'penulis' => $item->penulis,
                'tahun' => $item->tahun_terbit,
                'kategori' => $item->kategori->nama
            ];
        }

        return view('buku.index', compact('buku'));
    }

    public function show($id)
    {
        $item = Buku::with('kategori')->find($id);

        if ($item) {
            $dataBuku = [
                'id' => $item->id,
                'judul' => $item->judul,
                'penulis' => $item->penulis,
                'tahun' => $item->tahun_terbit,
                'kategori' => $item->kategori->nama
            ];
        } else {
            $dataBuku = null;
        }

        return view('buku.show', compact('dataBuku'));
    }
}