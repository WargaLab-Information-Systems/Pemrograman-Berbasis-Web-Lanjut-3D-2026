<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Tampilkan daftar buku & form tambah (UI Grid).
     */
    public function index()
    {
        $kategoris = Kategori::all();
        $buku = Buku::with('kategori')->get();

        return view('buku.index', compact('kategoris', 'buku'));
    }

    /**
     * Tampilkan form pembuatan buku baru (opsional jika menggunakan halaman terpisah).
     */
    public function create()
    {
        // Form tambah buku berada di halaman daftar buku (Manajemen Buku).
        return redirect()->route('buku.index');
    }

    /**
     * Simpan buku baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'  => 'required|exists:kategoris,id',
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4',
        ]);

        Buku::create($validated);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail buku.
     */
    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);

        return view('buku.detail', compact('buku'));
    }

    /**
     * Tampilkan form edit buku.
     */
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all();

        return view('buku.edit', compact('buku', 'kategoris'));
    }

    /**
     * Update data buku di database.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kategori_id'  => 'required|exists:kategoris,id',
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Hapus buku dari database.
     */
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}