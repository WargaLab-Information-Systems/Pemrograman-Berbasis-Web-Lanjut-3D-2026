<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BukuController extends Controller
{
    private $dataBuku = [
        [
            'id' => 1,
            'judul' => 'Pemrograman Web Berbasis Laravel',
            'penulis' => 'Prabo W osubianto',
            'tahun' => 2025,
            'kategori' => 'Teknologi & Ilmu Komputer'
        ],
        [
            'id' => 2,
            'judul' => 'Algoritma dan Struktur Data',
            'penulis' => 'Sumiyati Van Gogh',
            'tahun' => 2024,
            'kategori' => 'Akademik'
        ],
        [
            'id' => 3,
            'judul' => 'Manajemen Basis Data Relasional ala Prabowo',
            'penulis' => 'Jock O Wie',
            'tahun' => 2023,
            'kategori' => 'Sistem Informasi'
        ],
        [
            'id' => 4,
            'judul' => 'UI/UX Design for Beginner',
            'penulis' => 'Master Super Shifu Angga',
            'tahun' => 2026,
            'kategori' => 'Desain'
        ],
        [
            'id' => 5,
            'judul' => 'Kecerdasan Buatan dalam Bisnis',
            'penulis' => 'Mark Zuckenberg',
            'tahun' => 2026,
            'kategori' => 'Teknologi'
        ],
    ];

    public function index()
    {
        $books = $this->dataBuku;
        return view('buku.index', compact('books'));
    }

    public function show($id)
    {
        $book = collect($this->dataBuku)->firstWhere('id', $id);

        return view('buku.show', compact('book'));
    }
}
