<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\View\View;

class BukuController extends Controller
{
    // Menampilkan daftar buku untuk siswa
    public function index(): View
    {
        $bukus = Buku::with('kategori')
            ->latest()
            ->paginate(10);

        return view('siswa.buku.index', compact('bukus'));
    }

    public function show(Buku $buku): View
    {
        $dataBuku = [
            'id' => $buku->id,
            'judul' => $buku->judul,
            'penulis' => $buku->penulis,
            'tahun_terbit' => $buku->tahun_terbit,
            'kategori' => $buku->kategori?->nama ?? '-',
        ];

        return view('buku.show', compact('dataBuku'));
    }
}
