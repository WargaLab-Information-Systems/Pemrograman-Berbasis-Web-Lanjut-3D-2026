<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController as ApiBukuController;
use App\Http\Controllers\Api\RekapController as ApiRekapController;

Route::apiResource('buku', ApiBukuController::class)->names('api.buku');

Route::get('/rekap/buku-per-kategori', [ApiRekapController::class, 'rekapBukuPerKategori']);
Route::get('/rekap/peminjaman-per-buku', [ApiRekapController::class, 'rekapPeminjamanPerBuku']);