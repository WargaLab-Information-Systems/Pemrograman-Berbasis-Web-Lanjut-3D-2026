<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Models\Buku;
use App\Models\Kategori;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/buku', [BukuController::class, 'index']);
    Route::get('/buku/{buku}', [BukuController::class, 'show']);

    Route::middleware(['permission:manage_books'])->group(function () {
        Route::post('/buku', [BukuController::class, 'store']);
        Route::put('/buku/{buku}', [BukuController::class, 'update']);
        Route::delete('/buku/{buku}', [BukuController::class, 'destroy']);
    });
});

Route::get('/rekap/buku-per-kategori', function () {
    $data = Kategori::withCount('bukus')->get();

    return response()->json([
        'data' => $data,
    ], 200);
});

Route::get('/rekap/pemminjaman-per-buku', function () {
    $data = Buku::withCount('peminjamans')->get();

    return response()->json([
        'data' => $data,
    ], 200);
});