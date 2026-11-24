<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BukuController extends Controller
{
    public function home(): View
    {
        return view('home');
    }

    /**
     * Tampilan daftar buku (Katalog) - Dapat diakses oleh Admin, Petugas, dan Siswa
     */
    public function index(Request $request): View
    {
        $query = Buku::with('kategori');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        return view('buku.index', [
            'buku'      => $query->latest()->get(),
            'kategoris' => Kategori::all(),
        ]);
    }

    /**
     * Halaman khusus pengelolaan buku - Hanya Admin & Petugas
     */
    public function kelola(): View
    {
        return view('buku.kelola', [
            'buku'      => Buku::with('kategori')->latest()->get(),
            'kategoris' => Kategori::all(),
        ]);
    }

    /**
     * Form tambah buku baru
     */
    public function create(): View
    {
        return view('buku.create', [
            'kategoris' => Kategori::all(),
        ]);
    }

    /**
     * Simpan buku baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id'  => ['required', 'exists:kategoris,id'],
            'judul'        => ['required', 'string', 'max:255'],
            'penulis'      => ['required', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'integer', 'between:1900,2030'],
        ]);

        Buku::create($validated);

        return redirect()->route('buku.kelola')->with('success', 'Buku baru berhasil ditambahkan.');
    }

    /**
     * Tampilan detail buku
     */
    public function show(int $id): Response|View
    {
        $buku = Buku::with(['kategori', 'peminjaman'])->find($id);

        if (!$buku) {
            abort(404, 'Buku tidak ditemukan.');
        }

        return view('buku.show', [
            'buku' => $buku,
            'id'   => $id,
        ]);
    }

    /**
     * Form ubah buku
     */
    public function edit(int $id): View
    {
        $buku = Buku::findOrFail($id);

        return view('buku.edit', [
            'buku'      => $buku,
            'kategoris' => Kategori::all(),
        ]);
    }

    /**
     * Perbarui data buku
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $buku = Buku::findOrFail($id);

        $validated = $request->validate([
            'kategori_id'  => ['required', 'exists:kategoris,id'],
            'judul'        => ['required', 'string', 'max:255'],
            'penulis'      => ['required', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'integer', 'between:1900,2030'],
        ]);

        $buku->update($validated);

        return redirect()->route('buku.kelola')->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Hapus buku
     */
    public function destroy(int $id): RedirectResponse
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.kelola')->with('success', 'Buku berhasil dihapus.');
    }
}
