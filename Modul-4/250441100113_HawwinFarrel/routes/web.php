<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Models\Kategori;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/buku', function () {
    $kategoris = Kategori::all();
    return view('buku.index', compact('kategoris'));
});

Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');