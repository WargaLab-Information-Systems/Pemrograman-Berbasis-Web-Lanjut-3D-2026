<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    
    // Redirect
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');

    // Profil 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Katalog
    Route::middleware(['can:Lihat daftar buku'])->group(function () {
        Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
        Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
    });

    // Data AP
    Route::middleware(['can:Kelola data buku'])->group(function () {
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
        Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
        Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');
    });

    Route::middleware(['can:Ajukan peminjaman'])->group(function () {
        Route::post('/peminjaman/ajukan/{buku_id}', [PeminjamanController::class, 'ajukan'])->name('peminjaman.ajukan');
    });

    Route::middleware(['can:Lihat riwayat peminjaman'])->group(function () {
        Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
    });

    // Peminjaman AP
    Route::middleware(['can:Kelola peminjaman'])->group(function () {
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    });

    // Laporan AP
    Route::middleware(['can:Lihat laporan'])->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });

    // Dashboard A
    Route::middleware(['can:Akses dashboard admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Role
        Route::get('/users', [ProfileController::class, 'indexUsers'])->name('users.index');
        Route::put('/users/{id}/role', [ProfileController::class, 'updateRole'])->name('users.updateRole');
    });

    // Dashboard P
    Route::middleware(['can:Akses dashboard petugas'])->group(function () {
        Route::get('/petugas/dashboard', function () {
            return view('petugas.dashboard');
        })->name('petugas.dashboard');
    });
});

require __DIR__.'/auth.php';