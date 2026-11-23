<?php

use App\Models\Product;
use App\Jobs\CheckLowStockJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\artisan;
use Illuminate\Support\Facades\Schedule;

artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwal cek stok harian (Schedule)
Schedule::command('stock:check')
    ->dailyAt('08:00')
    ->name('daily-stock-check')
    ->withoutOverlapping();
