<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Models\Buku;
use App\Models\Kategori;

// API Perpustakaan Modul 5 
// Menggunakan session login Laravel Breeze melalui middleware web. emua endpoint di bawah wajib login.

Route::middleware(['web', 'auth'])->group(function () {

    // GET: Admin, Petugas, dan Siswa boleh melihat daftar buku
    Route::get('/buku', [BukuController::class, 'index'])
        ->middleware('permission:lihat daftar buku')
        ->name('api.buku.index');

    // GET: Admin, Petugas, dan Siswa boleh melihat detail buku
    Route::get('/buku/{id}', [BukuController::class, 'show'])
        ->middleware('permission:lihat daftar buku')
        ->name('api.buku.show');

    // Operasi kelola buku hanya untuk Admin dan Petugas
    Route::middleware('permission:kelola data buku')->group(function () {

        // POST: Menambah buku
        Route::post('/buku', [BukuController::class, 'store'])
            ->name('api.buku.store');

        // PUT/PATCH: Mengubah buku
        Route::match(['put', 'patch'], '/buku/{id}', [BukuController::class, 'update'])
            ->name('api.buku.update');

        // DELETE: Menghapus buku
        Route::delete('/buku/{id}', [BukuController::class, 'destroy'])
            ->name('api.buku.destroy');
    });

    // Rekap hanya untuk Admin dan Petugas
    Route::middleware('permission:lihat laporan perpustakaan')->group(function () {

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