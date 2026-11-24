<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BukuController extends Controller
{
    private $buku = [
        [
            'id' => 1,
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'tahun' => 2005,
            'kategori' => 'Novel'
        ],
        [
            'id' => 2,
            'judul' => 'Bumi Manusia',
            'penulis' => 'Pramoedya Ananta Toer',
            'tahun' => 1980,
            'kategori' => 'Sejarah'
        ],
        [
            'id' => 3,
            'judul' => 'Negeri 5 Menara',
            'penulis' => 'Ahmad Fuadi',
            'tahun' => 2009,
            'kategori' => 'Novel'
        ],
        [
            'id' => 4,
            'judul' => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'tahun' => 2018,
            'kategori' => 'Pengembangan Diri'
        ],
        [
            'id' => 5,
            'judul' => 'Atomic Habits',
            'penulis' => 'James Clear',
            'tahun' => 2018,
            'kategori' => 'Pengembangan Diri'
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
        $dataBuku = null;

        foreach ($this->buku as $buku) {
            if ($buku['id'] == $id) {
                $dataBuku = $buku;
                break;
            }
        }

        return view('buku.detail', [
            'buku' => $dataBuku
        ]);
    }
}