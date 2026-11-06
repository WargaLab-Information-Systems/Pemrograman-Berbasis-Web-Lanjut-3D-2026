<?php

use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;


// 1. Halaman Beranda
Route::get('/home', function () {
    return view('home');
})->name('home');

// Halaman Daftar Buku & Detail Buku (ditangani BukuController)
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
