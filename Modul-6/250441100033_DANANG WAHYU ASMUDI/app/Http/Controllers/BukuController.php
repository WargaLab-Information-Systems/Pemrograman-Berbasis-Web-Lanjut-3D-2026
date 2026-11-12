<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku.
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        $bukus = $query->latest('id')->paginate(10);
        $kategoris = Kategori::all();

        return view('buku.index', compact('bukus', 'kategoris'));
    }

    /**
     * Form tambah buku baru.
     * Hanya bisa diakses oleh admin & petugas lewat middleware can:kelola-buku.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('buku.create', compact('kategoris'));
    }

    /**
     * Menyimpan buku baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'  => 'required|exists:kategoris,id',
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|digits:4',
        ]);

        Buku::create($validated);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail buku.
     */
    public function show($id)
    {
        $buku = Buku::with('kategori')->find($id);
        return view('buku.show', compact('buku'));
    }

    /**
     * Form edit data buku.
     */
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    /**
     * Memperbarui data buku.
     */
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $validated = $request->validate([
            'kategori_id'  => 'required|exists:kategoris,id',
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|digits:4',
        ]);

        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    /**
     * Menghapus data buku.
     * 
     * SOAL 2 (c): Penghapusan dilakukan menggunakan metode DELETE yang dilindungi CSRF token.
     */
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus dari sistem!');
    }

    /**
     * Pencarian Buku - SOAL 1 (A2) [RENTAN SQL INJECTION]
     * Menggabungkan variabel string langsung ke query SQL tanpa parameter binding.
     */
    public function cari(Request $request)
    {
        $judul = $request->query('judul', '');

        // Query rentan SQL Injection sesuai spesifikasi Soal 1 (A2)
        $hasil = DB::select("SELECT * FROM bukus WHERE judul = ?", [$judul]);
        return response()->json($hasil);
    }
}
