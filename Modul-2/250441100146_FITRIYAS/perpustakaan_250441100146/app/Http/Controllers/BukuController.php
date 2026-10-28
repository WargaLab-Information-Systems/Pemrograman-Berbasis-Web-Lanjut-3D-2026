<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'penulis' => $item->penulis,
                'tahun' => $item->tahun_terbit,
                'kategori' => $item->kategori->nama,
            ];
        })->toArray();

        return view('buku.index', [
            'buku' => $buku
        ]);
    }

    public function show($id)
{
    $item = Buku::with('kategori')->find($id);

    if (!$item) {
        return view('buku.show', [
            'buku' => null
        ]);
    }

    $buku = [
        'id' => $item->id,
        'judul' => $item->judul,
        'penulis' => $item->penulis,
        'tahun' => $item->tahun_terbit,
        'kategori' => $item->kategori->nama,
    ];

    return view('buku.show', [
        'buku' => $buku
    ]);
    }
}