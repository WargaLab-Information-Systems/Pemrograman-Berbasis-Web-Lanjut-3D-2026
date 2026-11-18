<?php

use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use App\Models\Kategori;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rute API untuk aplikasi perpustakaan digital (Modul 3).
| Seluruh rute di sini secara otomatis memiliki prefix '/api'.
|
*/

// Endpoint Agregasi / Rekap Data
Route::get('/rekap/buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
Route::get('/rekap/peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);
Route::get('/rekap/pemminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']); // Alias pencegah typo

// API Resource Controller untuk Pengelolaan Buku (5 route: index, store, show, update, destroy)
Route::apiResource('buku', BukuController::class);

// Endpoint Kategori untuk referensi formulir dropdown
Route::get('/kategori', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Daftar kategori berhasil diambil.',
        'data' => Kategori::orderBy('nama')->get(),
    ], 200);
});
