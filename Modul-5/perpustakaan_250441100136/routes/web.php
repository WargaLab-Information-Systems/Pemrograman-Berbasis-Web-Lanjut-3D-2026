<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Utama Dialihkan ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Proteksi Autentikasi
Route::middleware(['auth'])->group(function () {

    // CENTRAL REDIRECT DASHBOARD (MANDATORI NAME 'dashboard')
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        return redirect()->route('buku.index');
    })->name('dashboard');

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Siswa - Daftar Buku & Riwayat Peminjaman
    Route::get('/buku', [BukuController::class, 'index'])
        ->middleware('permission:lihat daftar buku')
        ->name('buku.index');

    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayatSaya'])
        ->middleware('permission:melihat riwayat peminjaman sendiri')
        ->name('peminjaman.riwayat');

    // Siswa/User - Mengajukan Peminjaman Buku
    Route::post('/peminjaman/ajukan', [PeminjamanController::class, 'ajukan'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('peminjaman.ajukan');

    // Dashboard Admin
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('permission:akses dashboard admin')
        ->name('admin.dashboard');

    // Dashboard Petugas
    Route::get('/petugas/dashboard', [DashboardController::class, 'petugas'])
        ->middleware('permission:akses dashboard petugas')
        ->name('petugas.dashboard');

    // Kelola Data Buku (Web Controller)
    Route::middleware(['permission:kelola data buku'])->group(function () {
        Route::resource('buku-admin', BukuController::class)->except(['index', 'show']);
    });

    // Kelola Pengguna (Khusus Admin)
    Route::middleware(['permission:kelola data pengguna'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Kelola Peminjaman
    Route::middleware(['permission:kelola peminjaman'])->group(function () {
        Route::get('/kelola-peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/kelola-peminjaman/{id}/proses', [PeminjamanController::class, 'proses'])->name('peminjaman.proses');
    });

    // Laporan Perpustakaan (Admin & Petugas)
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat laporan perpustakaan')
        ->name('laporan.index');
});

require __DIR__.'/auth.php';
