<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori; // 1. Pastikan model Kategori sudah di-import di atas
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class BukuController extends Controller
{
    public function home(): View
    {
        return view('home');
    }

    public function index(): View
    {
        return view('buku.index', [
            'buku'      => Buku::with('kategori')->get(),
            'kategoris' => Kategori::all(), // 2. Tambahkan baris ini agar variabel $kategoris terkirim ke view
        ]);
    }

    public function show(int $id): Response
    {
        $buku = Buku::with('kategori')->find($id);

        return response()->view('buku.show', [
            'buku' => $buku,
            'id'   => $id,
        ], $buku ? 200 : 404);
    }
}