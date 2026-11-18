<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// modul 4
// Route::apiResource('buku', BukuController::class)->names('api.buku');
// end modul 4

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/buku', [BukuController::class, 'index'])
        ->middleware('permission:view books')
        ->name('api.buku.index');

    Route::get('/buku/{buku}', [BukuController::class, 'show'])
        ->middleware('permission:view books')
        ->name('api.buku.show');

    Route::post('/buku', [BukuController::class, 'store'])
        ->middleware('permission:manage books')
        ->name('api.buku.store');

    Route::put('/buku/{buku}', [BukuController::class, 'update'])
        ->middleware('permission:manage books')
        ->name('api.buku.update');

    Route::patch('/buku/{buku}', [BukuController::class, 'update'])
        ->middleware('permission:manage books');

    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])
        ->middleware('permission:manage books')
        ->name('api.buku.destroy');

});

Route::middleware([
    'auth:sanctum',
    'permission:view library reports',
])->group(function () {

    Route::get('/rekap/buku-per-kategori', [
        RekapController::class,
        'bukuPerKategori',
    ])->name('api.rekap.buku-per-kategori');

    Route::get('/rekap/peminjaman-per-buku', [
        RekapController::class,
        'peminjamanPerBuku',
    ])->name('api.rekap.peminjaman-per-buku');

});