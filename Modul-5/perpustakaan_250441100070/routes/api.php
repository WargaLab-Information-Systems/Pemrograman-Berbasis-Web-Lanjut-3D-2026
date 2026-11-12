<?php

use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (prefix /api)
|--------------------------------------------------------------------------
| Modul 5: API dilindungi. Halaman /buku memanggil API dari browser yang
| sudah login, jadi API memakai session yang sama ('web' + 'auth'). Token CSRF
| dikirim oleh buku.js lewat header X-CSRF-TOKEN. Tanpa login, API membalas 401;
| tanpa permission yang sesuai, API membalas 403 (dalam format JSON).
*/

Route::middleware(['web', 'auth'])->group(function () {

    // Melihat daftar & detail buku: permission "lihat daftar buku"
    Route::apiResource('buku', BukuController::class)
        ->only(['index', 'show'])
        ->names('api.buku')
        ->middleware('permission:lihat daftar buku');

    // Tambah, ubah, hapus buku: permission "kelola data buku"
    Route::apiResource('buku', BukuController::class)
        ->only(['store', 'update', 'destroy'])
        ->names('api.buku')
        ->middleware('permission:kelola data buku');

    // Rekap: permission "lihat laporan perpustakaan"
    Route::prefix('rekap')
        ->middleware('permission:lihat laporan perpustakaan')
        ->group(function () {
            Route::get('buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
            Route::get('peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);
        });
});
