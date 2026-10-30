<?php

use App\Http\Controllers\Api\BukuController;
use Illuminate\Support\Facades\Route;

// Route API Resource (5 route bawaan)
Route::apiResource('buku', BukuController::class);

// Route Rekap Agregasi
Route::get('/rekap/buku-per-kategori', [BukuController::class, 'rekapBukuPerKategori']);
Route::get('/rekap/peminjaman-per-buku', [BukuController::class, 'rekapPeminjamanPerBuku']);
