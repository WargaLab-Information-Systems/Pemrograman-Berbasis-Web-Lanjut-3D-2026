<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BukuPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PetugasDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('beranda');
})->name('home');

Route::middleware('auth')->group(function () {

  
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/admin/dashboard', AdminDashboardController::class)
        ->middleware('permission:akses dashboard admin')->name('admin.dashboard');
    Route::get('/petugas/dashboard', PetugasDashboardController::class)
        ->middleware('permission:akses dashboard petugas')->name('petugas.dashboard');

   
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat profil sendiri')->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->middleware('permission:ubah profil sendiri')->name('profile.destroy');

   
    Route::middleware('permission:lihat daftar buku')->group(function () {
        Route::get('/buku', function () {
            return view('buku.index');
        })->name('buku.index');
        Route::get('/buku/{id}', [BukuPageController::class, 'show'])->whereNumber('id')->name('buku.show');
    });

    Route::post('/buku/{id}/pinjam', [PeminjamanController::class, 'ajukan'])
        ->middleware('permission:mengajukan peminjaman buku')->whereNumber('id')->name('peminjaman.ajukan');

    
    Route::get('/riwayat', [RiwayatController::class, 'index'])
        ->middleware('permission:melihat riwayat peminjaman sendiri')->name('riwayat.index');
    Route::get('/riwayat/{user}', [RiwayatController::class, 'show'])->name('riwayat.show');

  
    Route::middleware('permission:kelola data pengguna')->prefix('admin')->group(function () {
        Route::resource('pengguna', PenggunaController::class)
            ->except('show')->parameters(['pengguna' => 'pengguna']);
    });

   
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->middleware('permission:kelola peminjaman')->name('peminjaman.index');
    Route::post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])
        ->middleware('permission:memproses persetujuan peminjaman')->name('peminjaman.setujui');
    Route::post('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])
        ->middleware('permission:memproses persetujuan peminjaman')->name('peminjaman.tolak');

    Route::get('/pengembalian', [PengembalianController::class, 'index'])
        ->middleware('permission:kelola pengembalian')->name('pengembalian.index');
    Route::post('/pengembalian/{peminjaman}', [PengembalianController::class, 'store'])
        ->middleware('permission:mencatat pengembalian buku')->name('pengembalian.store');


    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat laporan perpustakaan')->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])
        ->middleware('permission:lihat laporan perpustakaan')->name('laporan.cetak');
});

require __DIR__.'/auth.php';
