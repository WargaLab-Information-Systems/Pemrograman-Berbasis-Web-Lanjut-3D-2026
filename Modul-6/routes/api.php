<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;

Route::middleware(['web', 'auth'])->group(function () {
    // Dibuka untuk Admin, Petugas, dan Siswa
    Route::middleware('permission:lihat daftar buku')->group(function () {
        Route::get('/buku', [BukuController::class, 'index']);
        Route::get('/buku/{id}', [BukuController::class, 'show']);
    });

    // Khusus Admin dan Petugas
    Route::middleware('permission:kelola data buku')->group(function () {
        Route::post('/buku', [BukuController::class, 'store']);
        Route::put('/buku/{id}', [BukuController::class, 'update']);
        Route::delete('/buku/{id}', [BukuController::class, 'destroy']);
    });
});