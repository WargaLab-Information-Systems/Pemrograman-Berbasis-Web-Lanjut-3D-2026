<?php

use App\Http\Controllers\Api\BukuController;
use Illuminate\Support\Facades\Route;

Route::get('/buku', [BukuController::class, 'index']);
Route::post('/buku', [BukuController::class, 'store']);
Route::get('/buku/{buku}', [BukuController::class, 'show']);
Route::put('/buku/{buku}', [BukuController::class, 'update']);
Route::delete('/buku/{buku}', [BukuController::class, 'destroy']);
