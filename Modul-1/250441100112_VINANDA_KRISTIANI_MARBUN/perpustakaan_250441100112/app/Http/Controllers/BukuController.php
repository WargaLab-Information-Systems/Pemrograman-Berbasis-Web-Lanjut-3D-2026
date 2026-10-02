<?php

namespace App\Http\Controllers;

class BukuController extends Controller
{
    private function dataBuku()
    {
        return [
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
                'judul' => 'Pemrograman PHP untuk Pemula',
                'penulis' => 'Budi Raharjo',
                'tahun' => 2020,
                'kategori' => 'Teknologi'
            ],
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

        return view('buku.detail', compact('dataBuku'));
    }
}
