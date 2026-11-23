<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - SweetLili &  Supply Management System
|--------------------------------------------------------------------------
| REST API produk dengan Sanctum Token Authentication & Rate Limiting.
*/

Route::middleware('throttle:60,1')->group(function () {
    // Autentikasi API (Login untuk mendapatkan token Sanctum)
    Route::post('/auth/login', [AuthApiController::class, 'login']);

    // Endpoint Produk Terbuka (Read-only Katalog)
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{id}', [ProductApiController::class, 'show']);

    // Endpoint Terproteksi Sanctum (Wajib Bearer Token)
    Route::middleware('auth:sanctum')->group(function () {
        // Auth me & logout
        Route::get('/auth/me', [AuthApiController::class, 'me']);
        Route::post('/auth/logout', [AuthApiController::class, 'logout']);

        // CRUD Mutasi Produk (Store, Update, Destroy)
        Route::post('/products', [ProductApiController::class, 'store']);
        Route::put('/products/{id}', [ProductApiController::class, 'update']);
        Route::patch('/products/{id}', [ProductApiController::class, 'update']);
        Route::delete('/products/{id}', [ProductApiController::class, 'destroy']);
    });
});
