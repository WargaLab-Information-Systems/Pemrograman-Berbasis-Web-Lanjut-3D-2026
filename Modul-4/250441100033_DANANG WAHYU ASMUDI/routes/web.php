<?php
use App\Models\Kategori;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/buku', function () {
    $kategoris = Kategori::all(); 
    return view('buku.index', compact('kategoris'));
})->name('buku.index');