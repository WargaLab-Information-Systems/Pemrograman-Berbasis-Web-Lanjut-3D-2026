<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // A2

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::with('kategori')->latest()->get();
        $kategoris = Kategori::all();

        return view('buku.index', compact('bukus', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|numeric',
        ]);

        Buku::create([
            'judul' => $request->judul,
            'kategori_id' => $request->kategori_id,
            'penulis' => $request->penulis,
            'tahun_terbit' => $request->tahun_terbit,
        ]);

        return redirect()->back()->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function show($id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.show', compact('buku'));
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all();

        return view('buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|numeric',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update([
            'judul' => $request->judul,
            'kategori_id' => $request->kategori_id,
            'penulis' => $request->penulis,
            'tahun_terbit' => $request->tahun_terbit,
        ]);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil dihapus.');
    }

    // A2
    public function cari(Request $request)
    {
        $judul = $request->query('judul');
        $bukus = Buku::where('judul', $judul)->get();

        // $bukus = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");
        // $bukus = DB::select('SELECT * FROM bukus WHERE judul = ?', [$judul]);
        return response()->json($bukus);
    }
}