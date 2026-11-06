<?php

namespace App\Http\Controllers;

class BukuController extends Controller
{

    protected array $daftarBuku = [
        [
            'id'      => 1,
            'judul'   => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'tahun'   => 2005,
            'kategori' => 'Novel',
        ],
        [
            'id'      => 2,
            'judul'   => 'Bumi Manusia',
            'penulis' => 'Pramoedya Ananta Toer',
            'tahun'   => 1980,
            'kategori' => 'Novel Sejarah',
        ],
        [
            'id'      => 3,
            'judul'   => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'tahun'   => 2018,
            'kategori' => 'Pengembangan Diri',
        ],
        [
            'id'      => 4,
            'judul'   => 'Sapiens: Riwayat Singkat Umat Manusia',
            'penulis' => 'Yuval Noah Harari',
            'tahun'   => 2011,
            'kategori' => 'Sains Populer',
        ],
        [
            'id'      => 5,
            'judul'   => 'Belajar Laravel dari Nol',
            'penulis' => 'Budi Santoso',
            'tahun'   => 2023,
            'kategori' => 'Pemrograman',
        ],
    ];


    public function index()
    {
        // 6. Passing data ke view menggunakan compact()
        $daftarBuku = $this->daftarBuku;

        return view('buku.index', compact('daftarBuku'));
    }


    public function show($id)
    {
        $buku = collect($this->daftarBuku)->firstWhere('id', (int) $id);

        // $buku bernilai null jika data tidak ditemukan
        // -> ditangani dengan @if di view buku.detail
        return view('buku.detail', compact('buku'));
    }
}
