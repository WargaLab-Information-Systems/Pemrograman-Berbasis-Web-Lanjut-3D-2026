<?php

use App\Http\Controllers\Api\BukuController;
use Illuminate\Support\Facades\Route;

Route::apiResource('buku', BukuController::class)->names('api.buku');