<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Jobs\CheckLowStockJob;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductApiController extends Controller
{
    /**
     * Display a listing of products with filtering, search, and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        // Search by keyword
        if ($request->filled('search')) {
            $query->search($request->string('search'));
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        // Filter low stock
        if ($request->boolean('low_stock')) {
            $query->lowStock();
        }

        $perPage = min(max((int) $request->input('per_page', 10), 1), 100);
        $products = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar produk berhasil diambil.',
            'data' => ProductResource::collection($products->items()),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
            ],
        ], 200);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:150',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'nullable|in:active,inactive',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category.required' => 'Kategori produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka numerik.',
            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
        ]);

        $categoryModel = Category::firstOrCreate(
            ['name' => $validated['category']],
            ['slug' => Str::slug($validated['category'])]
        );

        $product = Product::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'category_id' => $categoryModel->id,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'status' => $validated['status'] ?? 'active',
            'description' => $validated['description'] ?? null,
        ]);

        if ($product->stock <= 10) {
            CheckLowStockJob::dispatch($product);
        }

        Product::flushProductCache();

        return response()->json([
            'success' => true,
            'message' => 'Produk baru berhasil ditambahkan.',
            'data' => new ProductResource($product),
        ], 201);
    }

    /**
     * Display the specified product.
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk dengan ID tersebut tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail produk berhasil diambil.',
            'data' => new ProductResource($product),
        ], 200);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|min:2|max:150',
            'category' => 'sometimes|required|string|max:100',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'status' => 'nullable|in:active,inactive',
            'description' => 'nullable|string|max:1000',
        ]);

        if (isset($validated['category'])) {
            $cat = Category::firstOrCreate(
                ['name' => $validated['category']],
                ['slug' => Str::slug($validated['category'])]
            );
            $validated['category_id'] = $cat->id;
        }

        $product->update($validated);

        if ($product->stock <= 10) {
            CheckLowStockJob::dispatch($product);
        }

        Product::flushProductCache();

        return response()->json([
            'success' => true,
            'message' => 'Informasi produk berhasil diperbarui.',
            'data' => new ProductResource($product->fresh()),
        ], 200);
    }

    /**
     * Remove the specified product from storage (Soft Delete).
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $name = $product->name;
        $product->delete();

        Product::flushProductCache();

        return response()->json([
            'success' => true,
            'message' => "Produk '{$name}' berhasil dipindahkan ke sampah (Soft Delete).",
        ], 200);
    }
}
