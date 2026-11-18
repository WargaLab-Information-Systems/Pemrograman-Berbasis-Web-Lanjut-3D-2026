<?php

use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use Illuminate\Support\Facades\Route;

Route::apiResource('buku', BukuController::class);

Route::prefix('rekap')->group(function () {
    Route::get('buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
    Route::get('peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);
    // alias sesuai penulisan di soal (typo "pemminjaman")
    Route::get('pemminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);
    Route::apiResource('buku', BukuController::class);
});