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
                'tahun_terbit' => 2005,
                'kategori' => 'Novel'
            ],
            [
                'id' => 2,
                'judul' => 'Bumi',
                'penulis' => 'Tere Liye',
                'tahun_terbit' => 2014,
                'kategori' => 'Fantasi'
            ],
            [
                'id' => 3,
                'judul' => 'Negeri 5 Menara',
                'penulis' => 'Ahmad Fuadi',
                'tahun_terbit' => 2009,
                'kategori' => 'Novel'
            ],
            [
                'id' => 4,
                'judul' => 'Pulang',
                'penulis' => 'Tere Liye',
                'tahun_terbit' => 2015,
                'kategori' => 'Drama'
            ],
            [
                'id' => 5,
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'tahun_terbit' => 2018,
                'kategori' => 'Pengembangan Diri'
            ],
            [
                'id' => 6,
                'judul' => 'Madilog',
                'penulis' => 'Tan Malaka',
                'tahun_terbit' => 1943,
                'kategori' => 'Filsafat'
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