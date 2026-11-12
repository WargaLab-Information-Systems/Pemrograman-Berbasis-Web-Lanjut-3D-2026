<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * SOAL 1 (A2) -- SENGAJA RENTAN. Nilai $judul dari query string digabungkan
 * langsung ke dalam string SQL, sehingga pengguna bisa menyisipkan potongan
 * SQL miliknya sendiri (SQL Injection). Jangan dipakai di luar latihan ini.
 */
class BukuCariController extends Controller
{
    public function cari(Request $request): JsonResponse
    {
        $judul = $request->query('judul', '');

        // RENTAN: $judul ditempel langsung ke query, tanpa disaring.
        $hasil = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");

        return response()->json($hasil);
    }
}
