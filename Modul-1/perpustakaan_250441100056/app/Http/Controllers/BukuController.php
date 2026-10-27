<?php

namespace App\Http\Controllers;

class BukuController extends Controller
{
    private $buku = [
        [
            'id' => 1,
            'judul' => 'Pemrograman PHP Dasar',
            'penulis' => 'Andika ',
            'tahun' => 2023,
            'kategori' => 'Pemrograman'
        ],
        [
            'id' => 2,
            'judul' => 'Belajar Laravel untuk Pemula',
            'penulis' => 'Andi Pratama',
            'tahun' => 2024,
            'kategori' => 'Web Development'
        ],
        [
            'id' => 3,
            'judul' => 'Dasar-Dasar Basis Data',
            'penulis' => 'Siti Aminah',
            'tahun' => 2022,
            'kategori' => 'Database'
        ],
        [
            'id' => 4,
            'judul' => 'Jaringan Komputer',
            'penulis' => 'Dila putriayu',
            'tahun' => 2023,
            'kategori' => 'Jaringan'
        ],
        [
            'id' => 5,
            'judul' => 'Pemrograman Web Modern',
            'penulis' => 'Rina Lestari',
            'tahun' => 2024,
            'kategori' => 'Web Development'
        ],
    ];

    public function index()
    {
        return view('buku.index', [
            'buku' => $this->buku
        ]);
    }

    public function show($id)
    {
        $buku = collect($this->buku)->firstWhere('id', $id);

        return view('buku.show', [
            'buku' => $buku
        ]);
    }
}

