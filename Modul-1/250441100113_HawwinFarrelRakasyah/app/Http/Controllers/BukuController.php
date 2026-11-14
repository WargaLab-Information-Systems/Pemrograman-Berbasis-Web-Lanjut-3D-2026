<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BukuController extends Controller
{
    private array $daftarBuku = [
        ['id' => 1, 'judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'tahun_terbit' => 2005, 'kategori' => 'Novel'],
        ['id' => 2, 'judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'tahun_terbit' => 1980, 'kategori' => 'Sejarah'],
        ['id' => 3, 'judul' => 'Filosofi Teras', 'penulis' => 'Henry Manampiring', 'tahun_terbit' => 2018, 'kategori' => 'Filsafat'],
        ['id' => 4, 'judul' => 'Cantik Itu Luka', 'penulis' => 'Eka Kurniawan', 'tahun_terbit' => 2002, 'kategori' => 'Novel'],
        ['id' => 5, 'judul' => 'Sejarah Dunia', 'penulis' => 'Jonathan Black', 'tahun_terbit' => 2007, 'kategori' => 'Sejarah'],
    ];

    public function index()
    {
        $buku = $this->daftarBuku;
        return view('buku.index', compact('buku'));
    }

    public function show($id)
    {
        $buku = collect($this->daftarBuku)->firstWhere('id', (int) $id);
        return view('buku.show', compact('buku'));
    }
}