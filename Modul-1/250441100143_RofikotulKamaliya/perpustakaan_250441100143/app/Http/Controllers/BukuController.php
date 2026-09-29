<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BukuController extends Controller
{
    private function getBuku()
    {
        return [
            [
                'id' => 1,
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'tahun' => 2005,
                'kategori' => 'Fiksi/Inspiratif',
            ],
            [
                'id' => 2,
                'judul' => 'Cantik Itu Luka',
                'penulis' => 'Eka Kurniawan',
                'tahun' => 2002,
                'kategori' => 'Sastra',
            ],
            [
                'id' => 3,
                'judul' => 'Filosofi Kopi',
                'penulis' => 'Dee Lestari',
                'tahun' => 2006,
                'kategori' => 'Fiksi',
            ],
            [
                'id' => 4,
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'tahun' => 2018,
                'kategori' => 'Pengembangan Diri',
            ],
            [
                'id' => 5,
                'judul' => 'Midnight Library',
                'penulis' => 'Matt Haig',
                'tahun' => 2020,
                'kategori' => 'Fantasi',
            ],
            [
                'id' => 6,
                'judul' => 'Katastrofe',
                'penulis' => 'J.S. Khairen',
                'tahun' => 2023,
                'kategori' => 'Misteri',
            ],
            [
                'id' => 7,
                'judul' => 'Pulang',
                'penulis' => 'Tere Liye',
                'tahun' => 2015,
                'kategori' => 'Novel',
            ],
            [
                'id' => 8,
                'judul' => 'The Let Them Theory',
                'penulis' => 'Mel Robbins',
                'tahun' => 2025,
                'kategori' => 'Pengembangan Diri',
            ],
        ];
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