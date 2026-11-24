<?php

use App\Http\Controllers\Api\BukuController;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {

    Route::get('/buku', [
        BukuController::class,
        'index'
    ])->middleware('permission:lihat daftar buku')
      ->name('api.buku.index');

    Route::get('/buku/{id}', [
        BukuController::class,
        'show'
    ])->middleware('permission:lihat daftar buku')
      ->name('api.buku.show');

    Route::middleware('can:kelola-buku')->group(function () {

        Route::post('/buku', [
            BukuController::class,
            'store'
        ])->name('api.buku.store');

        Route::match(['put', 'patch'], '/buku/{id}', [
            BukuController::class,
            'update'
        ])->name('api.buku.update');

        Route::delete('/buku/{id}', [
            BukuController::class,
            'destroy'
        ])->name('api.buku.destroy');
    });

    Route::middleware('permission:lihat laporan perpustakaan')
        ->group(function () {

            Route::get('/rekap/buku-per-kategori', function () {
                return response()->json([
                    'data' => Kategori::withCount('bukus')->get(),
                ], 200);
            });

            Route::get('/rekap/peminjaman-per-buku', function () {
                return response()->json([
                    'data' => Buku::with('kategori')
                        ->withCount('peminjamans')
                        ->get(),
                ], 200);
            });
        });
});