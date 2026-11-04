<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Poin 5: Data buku sementara
    private $dataBuku = [
        [
            'id' => 1,
            'judul' => 'Pemrograman Web Lanjut',
            'penulis' => 'Anton Gem, S.Kom., M.Kom.',
            'tahun_terbit' => 2024,
            'kategori' => 'Teknologi'
        ],
        [
            'id' => 2,
            'judul' => 'Mastering Model-View-Controller',
            'penulis' => 'Ferry Sukiwandi, S.Kom., M.Kom.',
            'tahun_terbit' => 2023,
            'kategori' => 'Pemrograman'
        ],
        [
            'id' => 3,
            'judul' => 'Desain Sistem & Basis Data',
            'penulis' => 'Dante, S.Si., M.T.',
            'tahun_terbit' => 2022,
            'kategori' => 'Basis Data'
        ],
        [
            'id' => 4,
            'judul' => 'Algoritma & Struktur Data Modern',
            'penulis' => 'Ilham God, S.Kom., M.Kom.',
            'tahun_terbit' => 2025,
            'kategori' => 'Ilmu Komputer'
        ],
        [
            'id' => 5,
            'judul' => 'Panduan Praktis UI/UX Design',
            'penulis' => 'Raze Satchel',
            'tahun_terbit' => 2021,
            'kategori' => 'Desain'
        ]
    ];

    // Poin 4 & 6: Menampilkan daftar buku
    public function index()
    {
        return view('buku.index', [
            'bukuList' => $this->dataBuku
        ]);
    }

    // Poin 4, 6 & 13: Menampilkan detail buku berdasarkan ID dari URL
    public function show($id)
    {
        $buku = collect($this->dataBuku)->firstWhere('id', $id);

        return view('buku.show', [
            'buku' => $buku
        ]);
    }
}