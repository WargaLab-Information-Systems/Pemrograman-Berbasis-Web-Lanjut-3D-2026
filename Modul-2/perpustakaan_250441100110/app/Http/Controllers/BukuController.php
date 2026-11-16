<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    private function formatBuku(Buku $b): array
    {
        return [
            'id' => $b->id,
            'judul' => $b->judul,
            'penulis' => $b->penulis,
            'tahun' => $b->tahun_terbit,
            'kategori' => $b->kategori->nama,
        ];
    }

    public function index()
    {
        $buku = Buku::with('kategori')->get()
            ->map(fn ($b) => $this->formatBuku($b))
            ->all();

        return view('buku.index', compact('buku'));
    }

    public function detail($id)
    {
        $data = Buku::with('kategori')->find($id);

        $buku = $data ? $this->formatBuku($data) : null;

        return view('buku.detail', compact('buku'));
    }
}