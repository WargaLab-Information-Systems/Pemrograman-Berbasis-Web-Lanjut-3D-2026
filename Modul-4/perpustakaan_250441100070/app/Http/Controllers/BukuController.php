<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;

class BukuController extends Controller
{
    /**
     * Halaman Daftar Buku (/buku).
     *
     * Sejak Modul 4, daftar buku TIDAK lagi dikirim dari sini. Tabelnya diisi
     * oleh JavaScript lewat GET /api/buku. Controller hanya mengirim daftar
     * kategori untuk pilihan (dropdown) pada formulir.
     */
    public function index()
    {
        $kategoris = Kategori::orderBy('nama')->get(['id', 'nama']);

        return view('buku.index', compact('kategoris'));
    }

    /**
     * Halaman Detail Buku (/buku/{id}). Tidak berubah dari Modul 2.
     */
    public function show($id)
    {
        $bukuModel = Buku::with('kategori')->find($id);

        $buku = $bukuModel ? [
            'id' => $bukuModel->id,
            'judul' => $bukuModel->judul,
            'penulis' => $bukuModel->penulis,
            'tahun' => $bukuModel->tahun_terbit,
            'kategori' => $bukuModel->kategori->nama ?? '-',
        ] : null;

        return view('buku.detail', compact('buku'));
    }
}
