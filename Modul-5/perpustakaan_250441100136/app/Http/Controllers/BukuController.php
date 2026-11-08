<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::all(); // Mengambil data buku dari database

        return view('buku.index', compact('buku'));
    }

    // Method lainnya...
}
