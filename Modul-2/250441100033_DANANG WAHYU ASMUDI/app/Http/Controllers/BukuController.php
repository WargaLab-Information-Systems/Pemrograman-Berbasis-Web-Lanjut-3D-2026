<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $bukuList = Buku::with('kategori')->get()->map(function ($buku) {
            return [
                'id' => $buku->id,
                'judul' => $buku->judul,
                'penulis' => $buku->penulis,
                'tahun_terbit' => $buku->tahun_terbit,
                'kategori' => $buku->kategori ? $buku->kategori->nama : 'Umum',
            ];
        });

        return view('buku.index', compact('bukuList'));
    }

    public function show($id)
    {
        $bukuModel = Buku::with('kategori')->find($id);

        $buku = null;
        if ($bukuModel) {
            $buku = [
                'id' => $bukuModel->id,
                'judul' => $bukuModel->judul,
                'penulis' => $bukuModel->penulis,
                'tahun_terbit' => $bukuModel->tahun_terbit,
                'kategori' => $bukuModel->kategori ? $bukuModel->kategori->nama : 'Umum',
                'deskripsi' => 'Buku ini tersedia dalam koleksi perpustakaan digital.'
            ];
        }

        return view('buku.show', compact('buku'));
    }
}