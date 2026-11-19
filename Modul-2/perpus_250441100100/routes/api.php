<?php

use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;

// Route API menggunakan session web agar dapat dipanggil dari halaman Blade
// yang menggunakan login Breeze. Middleware permission tetap diperiksa server.
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/kategori', [KategoriController::class, 'apiIndex'])
        ->middleware('permission:lihat daftar buku');

    Route::get('/buku', [BukuController::class, 'index'])
        ->middleware('permission:lihat daftar buku');

    Route::get('/buku/{id}', [BukuController::class, 'show'])
        ->middleware('permission:lihat daftar buku');

    Route::post('/buku', [BukuController::class, 'store'])
        ->middleware('permission:kelola data buku');

    Route::put('/buku/{id}', [BukuController::class, 'update'])
        ->middleware('permission:kelola data buku');

    Route::delete('/buku/{id}', [BukuController::class, 'destroy'])
        ->middleware('permission:kelola data buku');

    Route::get('/rekap/buku-per-kategori', [RekapController::class, 'bukuPerKategori'])
        ->middleware('permission:lihat laporan perpustakaan');

    Route::get('/rekap/peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku'])
        ->middleware('permission:lihat laporan perpustakaan');
});
