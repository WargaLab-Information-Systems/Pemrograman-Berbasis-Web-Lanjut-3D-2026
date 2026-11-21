<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Models\Kategori;

// --- SOAL 3: Tambahkan middleware 'web' dan 'auth' agar API membaca sesi login browser ---
Route::apiResource('buku', BukuController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['web', 'auth', 'can:kelola-buku']);

// Rute baca data (index, show) dibiarkan terbuka
Route::apiResource('buku', BukuController::class)
    ->except(['store', 'update', 'destroy']);

Route::get('/kategori', function () {
    $kategori = Kategori::all();
    return response()->json([
        'message' => 'Berhasil mengambil data kategori',
        'data' => $kategori
    ], 200);
});