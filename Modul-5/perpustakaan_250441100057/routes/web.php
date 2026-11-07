<?php

use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/buku', [BukuController::class, 'index'])
    ->name('buku.index');

Route::get('/buku/{id}', [BukuController::class, 'show'])
    ->name('buku.show');

Route::middleware(['auth', 'role:admin', 'permission:dashboard_admin'])->group(function () {
    Route::get('/dashboard-admin', function () {
        return view('dashboard-admin');
    })->name('dashboard.admin');
});

Route::middleware(['auth', 'role:petugas', 'permission:dashboard_petugas'])->group(function () {
    Route::get('/dashboard-petugas', function () {
        return view('dashboard-petugas');
    })->name('dashboard.petugas');
});

Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard-siswa', function () {
        return view('dashboard-siswa');
    })->name('dashboard.siswa');
});

Route::middleware(['auth', 'role:admin', 'permission:manage_users'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::patch('/users/{user}', [UserController::class, 'update'])
        ->name('users.update');
});

Route::middleware(['auth', 'permission:view_own_borrowing_history|manage_borrowings'])
    ->get('/peminjaman', [PeminjamanController::class, 'index'])
    ->name('peminjaman.index');

Route::middleware(['auth', 'role:siswa', 'permission:request_borrowing'])
    ->post('/peminjaman', [PeminjamanController::class, 'store'])
    ->name('peminjaman.store');

Route::middleware(['auth', 'role:siswa', 'permission:request_return'])
    ->post('/peminjaman/{id}/pengembalian', [PeminjamanController::class, 'requestReturn'])
    ->name('peminjaman.requestReturn');

Route::middleware(['auth', 'role:admin|petugas', 'permission:process_borrowing_approval'])
    ->patch('/peminjaman/{id}/setujui', [PeminjamanController::class, 'approve'])
    ->name('peminjaman.approve');

Route::middleware(['auth', 'role:admin|petugas', 'permission:process_borrowing_approval'])
    ->patch('/peminjaman/{id}/tolak', [PeminjamanController::class, 'reject'])
    ->name('peminjaman.reject');

Route::middleware(['auth', 'role:admin|petugas', 'permission:record_return'])
    ->patch('/peminjaman/{id}/konfirmasi-pengembalian', [PeminjamanController::class, 'confirmReturn'])
    ->name('peminjaman.confirmReturn');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';