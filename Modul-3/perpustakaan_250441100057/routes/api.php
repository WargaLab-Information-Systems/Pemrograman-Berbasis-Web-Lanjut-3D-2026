<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Models\Buku;
use App\Models\Kategori;

Route::apiResource('buku', BukuController::class);

Route::get('/rekap/buku-per-kategori', function () {
    $data = Kategori::withCount('bukus')->get();

    return response()->json([
        'data' => $data,
    ], 200);
});

Route::get('/rekap/pemminjaman-per-buku', function () {
    $data = Buku::withCount('peminjamans')->get();

    return response()->json([
        'data' => $data,
    ], 200);
});