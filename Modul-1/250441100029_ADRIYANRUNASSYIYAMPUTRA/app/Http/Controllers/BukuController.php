<?php

namespace App\Http\Controllers;

class BukuController extends Controller
{
    /**
     * Data buku sementara (tanpa database).
     * Nanti di modul berikutnya cukup ganti method ini dengan Model.
     */
    private function dataBuku(): array
    {
        return [
            ['id' => 1, 'judul' => 'Laskar Pelangi',      'penulis' => 'Andrea Hirata',          'tahun_terbit' => 2005, 'kategori' => 'Novel'],
            ['id' => 2, 'judul' => 'Bumi Manusia',        'penulis' => 'Pramoedya Ananta Toer',  'tahun_terbit' => 1980, 'kategori' => 'Novel Sejarah'],
            ['id' => 3, 'judul' => 'Filosofi Teras',      'penulis' => 'Henry Manampiring',      'tahun_terbit' => 2018, 'kategori' => 'Filsafat'],
            ['id' => 4, 'judul' => 'Atomic Habits',       'penulis' => 'James Clear',            'tahun_terbit' => 2018, 'kategori' => 'Pengembangan Diri'],
            ['id' => 5, 'judul' => 'Clean Code',          'penulis' => 'Robert C. Martin',       'tahun_terbit' => 2008, 'kategori' => 'Teknologi'],
            ['id' => 6, 'judul' => 'Sapiens',             'penulis' => 'Yuval Noah Harari',      'tahun_terbit' => 2011, 'kategori' => 'Sejarah'],
        ];
    }

    public function index()
    {
        return view('buku.index', [
            'daftarBuku' => $this->dataBuku(),
        ]);
    }

    public function show($id)
    {
        // Cari buku berdasarkan id; hasilnya null jika tidak ditemukan
        $buku = collect($this->dataBuku())->firstWhere('id', (int) $id);

        return view('buku.show', [
            'buku' => $buku,
        ]);
    }
}