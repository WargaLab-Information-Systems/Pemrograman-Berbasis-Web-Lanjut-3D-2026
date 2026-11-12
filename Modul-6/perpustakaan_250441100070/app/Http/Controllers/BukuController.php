<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;

class BukuController extends Controller
{
    /**
     * Halaman Daftar Buku (/buku).
     *
     * $bukuTerbaru ditambahkan untuk tugas Modul 6: bagian "5 Buku Terbaru"
     * dirender lewat Blade (bukan lewat JavaScript seperti tabel utama),
     * supaya audit escaping Blade bisa dibuktikan di halaman ini. File
     * controller ini TIDAK berubah lagi di tahap-tahap berikutnya.
     */
    public function index()
    {
        $kategoris = Kategori::orderBy('nama')->get(['id', 'nama']);
        $bukuTerbaru = Buku::latest()->take(5)->get();

        return view('buku.index', compact('kategoris', 'bukuTerbaru'));
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
