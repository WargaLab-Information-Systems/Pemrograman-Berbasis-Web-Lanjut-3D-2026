<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Models\Kategori;

Route::apiResource('buku', BukuController::class);

Route::get('/kategori', function () {
    $kategori = Kategori::all();
    return response()->json([
        'message' => 'Berhasil mengambil data kategori',
        'data' => $kategori
    ], 200);
});