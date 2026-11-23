<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard dengan statistik berbasis Cache::remember.
     */
    public function index(): View
    {
        // Cache statistik dashboard selama 60 menit (akan diinvalidasi otomatis saat produk diubah/dihapus/dibuat)
        $stats = Cache::remember('dashboard_stats', 3600, function () {
            $totalProducts = Product::count();
            $totalStock = (int) Product::sum('stock');
            $lowStockCount = Product::where('stock', '<=', 10)->count();
            $activeCount = Product::where('status', 'active')->count();
            $inactiveCount = Product::where('status', 'inactive')->count();
            $trashedCount = Product::onlyTrashed()->count();
            $inventoryValue = (float) (Product::selectRaw('SUM(price * stock) as total')->value('total') ?? 0);

            // Ringkasan produk dengan stok kritis/menipis
            $lowStockItems = Product::where('stock', '<=', 10)
                ->orderBy('stock', 'asc')
                ->limit(6)
                ->get();

            // Ringkasan distribusi stok per kategori
            $categoryDistribution = Product::select('category', DB::raw('count(*) as product_count'), DB::raw('sum(stock) as stock_sum'))
                ->groupBy('category')
                ->get();

            // Produk terbaru
            $recentProducts = Product::latest()->limit(5)->get();

            return [
                'total_products' => $totalProducts,
                'total_stock' => $totalStock,
                'low_stock_count' => $lowStockCount,
                'active_count' => $activeCount,
                'inactive_count' => $inactiveCount,
                'trashed_count' => $trashedCount,
                'inventory_value' => $inventoryValue,
                'low_stock_items' => $lowStockItems,
                'category_distribution' => $categoryDistribution,
                'recent_products' => $recentProducts,
                'cached_at' => now()->translatedFormat('H:i:s, d M Y'),
            ];
        });

        // Cache daftar kategori
        $categories = Cache::remember('categories_list', 3600, function () {
            return Category::withCount('products')->get();
        });

        return view('dashboard', [
            'stats' => $stats,
            'categories' => $categories,
        ]);
    }

    /**
     * Helper manual untuk membersihkan cache dashboard (opsional trigger dari UI).
     */
    public function clearCache()
    {
        Product::flushProductCache();

        return redirect()->route('dashboard')
            ->with('success', 'Cache statistik dashboard & kategori berhasil diperbarui secara manual!');
    }
}
