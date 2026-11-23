<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'category',
        'price',
        'stock',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    /**
     * Clear caches when product changes.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            self::flushProductCache();
        });

        static::deleted(function () {
            self::flushProductCache();
        });

        static::restored(function () {
            self::flushProductCache();
        });

        static::forceDeleted(function () {
            self::flushProductCache();
        });
    }

    public static function flushProductCache(): void
    {
        Cache::forget('dashboard_stats');
        Cache::forget('categories_list');
        Cache::forget('low_stock_products');
    }

    /**
     * Relationship to Category.
     */
    public function categoryModel(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relationship to Stock Alerts.
     */
    public function stockAlerts(): HasMany
    {
        return $this->hasMany(StockAlert::class);
    }

    /**
     * Scope for active products.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for low stock products (<= 10).
     */
    public function scopeLowStock(Builder $query): Builder
    {
        return $query->where('stock', '<=', 10);
    }

    /**
     * Scope search.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', '%' . $term . '%')
              ->orWhere('category', 'like', '%' . $term . '%')
              ->orWhere('description', 'like', '%' . $term . '%');
        });
    }

    /**
     * Helper to check if stock is low.
     */
    public function isLowStock(): bool
    {
        return $this->stock <= 10;
    }
}
