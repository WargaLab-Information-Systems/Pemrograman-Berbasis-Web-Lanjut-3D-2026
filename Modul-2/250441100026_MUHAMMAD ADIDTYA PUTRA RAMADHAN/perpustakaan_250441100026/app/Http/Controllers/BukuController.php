<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    // HAPUS array private $dataBuku = [...] karena sekarang pakai database

    public function index()
    {
        // Mengambil data dari Database, lalu ubah ke format array (agar View lama tidak error)
        $dataDariDb = Buku::with('kategori')->get();
        $bukuArray = [];
        
        foreach ($dataDariDb as $b) {
            $bukuArray[] = [
                'id' => $b->id,
                'judul' => $b->judul,
                'penulis' => $b->penulis,
                'tahun_terbit' => $b->tahun_terbit,
                'kategori' => $b->kategori->nama, // Ambil nama dari relasi tabel kategori
            ];
        }

        return view('buku.index', ['buku' => $bukuArray]);
    }

    public function show($id)
    {
        // Mencari buku berdasarkan ID di Database
        $b = Buku::with('kategori')->find($id);
        
        $bukuDitemukan = null;
        if ($b) {
            // Ubah format ke array lagi agar View lama tidak error
            $bukuDitemukan = [
                'id' => $b->id,
                'judul' => $b->judul,
                'penulis' => $b->penulis,
                'tahun_terbit' => $b->tahun_terbit,
                'kategori' => $b->kategori->nama,
            ];
        }

        return view('buku.detail', ['buku' => $bukuDitemukan]);
    }
}