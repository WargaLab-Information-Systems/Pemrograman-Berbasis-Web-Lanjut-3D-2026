<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'penulis' => $item->penulis,
                'tahun_terbit' => $item->tahun_terbit,
                'kategori' => $item->kategori->nama,
            ];
        });

        return view('buku.index', compact('buku'));
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->find($id);

        if (! $buku) {
            return view('buku.show', [
                'dataBuku' => null,
            ]);
        }

        $dataBuku = [
            'id' => $buku->id,
            'judul' => $buku->judul,
            'penulis' => $buku->penulis,
            'tahun_terbit' => $buku->tahun_terbit,
            'kategori' => $buku->kategori->nama,
        ];

        return view('buku.show', compact('dataBuku'));
    }

    public function cari(Request $request): JsonResponse
    {
        $judul = $request->query('judul');
        $bukus = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");

        return response()->json($bukus);
    }
}
