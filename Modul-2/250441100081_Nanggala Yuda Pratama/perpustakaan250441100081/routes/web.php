<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;



Route::get('/', function () {
    return view('home');
})->name('home');

route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');