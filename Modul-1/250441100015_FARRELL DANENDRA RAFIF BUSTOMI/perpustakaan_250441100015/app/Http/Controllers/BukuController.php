<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BukuController extends Controller
{
    private $books = [
        [
            'id' => 1,
            'judul' => 'Bumi',
            'penulis' => 'Tere Liye',
            'tahun' => 2014,
            'kategori' => 'Fantasi',
        ],
        [
            'id' => 2,
            'judul' => 'Bulan',
            'penulis' => 'Tere Liye',
            'tahun' => 2015,
            'kategori' => 'Fantasi',
        ],
        [
            'id' => 3,
            'judul' => 'Matahari',
            'penulis' => 'Tere Liye',
            'tahun' => 2016,
            'kategori' => 'Fantasi',
        ],
        [
            'id' => 4,
            'judul' => 'Hujan',
            'penulis' => 'Tere Liye',
            'tahun' => 2016,
            'kategori' => 'Drama',
        ],
        [
            'id' => 5,
            'judul' => 'Laut Bercerita',
            'penulis' => 'Tere Liye',
            'tahun' => 2017,
            'kategori' => 'Drama',
        ],
    ];

    public function index()
    {
        return view('buku.index', [
            'books' => $this->books
        ]);
    }

    public function show(int $id)
    {
        $book = collect($this->books)->firstWhere('id', $id);

        return view('buku.show', [
            'book' => $book
        ]);
    }
}

