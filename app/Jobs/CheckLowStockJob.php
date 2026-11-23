<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\StockAlert;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CheckLowStockJob implements ShouldQueue
{
    use Queueable;

    public Product $product;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->product->stock <= 10) {
            $msg = "Peringatan Stok Menipis: Produk '{$this->product->name}' tersisa {$this->product->stock} unit pada tanggal " . now()->toFormattedDateString() . ".";
            
            StockAlert::create([
                'product_id' => $this->product->id,
                'product_name' => $this->product->name,
                'stock_count' => $this->product->stock,
                'alert_type' => 'low_stock',
                'message' => $msg,
            ]);

            Log::warning("[SweetLili Stock Job] " . $msg);
        }
    }
}
