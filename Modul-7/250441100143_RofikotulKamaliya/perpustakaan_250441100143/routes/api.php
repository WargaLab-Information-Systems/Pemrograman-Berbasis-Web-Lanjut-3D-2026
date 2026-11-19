<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Buku;
use App\Models\Kategori;
use App\Http\Controllers\Api\BukuController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'permission:lihat daftar buku'])->group(function (){
    Route::get('/buku', [BukuController::class, 'index'])
        ->name('api.buku.index');

    Route::get('/buku/{buku}', [BukuController::class, 'show'])
        ->name('api.buku.show');
});

Route::middleware(['auth:sanctum', 'permission:kelola data buku'])->group(function () {
    Route::post('/buku', [BukuController::class, 'store'])
        ->name('api.buku.store');

    Route::put('/buku/{buku}', [BukuController::class, 'update'])
        ->name('api.buku.update');

    Route::patch('/buku/{buku}', [BukuController::class, 'update']);

    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])
        ->name('api.buku.destroy');
});

Route::middleware(['auth:sanctum', 'permission:lihat laporan perpustakaan'])->group(function () {
    Route::get('/rekap/buku-per-kategori', function () {
        return response()->json(
            Kategori::withCount('bukus')->get(),
            200
        );
    });

    Route::get('rekap/peminjaman-per-buku', function () {
        return response()->json(
            Buku::with('kategori')
                ->withCount('peminjamans')
                ->get(),
            200
        );
    });
});
