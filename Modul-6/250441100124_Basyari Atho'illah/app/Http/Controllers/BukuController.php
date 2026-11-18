<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\View\View;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar semua buku beserta relasi kategorinya.
     */
    
    public function index(): View
    {
        // Mengambil kategori untuk pilihan formulir buku
        $kategoris = Kategori::orderBy('nama')->get();

        // Mengambil daftar buku untuk pengujian XSS
        $bukuUntukUjiXss = Buku::orderBy('id', 'desc')->get();

        return view('buku.index', [
            'kategoris' => $kategoris,
            'bukuUntukUjiXss' => $bukuUntukUjiXss,
        ]);
    }

    /**
     * Menampilkan detail buku berdasarkan ID.
     */
    public function show(int|string $id): View
    {
        // Mencari data buku berdasarkan ID beserta relasi kategorinya
        $book = Buku::with('kategoriRelasi')->find($id);

        return view('buku.show', [
            'book' => $book,
        ]);
    }

    /**
 * Demonstrasi SQL Injection (TIDAK AMAN).
 * Hanya digunakan untuk pengujian keamanan lokal.
 */
// public function cariTidakAman(Request $request): JsonResponse
// {
//     $kataKunci = $request->query('q', '');

//     // Query sengaja dibuat rentan untuk demonstrasi SQL Injection.
//     $sql = "SELECT * FROM bukus WHERE judul LIKE '%{$kataKunci}%'";

//     $hasil = DB::select($sql);

//     return response()->json([
//         'status' => 'success',
//         'data' => $hasil,
//     ]);
// }

public function cariTidakAman(Request $request): JsonResponse
{
    $kataKunci = $request->query('q', '');

    // Query menggunakan parameter binding agar aman dari SQL Injection.
    $sql = "SELECT * FROM bukus WHERE judul LIKE ?";

    $hasil = DB::select($sql, ['%' . $kataKunci . '%']);

    return response()->json([
        'status' => 'success',
        'data' => $hasil,
    ]);
}
}
