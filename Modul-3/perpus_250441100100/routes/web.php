<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PerpustakaanAccessController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

/*
| PENGALIHAN DASHBOARD BERDASARKAN ROLE
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

/*
| ROUTE YANG MEMBUTUHKAN LOGIN
*/
Route::middleware(['auth'])->group(function () {


    Route::get('/admin/dashboard', [PerpustakaanAccessController::class, 'dashboardAdmin'])
        ->middleware('permission:akses dashboard admin')
        ->name('admin.dashboard');

    Route::get('/petugas/dashboard', [PerpustakaanAccessController::class, 'dashboardPetugas'])
        ->middleware('permission:akses dashboard petugas')
        ->name('petugas.dashboard');

    // Dashboard siswa tidak punya permission khusus pada tabel hak akses,  
    Route::get('/siswa/dashboard', [PerpustakaanAccessController::class, 'dashboardSiswa'])
        ->middleware('role:siswa')
        ->name('siswa.dashboard');

    /* DAFTAR BUKU (semua role) */

    Route::get('/buku', [BukuController::class, 'index'])
        ->middleware('permission:lihat daftar buku')
        ->name('buku.index');

    /* KELOLA DATA BUKU (ADMIN & PETUGAS) */

    Route::get('/buku/create', [BukuController::class, 'create'])
        ->middleware('permission:kelola data buku')
        ->name('buku.create');

    Route::post('/buku', [BukuController::class, 'store'])
        ->middleware('permission:kelola data buku')
        ->name('buku.store');

    Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])
        ->middleware('permission:kelola data buku')
        ->name('buku.edit');

    Route::put('/buku/{id}', [BukuController::class, 'update'])
        ->middleware('permission:kelola data buku')
        ->name('buku.update');

    Route::delete('/buku/{id}', [BukuController::class, 'destroy'])
        ->middleware('permission:kelola data buku')
        ->name('buku.destroy');

    /* DETAIL BUKU (semua role) */

    Route::get('/buku/{id}', [BukuController::class, 'show'])
        ->middleware('permission:lihat daftar buku')
        ->name('buku.show');

    /* KELOLA PENGGUNA & ROLE - ADMIN */

    Route::get('/admin/users', [PerpustakaanAccessController::class, 'indexUsers'])
        ->middleware('permission:kelola data pengguna')
        ->name('admin.users');

    Route::post('/admin/users', [PerpustakaanAccessController::class, 'storeUser'])
        ->middleware('permission:kelola data pengguna')
        ->name('admin.users.store');

    Route::put('/admin/users/{user}/role', [PerpustakaanAccessController::class, 'updateUserRole'])
        ->middleware('permission:kelola role pengguna')
        ->name('admin.users.role');

    Route::delete('/admin/users/{user}', [PerpustakaanAccessController::class, 'destroyUser'])
        ->middleware('permission:kelola data pengguna')
        ->name('admin.users.destroy');

    /* PEMINJAMAN (ADMIN & PETUGAS) */

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->middleware('permission:kelola peminjaman')
        ->name('peminjaman.index');

    Route::post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.setujui');

    Route::post('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.tolak');

    /* PENGEMBALIAN (ADMIN & PETUGAS) */

    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalian'])
        ->middleware('permission:kelola pengembalian')
        ->name('pengembalian.index');

    Route::post('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])
        ->middleware('permission:mencatat pengembalian buku')
        ->name('peminjaman.kembalikan');

    /* LAPORAN (ADMIN & PETUGAS) */

    Route::get('/laporan', [PerpustakaanAccessController::class, 'laporanPerpustakaan'])
        ->middleware('permission:lihat laporan perpustakaan')
        ->name('laporan.index');

    /* PENGAJUAN & STATUS PEMINJAMAN (SEMUA ROLE, DATA MILIK SENDIRI) */

    Route::post('/buku/{buku}/ajukan', [PeminjamanController::class, 'ajukan'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('peminjaman.ajukan');

    Route::get('/status-peminjaman', [PeminjamanController::class, 'statusSendiri'])
        ->middleware('permission:melihat status peminjaman sendiri')
        ->name('peminjaman.status.sendiri');

    // {userId?} hanya diterima jika sama dengan id pengguna yang login (selain itu 403).
    Route::get('/riwayat-peminjaman/{userId?}', [PeminjamanController::class, 'riwayatSendiri'])
        ->middleware('permission:melihat riwayat peminjaman sendiri')
        ->name('peminjaman.riwayat.sendiri');

    /* PROFIL MILIK SENDIRI */

    Route::get('/profil-saya/{userId?}', [PerpustakaanAccessController::class, 'profilSendiri'])
        ->middleware('permission:lihat profil sendiri')
        ->name('profil.saya');

    Route::patch('/profil-saya', [PerpustakaanAccessController::class, 'updateProfilSendiri'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.update');

    Route::delete('/profil-saya', [PerpustakaanAccessController::class, 'destroyProfilSendiri'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
