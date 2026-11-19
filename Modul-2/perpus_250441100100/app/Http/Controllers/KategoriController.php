<?php

namespace App\Http\Controllers;

use App\Models\Kategori;

class KategoriController extends Controller
{
    public function apiIndex()
    {
        return response()->json(
            Kategori::all(['id', 'nama'])
        );
    }
}