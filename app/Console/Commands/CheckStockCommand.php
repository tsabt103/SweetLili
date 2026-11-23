<?php

namespace App\Console\Commands;

use App\Jobs\CheckLowStockJob;
use App\Models\Product;
use Illuminate\Console\Command;

class CheckStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pemeriksaan harian produk stok menipis (<= 10) dan picu job notifikasi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lowStockProducts = Product::where('stock', '<=', 10)->get();

        $this->info("Menemukan {$lowStockProducts->count()} produk dengan stok menipis.");

        foreach ($lowStockProducts as $product) {
            CheckLowStockJob::dispatch($product);
            $this->line("- [{$product->stock} unit] {$product->name}");
        }

        $this->info("Pemeriksaan stok selesai. Job antrean telah dikirim.");
    }
}
