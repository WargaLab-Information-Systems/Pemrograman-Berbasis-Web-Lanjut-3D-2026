<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| middleware 'auth'        : hanya untuk pengguna yang sudah login
| middleware 'permission'  : pembatasan hak akses (Spatie Laravel Permission)
| middleware 'guest'       : ada di routes/auth.php (login & registrasi)
*/

// Halaman publik (Modul 1)
Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('auth')->group(function () {

    // /dashboard: mengarahkan pengguna ke halaman yang sesuai rolenya
    Route::get('/dashboard', [DashboardController::class, 'arahkan'])->name('dashboard');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('permission:akses dashboard admin')
        ->name('admin.dashboard');

    Route::get('/petugas/dashboard', [DashboardController::class, 'petugas'])
        ->middleware('permission:akses dashboard petugas')
        ->name('petugas.dashboard');

    // Buku (Modul 1-4)
    Route::middleware('permission:lihat daftar buku')->group(function () {
        Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
        Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
    });

    // Kelola pengguna dan role (khusus admin)
    Route::middleware('permission:kelola data pengguna')
        ->prefix('admin/users')
        ->name('admin.users.')
        ->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

    // Peminjaman: pengajuan oleh pengguna
    Route::post('/buku/{id}/ajukan', [PeminjamanController::class, 'ajukan'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('peminjaman.ajukan');

    // Peminjaman: dikelola admin/petugas
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->middleware('permission:kelola peminjaman')
        ->name('peminjaman.index');

    Route::post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.setujui');

    Route::post('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.tolak');

    // Pengembalian
    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalian'])
        ->middleware('permission:kelola pengembalian')
        ->name('pengembalian.index');

    Route::post('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'kembali'])
        ->middleware('permission:mencatat pengembalian buku')
        ->name('peminjaman.kembali');

    // Laporan
    Route::get('/laporan', LaporanController::class)
        ->middleware('permission:lihat laporan perpustakaan')
        ->name('laporan.index');

    // Status & riwayat peminjaman. Selain permission, akses dicek terhadap
    // identitas pengguna (UserPolicy@viewRiwayat) di RiwayatController.
    Route::get('/riwayat/{user}', [RiwayatController::class, 'show'])
        ->middleware('permission:melihat status peminjaman sendiri|melihat riwayat peminjaman sendiri')
        ->name('riwayat.show');

    // Profil sendiri (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat profil sendiri')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.update');
});

// Route login, registrasi, logout, dan reset password dari Laravel Breeze
require __DIR__.'/auth.php';
