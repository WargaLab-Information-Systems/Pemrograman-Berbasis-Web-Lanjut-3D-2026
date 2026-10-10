<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $books = Buku::with('kategori')->get()->map(function($buku) {
            return [
                'id' => $buku->id,
                'judul' => $buku->judul,
                'penulis' => $buku->penulis,
                'tahun' => $buku->tahun_terbit,
                'kategori' => $buku->kategori->nama ?? 'Umum',
            ];
        });

        return view('buku.index', compact('books'));
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->find($id);

        $book = $buku ? [
            'id' => $buku->id,
            'judul' => $buku->judul,
            'penulis' => $buku->penulis,
            'tahun' => $buku->tahun_terbit,
            'kategori' => $buku->kategori->nama ?? 'Umum',
        ] : null;

        return view('buku.show', compact('book'));
    }
}