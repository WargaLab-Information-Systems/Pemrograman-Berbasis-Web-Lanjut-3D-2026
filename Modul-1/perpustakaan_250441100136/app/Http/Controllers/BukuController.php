<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Data buku sementara (Array multidimensi)
    private $dataBuku = [
        [
            'id' => 1,
            'judul' => 'Pemrograman Web dengan PHP & MySQL',
            'penulis' => 'Eko Rismanto',
            'tahun_terbit' => 2024,
            'kategori' => 'Teknologi Informasi',
            'deskripsi' => 'Buku panduan lengkap mengenai dasar-dasar pemrograman web menggunakan PHP modern dan database MySQL.'
        ],
        [
            'id' => 2,
            'judul' => 'Struktur Data dan Algoritma',
            'penulis' => 'Ahmad Dahlan',
            'tahun_terbit' => 2022,
            'kategori' => 'Ilmu Komputer',
            'deskripsi' => 'Membahas konsep dasar array, stack, queue, tree, graph, serta algoritma pencarian dan pengurutan.'
        ],
        [
            'id' => 3,
            'judul' => 'Desain Sistem Informasi Modern',
            'penulis' => 'Siti Aminah',
            'tahun_terbit' => 2023,
            'kategori' => 'Sistem Informasi',
            'deskripsi' => 'Panduan analisis dan perancangan sistem informasi bisnis menggunakan UML dan pendekatan ERD.'
        ],
        [
            'id' => 4,
            'judul' => 'Dasar-Dasar AI & Machine Learning',
            'penulis' => 'Budi Santoso',
            'tahun_terbit' => 2025,
            'kategori' => 'Kecerdasan Buatan',
            'deskripsi' => 'Pengenalan praktis mengenai konsep kecerdasan buatan, pemrosesan data, dan model machine learning dasar.'
        ],
        [
            'id' => 5,
            'judul' => 'Pengantar Jaringan Komputer',
            'penulis' => 'Rian Hidayat',
            'tahun_terbit' => 2021,
            'kategori' => 'Jaringan',
            'deskripsi' => 'Memahami protokol OSI Layer, TCP/IP, subnetting, dan konfigurasi jaringan dasar.'
        ],
    ];

    public function index()
    {
        return view('buku.index', [
            'buku' => $this->dataBuku
        ]);
    }
    

    public function show($id)
    {
        // Cari buku berdasarkan ID menggunakan array_filter
        $bukuFound = collect($this->dataBuku)->firstWhere('id', (int) $id);

        return view('buku.show', [
            'buku' => $bukuFound
        ]);
    }
}



