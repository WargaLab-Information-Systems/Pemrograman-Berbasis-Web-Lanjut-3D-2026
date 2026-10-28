<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        return view('buku.index');
    }

    public function show(int $id)
    {
        $detailBuku = Buku::find($id);

        return view('buku.show', compact('detailBuku'));
    }
}
