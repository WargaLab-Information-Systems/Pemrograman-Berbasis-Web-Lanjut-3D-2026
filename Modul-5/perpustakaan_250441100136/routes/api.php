<?php

use App\Http\Controllers\Api\BukuController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('buku', [BukuController::class, 'index'])->middleware('permission:lihat daftar buku');
    Route::get('buku/{buku}', [BukuController::class, 'show'])->middleware('permission:lihat daftar buku');

    Route::middleware(['permission:kelola data buku'])->group(function () {
        Route::post('buku', [BukuController::class, 'store']);
        Route::match(['put', 'patch'], 'buku/{buku}', [BukuController::class, 'update']);
        Route::delete('buku/{buku}', [BukuController::class, 'destroy']);
    });
});
