<?php

namespace App\Http\Controllers;

class BukuController extends Controller
{
    private $databuku = [
        [
            'id_buku' => 1,
            'judul' => 'Matahari',
            'penulis' => 'Ravaaji',
            'tahun_terbit' => 2026,
            'kategori' => 'Novel'
        ],

        [
            'id_buku' => 2,
            'judul' => 'Dunia',
            'penulis' => 'Tanjani',
            'tahun_terbit' => 2000,
            'kategori' => 'Novel'
        ],

        [
            'id_buku' => 3,
            'judul' => 'Belajar Pemrograman',
            'penulis' => 'Jessen',
            'tahun_terbit' => 2006,
            'kategori' => 'Pembelajaran'
        ],

        [
            'id_buku' => 4,
            'judul' => 'Belajar Cyber',
            'penulis' => 'Ravaaji',
            'tahun_terbit' => 2026,
            'kategori' => 'Pembelajaran'
        ],

        [
            'id_buku' => 5,
            'judul' => 'Setan Itu Lagi',
            'penulis' => 'Leo',
            'tahun_terbit' => 2026,
            'kategori' => 'Horor'
        ],
    ];

    public function index()
    {
        return view('databuku.index', [
            'databuku' => $this->databuku
        ]);
    }

    public function show($id_buku)
    {
        $databuku = null;

        foreach ($this->databuku as $buku) {
            if ($buku['id_buku'] == $id_buku) {
                $databuku = $buku;
                break;
            }
        }

        return view('databuku.show', [
            'buku' => $databuku
        ]);
    }
}