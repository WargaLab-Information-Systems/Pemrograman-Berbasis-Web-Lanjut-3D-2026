<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    public function index()
    {
        $dataBuku = Buku::with('kategori')->get();

        $buku = [];

        foreach ($dataBuku as $item) {
            $buku[] = [
                'id' => $item->id,
                'judul' => $item->judul,
                'penulis' => $item->penulis,
                'tahun' => $item->tahun_terbit,
                'kategori' => $item->kategori->nama
            ];
        }

        return view('buku.index', compact('buku'));
    }

    public function show($id)
    {
        $item = Buku::with('kategori')->find($id);

        if ($item) {
            $dataBuku = [
                'id' => $item->id,
                'judul' => $item->judul,
                'penulis' => $item->penulis,
                'tahun' => $item->tahun_terbit,
                'kategori' => $item->kategori->nama
            ];
        } else {
            $dataBuku = null;
        }

        return view('buku.show', compact('dataBuku'));
    }

    public function cariSqlInjection(Request $request)
    {
        $judul = $request->query('judul');

        $buku = DB::select(
            'SELECT * FROM bukus WHERE judul = ?',
            [$judul]
        );

        return response()->json($buku);
    }

     public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        $buku->delete();

        return redirect('/buku')
            ->with('success', 'Buku berhasil dihapus');
    }
}