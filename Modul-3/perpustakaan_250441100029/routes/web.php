<?php

use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'beranda')->name('beranda'); /*url apabila kosongan/default 
, akan di arahkan ke halaman beranda */
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index'); 
/*baris ke dua ini berjalan bila user memencet tombol jelajahi buku, saat user memencet akan mencari 
route dengan nama 'buku.index' , yang tugas nya adalah menampilkan daftar buku dengan 
bantuan  */
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');