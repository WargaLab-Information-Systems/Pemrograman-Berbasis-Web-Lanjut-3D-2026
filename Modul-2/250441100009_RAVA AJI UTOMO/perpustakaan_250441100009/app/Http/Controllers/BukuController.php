<?php

namespace App\Http\Controllers;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $daftarBuku = Buku::all();

        return view('buku.index', [
            'daftarBuku' => $daftarBuku,
        ]);
    }

    public function detail($id)
    {
        $buku = Buku::find($id);

        return view('buku.detail', [
            'buku' => $buku,
        ]);
    }
}