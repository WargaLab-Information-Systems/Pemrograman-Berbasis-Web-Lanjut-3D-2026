<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;

Route::apiResource('buku', BukuController::class);

Route::prefix('rekap')->group(function () {
    Route::get('/buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
    Route::get('/peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);
    Route::get('/pemminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);
});