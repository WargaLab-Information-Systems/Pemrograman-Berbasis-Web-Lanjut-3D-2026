<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class BukuController extends Controller
{
    /**
     * Data buku sementara (tanpa database).
     * Dipisah ke method sendiri supaya nanti mudah diganti ke Model/Database.
     */
    private function semuaBuku(): array
    {
        return [
            ['id' => 1, 'judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'tahun_terbit' => 2005, 'kategori' => 'Novel'],
            ['id' => 2, 'judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'tahun_terbit' => 1980, 'kategori' => 'Sastra'],
            ['id' => 3, 'judul' => 'Filosofi Teras', 'penulis' => 'Henry Manampiring', 'tahun_terbit' => 2018, 'kategori' => 'Pengembangan Diri'],
            ['id' => 4, 'judul' => 'Atomic Habits', 'penulis' => 'James Clear', 'tahun_terbit' => 2018, 'kategori' => 'Pengembangan Diri'],
            ['id' => 5, 'judul' => 'Clean Code', 'penulis' => 'Robert C. Martin', 'tahun_terbit' => 2008, 'kategori' => 'Teknologi'],
            ['id' => 6, 'judul' => 'Sapiens', 'penulis' => 'Yuval Noah Harari', 'tahun_terbit' => 2011, 'kategori' => 'Sejarah'],
        ];
    }

    public function index(): View
    {
        return view('buku.index', [
            'daftarBuku' => $this->semuaBuku(),
        ]);
    }

    public function show(int $id): Response
    {
        $buku = collect($this->semuaBuku())->firstWhere('id', $id);

        // Kalau tidak ketemu, tetap render view (ditangani @if) dengan status 404
        return response()->view('buku.show', [
            'buku' => $buku,
            'id' => $id,
        ], $buku ? 200 : 404);
    }
}
