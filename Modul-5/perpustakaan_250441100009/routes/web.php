<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/{id}', [BukuController::class, 'detail'])->name('buku.detail');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->hasRole('petugas')) {
        return redirect()->route('petugas.dashboard');
    }

    return redirect()->route('siswa.riwayat');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayat'])->name('siswa.riwayat');
    Route::get('/riwayat-peminjaman/{id}', [PeminjamanController::class, 'detail'])->name('siswa.riwayat.detail');
    Route::post('/pinjam/{bukuId}', [PeminjamanController::class, 'ajukanPinjam'])->name('buku.pinjam')->middleware('can:mengajukan peminjaman buku');
});

// Petugas routes (Role: petugas khusus)
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
    Route::get('/peminjaman', [PetugasController::class, 'peminjaman'])->name('peminjaman');
    Route::post('/peminjaman/{id}/setujui', [PetugasController::class, 'setujuiPeminjaman'])->name('peminjaman.setujui');
    Route::post('/peminjaman/{id}/tolak', [PetugasController::class, 'tolakPeminjaman'])->name('peminjaman.tolak');
    Route::post('/peminjaman/{id}/kembali', [PetugasController::class, 'prosesKembali'])->name('peminjaman.kembali');
});

// Admin routes (Role: admin khusus)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{id}/role', [AdminController::class, 'updateRole'])->name('users.role');
    Route::get('/peminjaman', [AdminController::class, 'peminjaman'])->name('peminjaman');
    Route::post('/peminjaman/{id}/setujui', [AdminController::class, 'setujuiPeminjaman'])->name('peminjaman.setujui');
    Route::post('/peminjaman/{id}/tolak', [AdminController::class, 'tolakPeminjaman'])->name('peminjaman.tolak');
    Route::post('/peminjaman/{id}/kembali', [AdminController::class, 'prosesKembali'])->name('peminjaman.kembali');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
});

require __DIR__.'/auth.php';
