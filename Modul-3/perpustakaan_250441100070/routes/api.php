<?php
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use Illuminate\Support\Facades\Route;

// Gunakan App\Http\Controllers\Api\BukuController
Route::apiResource('buku', BukuController::class);

Route::get('buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
Route::get('peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);