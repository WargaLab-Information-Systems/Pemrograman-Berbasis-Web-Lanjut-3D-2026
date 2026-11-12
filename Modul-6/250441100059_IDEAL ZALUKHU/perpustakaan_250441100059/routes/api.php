
<?php

use App\Http\Controllers\Api\BukuController;
use Illuminate\Support\Facades\Route;

// Melihat daftar dan detail buku
Route::middleware(['auth:sanctum', 'permission:books.view'])->group(function () {
    Route::get('/buku', [BukuController::class, 'index']);
    Route::get('/buku/{id}', [BukuController::class, 'show']);
});

// Mengelola buku: Admin dan Petugas
Route::middleware(['auth:sanctum', 'permission:books.manage'])->group(function () {
    Route::post('/buku', [BukuController::class, 'store']);
    Route::put('/buku/{id}', [BukuController::class, 'update']);
    Route::patch('/buku/{id}', [BukuController::class, 'update']);
    Route::delete('/buku/{id}', [BukuController::class, 'destroy']);
});
