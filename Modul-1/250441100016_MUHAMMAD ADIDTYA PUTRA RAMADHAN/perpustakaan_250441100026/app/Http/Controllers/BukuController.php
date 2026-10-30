<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BukuController extends Controller
{
    private $dataBuku = [
        [
            'id' => 1,
            'judul' => 'Belajar Laravel untuk Pemula',
            'penulis' => 'Taylor Otwell',
            'tahun_terbit' => 2023,
            'kategori' => 'Teknologi'
        ],
        [
            'id' => 2,
            'judul' => 'Mahir Tailwind CSS',
            'penulis' => 'Adam Wathan',
            'tahun_terbit' => 2022,
            'kategori' => 'Desain Web'
        ],
        [
            'id' => 3,
            'judul' => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'tahun_terbit' => 2019,
            'kategori' => 'Filsafat'
        ],
        [
            'id' => 4,
            'judul' => 'Atomic Habits',
            'penulis' => 'James Clear',
            'tahun_terbit' => 2018,
            'kategori' => 'Pengembangan Diri'
        ],
        [
            'id' => 5,
            'judul' => 'Dasar Pemrograman Python',
            'penulis' => 'Guido van Rossum',
            'tahun_terbit' => 2021,
            'kategori' => 'Teknologi'
        ]
    ];

    public function index()
    {
        return view('buku.index', ['buku' => $this->dataBuku]);
    }

    public function show($id)
    {
        $bukuDitemukan = null;

        foreach ($this->dataBuku as $buku) {
            if ($buku['id'] == $id) {
                $bukuDitemukan = $buku;
                break;
            }
        }

        return view('buku.detail', ['buku' => $bukuDitemukan]);
    }
}