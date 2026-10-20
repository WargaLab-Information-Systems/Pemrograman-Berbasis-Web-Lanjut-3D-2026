<?php

namespace App\Http\Controllers;

class BukuController extends Controller
{
    public function index()
    {
        $buku = [
            [
                'id' => 1,
                'judul' => 'Pulang',
                'penulis' => 'Tere Liye',
                'tahun' => 2015,
                'kategori' => 'Fiksi / Novel'
            ],
            [
                'id' => 2,
                'judul' => 'Hujan',
                'penulis' => 'Tere Liye',
                'tahun' => 2016,
                'kategori' => 'Fiksi / Novel'
            ],
            [
                'id' => 3,
                'judul' => 'Atomic Habits',
                'penulis' => 'James Clear',
                'tahun' => 2018,
                'kategori' => 'Non-Fiksi'
            ],
            [
                'id' => 4,
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'tahun' => 2019,
                'kategori' => 'Non-Fiksi'
            ],
            [
                'id' => 5,
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'tahun' => 2005,
                'kategori' => 'Fiksi / Novel'
            ]
        ];

        return view('buku.index', [
            'buku' => $buku
        ]);
    }

    public function detail($id)
    {
        $buku = [
            [
                'id' => 1,
                'judul' => 'Pulang',
                'penulis' => 'Tere Liye',
                'tahun' => 2015,
                'kategori' => 'Fiksi / Novel'
            ],
            [
                'id' => 2,
                'judul' => 'Hujan',
                'penulis' => 'Tere Liye',
                'tahun' => 2016,
                'kategori' => 'Fiksi / Novel'
            ],
            [
                'id' => 3,
                'judul' => 'Atomic Habits',
                'penulis' => 'James Clear',
                'tahun' => 2018,
                'kategori' => 'Non-Fiksi'
            ],
            [
                'id' => 4,
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'tahun' => 2019,
                'kategori' => 'Non-Fiksi'
            ],
            [
                'id' => 5,
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'tahun' => 2005,
                'kategori' => 'Fiksi / Novel'
            ]
        ];

        $dataBuku = null;

        foreach ($buku as $item) {
            if ($item['id'] == $id) {
                $dataBuku = $item;
                break;
            }
        }

        return view('buku.detail', [
            'buku' => $dataBuku
        ]);
    }
}