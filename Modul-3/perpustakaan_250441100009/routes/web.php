<?php

use App\Http\Controllers\Api\BukuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

// buat detail buku
Route::get('/buku/{id}', [BukuController::class, 'detail'])->name('buku.detail');
