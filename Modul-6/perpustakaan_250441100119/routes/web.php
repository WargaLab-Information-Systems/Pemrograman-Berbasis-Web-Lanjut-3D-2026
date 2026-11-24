<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BukuCariController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DetailPeminjamanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Models\Buku;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        return redirect()->route('buku.index');
    })->name('dashboard');

    Route::get('/admin/dashboard', function () {
        return view('dashboard', [
            'judulDashboard' => 'Dashboard Admin',
        ]);
    })
        ->middleware('permission:akses dashboard admin')
        ->name('admin.dashboard');

    Route::get('/petugas/dashboard', function () {
        return view('dashboard', [
            'judulDashboard' => 'Dashboard Petugas',
        ]);
    })
        ->middleware('permission:akses dashboard petugas')
        ->name('petugas.dashboard');

    Route::get('/buku', [BukuController::class, 'index'])
        ->middleware('permission:lihat daftar buku')
        ->name('buku.index');

    Route::get('/buku/create', [BukuController::class, 'index'])
        ->middleware('can:kelola-buku')
        ->name('buku.create');

    Route::get('/buku-cari', [BukuCariController::class, 'index'])
        ->name('buku.cari');

    Route::delete('/buku/{buku}', function (Buku $buku) {
        if ($buku->peminjamans()->exists()) {
            return redirect()
                ->route('buku.index')
                ->with(
                    'error',
                    'Buku tidak dapat dihapus karena memiliki riwayat peminjaman.'
                );
        }

        $buku->delete();

        return redirect()
            ->route('buku.index')
            ->with('success', 'Buku berhasil dihapus.');
    })
        ->middleware('can:kelola-buku')
        ->name('buku.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat profil sendiri')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.destroy');

    Route::get('/peminjaman', [
        PeminjamanController::class,
        'index'
    ])
        ->middleware(
            'permission:kelola peminjaman|lihat riwayat peminjaman sendiri'
        )
        ->name('peminjaman.index');

    Route::post('/peminjaman', [
        PeminjamanController::class,
        'store'
    ])
        ->middleware('permission:ajukan peminjaman buku')
        ->name('peminjaman.store');

    Route::get('/peminjaman/{id}', [
        DetailPeminjamanController::class,
        'show'
    ])->name('peminjaman.show');

    Route::patch(
        '/peminjaman/{peminjaman}/status',
        [PeminjamanController::class, 'updateStatus']
    )
        ->middleware('permission:proses persetujuan peminjaman')
        ->name('peminjaman.update-status');

    Route::patch(
        '/peminjaman/{peminjaman}/kembalikan',
        [PeminjamanController::class, 'kembalikan']
    )
        ->middleware('permission:catat pengembalian buku')
        ->name('peminjaman.kembalikan');

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat laporan perpustakaan')
        ->name('laporan.index');
});

Route::middleware([
    'auth',
    'permission:kelola data pengguna',
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::patch('/users/{user}/role', [
            UserController::class,
            'updateRole',
        ])
            ->middleware('permission:kelola role pengguna')
            ->name('users.update-role');
    });

require __DIR__ . '/auth.php';