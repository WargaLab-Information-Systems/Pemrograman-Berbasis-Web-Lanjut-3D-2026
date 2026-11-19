<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Daftar buku (read-only) untuk semua pengguna yang punya permission "lihat daftar buku".
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori')->orderBy('judul');

        if ($request->filled('cari')) {
            $cari = $request->string('cari')->toString();
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        $bukus = $query->get();

        return view('buku.index', compact('bukus'));
    }

    public function show(Buku $buku)
    {
        $buku->load('kategori');

        return view('buku.show', compact('buku'));
    }
}
