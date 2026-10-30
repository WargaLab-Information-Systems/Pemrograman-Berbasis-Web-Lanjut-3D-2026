<?php

use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;

// Halaman Beranda
route::get('/', [BukuController::class, 'home'])->name('home');

// Halaman Daftar Buku
route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

// Halaman Detail Buku dengan Route Parameter
route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
