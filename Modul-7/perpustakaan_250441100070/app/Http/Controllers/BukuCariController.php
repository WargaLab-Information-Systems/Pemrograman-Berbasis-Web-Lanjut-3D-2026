<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * SOAL 2 (b): diperbaiki dengan parameter binding ("?"). Nilai $judul
 * dikirim terpisah dari teks query, sehingga driver database menatanya
 * sebagai DATA, bukan sebagai bagian dari perintah SQL. Payload seperti
 * ' OR '1'='1 tidak lagi berpengaruh -- hanya dicocokkan sebagai teks biasa.
 */
class BukuCariController extends Controller
{
    public function cari(Request $request): JsonResponse
    {
        $judul = $request->query('judul', '');

        $hasil = DB::select('SELECT * FROM bukus WHERE judul = ?', [$judul]);

        return response()->json($hasil);
    }
}
