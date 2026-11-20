<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBukuRequest;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BukuController extends Controller
{
    public function index(): View
    {
        $buku = Buku::with('kategori')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('buku.index', compact('buku'));
    }

    public function create(): View
    {
        $kategoris = Kategori::orderBy('nama')->get();

        return view('buku.create', compact('kategoris'));
    }

    public function store(StoreBukuRequest $request): RedirectResponse
    {
        Buku::create($request->validated());

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(int $id): View
    {
        $bukuModel = Buku::with('kategori')->find($id);

        if (! $bukuModel) {
            abort(404);
        }

        $buku = [
            'id' => $bukuModel->id,
            'judul' => $bukuModel->judul,
            'penulis' => $bukuModel->penulis,
            'tahun_terbit' => $bukuModel->tahun_terbit,
            'kategori' => $bukuModel->kategori?->nama ?? 'Tanpa Kategori',
            'kategori_id' => $bukuModel->kategori_id,
        ];

        return view('buku.detail', compact('buku'));
    }

    public function edit(int $id): View
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::orderBy('nama')->get();

        return view('buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $buku = Buku::findOrFail($id);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'integer', 'min:1900', 'max:'.date('Y')],
            'kategori_id' => ['required', 'exists:kategoris,id'],
        ]);

        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
