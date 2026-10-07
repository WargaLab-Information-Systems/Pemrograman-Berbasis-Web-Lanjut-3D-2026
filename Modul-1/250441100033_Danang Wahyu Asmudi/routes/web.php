<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;

// Poin 1: Route Beranda dengan URL "/" dan name "home"
Route::get('/', function () {
    return view('home');
})->name('home');

// Poin 2: Route Daftar Buku dengan URL "/buku" dan name "buku.index"
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

// Poin 3: Route Detail Buku dengan parameter {id}
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');