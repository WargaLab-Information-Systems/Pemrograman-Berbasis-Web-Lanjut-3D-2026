<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Menampilkan seluruh data buku
    public function index(Request $request)
    {
        $bukus = Buku::with('kategori')
            ->latest()
            ->paginate(10);

        $kategoris = Kategori::all();
        $bukuEdit = null;

        if ($request->filled('edit')) {
            abort_unless($request->user()->can('books.manage'), 403);

            $bukuEdit = Buku::findOrFail($request->integer('edit'));

            if ($bukuEdit->hasActivePeminjamans()) {
                return redirect()
                    ->route('petugas.buku.index')
                    ->with('error', 'Buku tidak dapat diedit karena masih memiliki peminjaman aktif.');
            }
        }

        return view('petugas.buku.index', compact('bukus', 'kategoris', 'bukuEdit'));
    }

    // Menampilkan halaman tambah buku
    public function create()
    {
        $kategoris = Kategori::all();

        return view('petugas.buku.create', compact('kategoris'));
    }

    // Menyimpan buku baru
    public function store(StoreBukuRequest $request)
    {
        Buku::create($request->validated());

        return redirect()
            ->route('petugas.buku.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    // Mengarahkan ke formulir edit buku pada halaman manajemen buku
    public function edit(string $id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->hasActivePeminjamans()) {
            return redirect()
                ->route('petugas.buku.index')
                ->with('error', 'Buku tidak dapat diedit karena masih memiliki peminjaman aktif.');
        }

        return redirect()->route('petugas.buku.index', ['edit' => $buku->id]);
    }

    // Memperbarui data buku
    public function update(UpdateBukuRequest $request, string $id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->hasActivePeminjamans()) {
            return redirect()
                ->route('petugas.buku.index')
                ->with('error', 'Buku tidak dapat diedit karena masih memiliki peminjaman aktif.');
        }

        $buku->update($request->validated());

        return redirect()
            ->route('petugas.buku.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    // Menghapus buku
    public function destroy(string $id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->hasPeminjamans()) {
            return redirect()
                ->route('petugas.buku.index')
                ->with('error', 'Buku tidak dapat dihapus karena sudah memiliki riwayat peminjaman.');
        }

        $buku->delete();

        return redirect()
            ->route('petugas.buku.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
