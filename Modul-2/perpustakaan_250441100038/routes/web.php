<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Rute untuk Halaman Beranda (Selamat Datang)
Route::get('/', function () {
    // Pastikan nama file di resources/views Anda adalah 'welcome.blade.php'. 
    // Jika namanya 'home.blade.php', ubah tulisan 'welcome' di bawah ini menjadi 'home'.
    return view('welcome'); 
})->name('home');

// 2. Rute untuk Halaman Daftar Buku
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

// 3. Rute untuk Halaman Detail Buku
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');

// require __DIR__.'/auth.php';