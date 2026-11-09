<?php

use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('buku', BukuController::class)->names('api.buku');

// Endpoint rekap agregasi
Route::prefix('rekap')->name('api.rekap.')->group(function () {
    Route::get('buku-per-kategori', [RekapController::class, 'bukuPerKategori'])->name('buku-per-kategori');
    Route::get('peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku'])->name('peminjaman-per-buku');
});
