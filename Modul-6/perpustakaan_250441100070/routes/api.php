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
| tanpa izin yang sesuai, API membalas 403 (dalam format JSON).
|
| Modul 6 Soal 3: store/update/destroy memakai Gate "kelola-buku",
| menggantikan permission:kelola data buku yang dipakai sebelumnya, supaya
| aturan "siapa yang boleh mengelola buku" dipusatkan di satu Gate saja
| (lihat AppServiceProvider::boot()).
*/

Route::middleware(['web', 'auth'])->group(function () {

    // Melihat daftar & detail buku: tetap permission "lihat daftar buku"
    Route::apiResource('buku', BukuController::class)
        ->only(['index', 'show'])
        ->names('api.buku')
        ->middleware('permission:lihat daftar buku');

    // Tambah, ubah, hapus buku: sekarang lewat Gate "kelola-buku" (Soal 3)
    Route::apiResource('buku', BukuController::class)
        ->only(['store', 'update', 'destroy'])
        ->names('api.buku')
        ->middleware('can:kelola-buku');

    // Rekap: permission "lihat laporan perpustakaan"
    Route::prefix('rekap')
        ->middleware('permission:lihat laporan perpustakaan')
        ->group(function () {
            Route::get('buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
            Route::get('peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);
        });
});
