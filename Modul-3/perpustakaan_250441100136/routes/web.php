<?php

use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;

// 1. Route Beranda
Route::get('/', function () {
    return view('home');
})->name('home');

// 2. Route Daftar Buku
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

// 3. Route Detail Buku
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
