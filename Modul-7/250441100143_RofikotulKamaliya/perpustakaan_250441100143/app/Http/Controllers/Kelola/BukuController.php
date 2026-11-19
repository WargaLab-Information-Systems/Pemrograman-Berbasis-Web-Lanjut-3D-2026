<?php

namespace App\Http\Controllers\Kelola;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use App\Models\Kategori;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::with('kategori')->withCount('peminjamans')->orderBy('judul')->get();

        return view('kelola.buku.index', compact('bukus'));
    }

    public function create()
    {
        return view('kelola.buku.create', [
            'kategoris' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function store(StoreBukuRequest $request)
    {
        Buku::create($request->validated());

        return redirect()->route('kelola.buku.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        return view('kelola.buku.edit', [
            'buku' => $buku,
            'kategoris' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function update(UpdateBukuRequest $request, Buku $buku)
    {
        $buku->update($request->validated());

        return redirect()->route('kelola.buku.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->peminjamans()->exists()) {
            return redirect()->route('kelola.buku.index')
                ->with('error', 'Buku tidak bisa dihapus karena sudah memiliki riwayat peminjaman.');
        }

        $buku->delete();

        return redirect()->route('kelola.buku.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
