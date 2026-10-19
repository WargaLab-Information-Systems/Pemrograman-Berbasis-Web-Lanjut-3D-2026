<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BukuController extends Controller
{
    private $daftarBuku = [
        ['id' => '1', 'judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'tahun' => 2015, 'kategori' => 'Novel'],
        ['id' => '2', 'judul' => 'Tenggelamnya Kapal van Der Wijk', 'penulis' => 'Buya Hamka', 'tahun' => 1939, 'kategori' => 'Novel'],
        ['id' => '3', 'judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'tahun' => 1980, 'kategori' => 'Novel'],
        ['id' => '4', 'judul' => 'Sejarah Nusantara', 'penulis' => 'Budi Santoso', 'tahun' => 2018, 'kategori' => 'Sejarah'],
        ['id' => '5', 'judul' => 'Madilog', 'penulis' => 'Tan Malaka', 'tahun' => 1951, 'kategori' => 'Non Fiksi'],
    ];

    public function index()
    {
        $buku = $this->daftarBuku;
        return view('buku.index', compact('buku'));
    }

    public function show($id)
    {
        $detailBuku = collect($this->daftarBuku)->firstWhere('id', $id);
        return view('buku.detail', compact('detailBuku'));
    }
}