
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Models\Buku;
use App\Models\Kategori;

Route::apiResource('buku', BukuController::class);

Route::get('/rekap/buku-per-kategori', function () {
    return response()->json([
        'data' => Kategori::withCount('bukus')->get(),
    ], 200);
});

Route::get('/rekap/peminjaman-per-buku', function () {
    return response()->json([
        'data' => Buku::with('kategori')
            ->withCount('peminjamans')
            ->get(),
    ], 200);
});
