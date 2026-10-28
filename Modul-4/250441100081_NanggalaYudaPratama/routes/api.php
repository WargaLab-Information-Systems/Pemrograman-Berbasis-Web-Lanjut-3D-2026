<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;

Route::apiResource('buku', BukuController::class)->names('api.buku');