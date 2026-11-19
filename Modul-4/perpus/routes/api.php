<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Models\Kategori;

Route::get('/kategori', [Kategori::class,]);
Route::get('/buku', [BukuController::class, 'apiIndex']);
Route::post('/buku', [BukuController::class, 'apiStore']);
Route::put('/buku/{id}', [BukuController::class, 'apiUpdate']);
Route::delete('/buku/{id}', [BukuController::class, 'apiDestroy']);