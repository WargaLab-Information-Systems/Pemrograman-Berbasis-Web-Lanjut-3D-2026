
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use App\Http\Controllers\Api\PeminjamanController;

Route::middleware('auth:sanctum')->group(function () {

    // ==================================
    // DATA BUKU
    // ==================================

    // Menampilkan daftar dan detail buku
    Route::get('/buku', [BukuController::class, 'index'])
        ->middleware('permission:lihat daftar buku');

    Route::get('/buku/{buku}', [BukuController::class, 'show'])
        ->middleware('permission:lihat daftar buku');

    // Menambahkan buku
    Route::post('/buku', [BukuController::class, 'store'])
        ->middleware('permission:kelola data buku');

    // Mengubah buku
    Route::put('/buku/{buku}', [BukuController::class, 'update'])
        ->middleware('permission:kelola data buku');

    Route::patch('/buku/{buku}', [BukuController::class, 'update'])
        ->middleware('permission:kelola data buku');

    // Menghapus buku
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])
        ->middleware('permission:kelola data buku');


    // ==================================
    // REKAP PERPUSTAKAAN
    // ==================================

    Route::get(
        '/rekap/buku-per-kategori',
        [RekapController::class, 'bukuPerKategori']
    )->middleware('permission:lihat laporan perpustakaan');

    Route::get(
        '/rekap/peminjaman-per-buku',
        [RekapController::class, 'peminjamanPerBuku']
    )->middleware('permission:lihat laporan perpustakaan');


    // ==================================
    // PEMINJAMAN UNTUK SISWA
    // ==================================

    // Melihat riwayat peminjaman sendiri
    Route::get('/peminjaman-saya', [PeminjamanController::class, 'indexSaya'])
        ->middleware('permission:melihat riwayat peminjaman sendiri');

    // Melihat detail peminjaman (dengan pengecekan identitas siswa)
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])
        ->middleware('permission:melihat status peminjaman sendiri');

    // Mengajukan peminjaman
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])
        ->middleware('permission:mengajukan peminjaman buku');

    // Mengajukan pengembalian oleh Siswa
    Route::patch('/peminjaman/{id}/ajukan-pengembalian', [PeminjamanController::class, 'ajukanPengembalian'])
        ->middleware('permission:melihat status peminjaman sendiri');


    // ==================================
    // PEMINJAMAN UNTUK PETUGAS
    // ==================================

    // Melihat seluruh pengajuan
    Route::get('/petugas/peminjaman', [PeminjamanController::class, 'indexPetugas'])
        ->middleware('permission:memproses persetujuan peminjaman');

    // Menyetujui peminjaman
    Route::patch('/petugas/peminjaman/{id}/setujui', [PeminjamanController::class, 'setujui'])
        ->middleware('permission:memproses persetujuan peminjaman');

    // Menolak peminjaman
    Route::patch('/petugas/peminjaman/{id}/tolak', [PeminjamanController::class, 'tolak'])
        ->middleware('permission:memproses persetujuan peminjaman');

    // Mengonfirmasi pengembalian
    Route::patch('/petugas/peminjaman/{id}/konfirmasi-pengembalian', [PeminjamanController::class, 'konfirmasiPengembalian'])
        ->middleware('permission:mencatat pengembalian buku');

    // Mencatat pengembalian langsung
    Route::patch('/petugas/peminjaman/{id}/catat-pengembalian', [PeminjamanController::class, 'catatPengembalian'])
        ->middleware('permission:mencatat pengembalian buku');

});
