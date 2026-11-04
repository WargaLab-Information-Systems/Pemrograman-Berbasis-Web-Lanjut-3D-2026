<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Contracts\View\View;

class BukuController extends Controller
{
    private function dataBuku(): array
    {
        return Buku::with('kategori')
            ->orderBy('id')
            ->get()
            ->map(fn ($buku) => [
                'id'           => $buku->id,
                'judul'        => $buku->judul,
                'penulis'      => $buku->penulis,
                'tahun_terbit' => $buku->tahun_terbit,
                'kategori'     => $buku->kategori->nama,
            ])
            ->all();
    }

    public function index(): View
    {
        $daftarBuku = $this->dataBuku();

        return view('buku.index', compact('daftarBuku'));
    }

    public function show(int $id): View
    {
        $buku = collect($this->dataBuku())->firstWhere('id', $id);

        return view('buku.show')->with('buku', $buku);
    }
}