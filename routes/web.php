<?php

use App\Http\Controllers\ApiDocsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManualAuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SweetLili &  Supply Management System
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard jika login, atau ke halaman login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Autentikasi Manual (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [ManualAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ManualAuthController::class, 'login']);

    Route::get('/register', [ManualAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [ManualAuthController::class, 'register']);
});

// Sistem Utama Terproteksi (Auth Only)
Route::middleware('auth')->group(function () {
    // Logout Manual
    Route::post('/logout', [ManualAuthController::class, 'logout'])->name('logout');

    // Dashboard dengan Cache::remember
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/clear-cache', [DashboardController::class, 'clearCache'])->name('dashboard.clear-cache');

    // Katalog Produk & Livewire CRUD
    Route::get('/products', function () {
        return view('products.index');
    })->name('products.index');

    // Sampah (Soft Delete) Produk
    Route::get('/products/trash', function () {
        return view('products.trash');
    })->name('products.trash');

    // Dokumentasi & Generator Token REST API
    Route::get('/api-docs', [ApiDocsController::class, 'index'])->name('api.docs');
    Route::post('/api-docs/tokens', [ApiDocsController::class, 'createToken'])->name('api.tokens.create');
    Route::delete('/api-docs/tokens/{id}', [ApiDocsController::class, 'revokeToken'])->name('api.tokens.revoke');
});
