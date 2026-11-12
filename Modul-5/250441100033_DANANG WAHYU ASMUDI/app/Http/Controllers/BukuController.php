<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Http\Resources\BukuResource;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
        }

        $buku = $query->latest('id')->paginate(10);

        return BukuResource::collection($buku);
    }
}
