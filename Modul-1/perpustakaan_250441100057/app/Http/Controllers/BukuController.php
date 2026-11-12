<?php

namespace App\Http\Controllers;

class BukuController extends Controller
{
    private function dataBuku()
    {
        return [
            [
                'id' => 1,
                'judul' => 'Pemrograman Web dengan Laravel',
                'penulis' => 'Budi Raharjo',
                'tahun' => 2024,
                'kategori' => 'Pemrograman'
            ],
            [
                'id' => 2,
                'judul' => 'Dasar-Dasar Pemrograman',
                'penulis' => 'Andi Setiawan',
                'tahun' => 2023,
                'kategori' => 'Pemrograman'
            ],
            [
                'id' => 3,
                'judul' => 'Belajar PHP untuk Pemula',
                'penulis' => 'Siti Aminah',
                'tahun' => 2022,
                'kategori' => 'PHP'
            ],
            [
                'id' => 4,
                'judul' => 'Mengenal Database MySQL',
                'penulis' => 'Rudi Hartono',
                'tahun' => 2023,
                'kategori' => 'Database'
            ],
            [
                'id' => 5,
                'judul' => 'Pemrograman Berbasis Web',
                'penulis' => 'Dewi Lestari',
                'tahun' => 2024,
                'kategori' => 'Web'
            ]
        ];
    }

    public function index()
    {
        $buku = $this->dataBuku();

        return view('buku.index', compact('buku'));
    }

    public function show($id)
    {
        $buku = $this->dataBuku();

        $dataBuku = null;

        foreach ($buku as $item) {
            if ($item['id'] == $id) {
                $dataBuku = $item;
                break;
            }
        }

        return view('buku.show', compact('dataBuku'));
    }
}