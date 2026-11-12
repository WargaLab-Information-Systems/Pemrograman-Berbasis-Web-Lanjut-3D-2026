<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;

// Gunakan middleware ['web', 'auth'] agar membaca session login Breeze
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/buku', [BukuController::class, 'index'])->middleware('can:Lihat daftar buku');
    Route::post('/buku', [BukuController::class, 'store'])->middleware('can:Kelola data buku');
    Route::put('/buku/{id}', [BukuController::class, 'update'])->middleware('can:Kelola data buku');
    Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->middleware('can:Kelola data buku');
});