<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// Route Dashboard Utama (Redirect otomatis sesuai role/permission atau Dashboard Siswa)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Group Route yang Memerlukan Autentikasi
Route::middleware('auth')->group(function () {

    // ==========================================
    // 1. DASHBOARD BERDASARKAN ROLE & PERMISSION
    // ==========================================
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('permission:Akses dashboard admin')
        ->name('admin.dashboard');

    Route::get('/petugas/dashboard', [DashboardController::class, 'petugas'])
        ->middleware('permission:Akses dashboard petugas')
        ->name('petugas.dashboard');

    // ==========================================
    // 2. KATALOG BUKU & DETAIL (Semua Role)
    // ==========================================
    Route::get('/buku', [BukuController::class, 'index'])
        ->middleware('permission:Lihat daftar buku')
        ->name('buku.index');

    Route::get('/buku/{id}', [BukuController::class, 'show'])
        ->whereNumber('id')
        ->middleware('permission:Lihat daftar buku')
        ->name('buku.show');

    // ==========================================
    // 3. KELOLA DATA BUKU (Admin & Petugas)
    // ==========================================
    Route::middleware('permission:Kelola data buku')->group(function () {
        Route::get('/kelola-buku', [BukuController::class, 'kelola'])->name('buku.kelola');
        Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->whereNumber('id')->name('buku.edit');
        Route::put('/buku/{id}', [BukuController::class, 'update'])->whereNumber('id')->name('buku.update');
        Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->whereNumber('id')->name('buku.destroy');
    });

    // ==========================================
    // 4. MANAJEMEN PENGGUNA & ROLE (Admin Saja)
    // ==========================================
    Route::middleware(['permission:Kelola data pengguna', 'permission:Kelola role pengguna'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // ==========================================
    // 5. PEMINJAMAN & PENGEMBALIAN BUKU
    // ==========================================
    // Pengajuan Peminjaman (Admin, Petugas, Siswa)
    Route::middleware('permission:Mengajukan peminjaman buku')->group(function () {
        Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    });

    // Riwayat Peminjaman Pribadi (Admin, Petugas, Siswa)
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayatSaya'])
        ->middleware('permission:Melihat riwayat peminjaman sendiri')
        ->name('peminjaman.riwayat');

    // Detail Peminjaman (Proteksi data pribadi berbasis identitas)
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])
        ->whereNumber('id')
        ->name('peminjaman.show');

    // Kelola Transaksi Peminjaman (Admin & Petugas)
    Route::middleware('permission:Kelola peminjaman')->group(function () {
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    });

    // Persetujuan Peminjaman (Admin & Petugas)
    Route::middleware('permission:Memproses persetujuan peminjaman')->group(function () {
        Route::post('/peminjaman/{id}/setujui', [PeminjamanController::class, 'setujui'])->whereNumber('id')->name('peminjaman.setujui');
        Route::post('/peminjaman/{id}/tolak', [PeminjamanController::class, 'tolak'])->whereNumber('id')->name('peminjaman.tolak');
    });

    // Pengembalian Buku (Admin & Petugas)
    Route::middleware('permission:Mencatat pengembalian buku')->group(function () {
        Route::post('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->whereNumber('id')->name('peminjaman.kembalikan');
    });

    // ==========================================
    // 6. LAPORAN PERPUSTAKAAN (Admin & Petugas)
    // ==========================================
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:Lihat laporan perpustakaan')
        ->name('laporan.index');

    // ==========================================
    // 7. PROFIL PENGGUNA (Semua User)
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:Lihat profil sendiri')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:Ubah profil sendiri')
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
