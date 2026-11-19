<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Buku;
use App\Models\Kategori;
use App\Http\Controllers\Api\BukuController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/rekap/buku-per-kategori', function () {
    return response()->json(
        Kategori::withCount('bukus')->get(),
        200
    );
});

Route::get('rekap/peminjaman-per-buku', function () {
    return response()->json(
        Buku::with('kategori')
            ->withCount('peminjamans')
            ->get(),
        200
    );
});

Route::apiResource('buku', BukuController::class);
