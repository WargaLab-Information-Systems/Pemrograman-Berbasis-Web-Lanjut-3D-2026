<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    /**
     * Halaman Daftar Buku (/buku).
     *
     * Sumber data sekarang dari database (Eloquent), bukan array statis
     * seperti di Modul I. Bentuk data yang dikirim ke view sengaja dibuat
     * sama persis dengan Modul I (array asosiatif dengan key id, judul,
     * penulis, tahun, kategori) supaya view & Blade Component TIDAK perlu
     * diubah sama sekali.
     */
    public function index()
    {
        $daftarBuku = Buku::with('kategori')
            ->get()
            ->map(fn (Buku $buku) => [
                'id' => $buku->id,
                'judul' => $buku->judul,
                'penulis' => $buku->penulis,
                'tahun' => $buku->tahun_terbit,
                'kategori' => $buku->kategori->nama ?? '-',
            ])
            ->toArray();

        return view('buku.index', compact('daftarBuku'));
    }

    /**
     * Halaman Detail Buku (/buku/{id}).
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

        // $buku bernilai null jika data tidak ditemukan,
        // tetap ditangani dengan @if di view buku.detail (tidak berubah).
        return view('buku.detail', compact('buku'));
    }
}
