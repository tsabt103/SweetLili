<?php

namespace App\Livewire;

use App\Jobs\CheckLowStockJob;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManagement extends Component
{
    use WithPagination;

    // Filter & Search states
    public string $search = '';
    public string $selectedCategory = '';
    public string $selectedStatus = '';
    public bool $filterLowStock = false;
    public int $perPage = 8;

    // Form inputs
    public ?int $productId = null;
    public string $name = '';
    public string $category = '';
    public ?int $category_id = null;
    public string $price = '';
    public string $stock = '';
    public string $status = 'active';
    public string $description = '';

    // Modal UI states
    public bool $showFormModal = false;
    public bool $isEditing = false;
    public bool $showDeleteModal = false;
    public ?int $deleteTargetId = null;
    public string $deleteTargetName = '';

    // Reset pagination when searching/filtering
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingSelectedCategory(): void { $this->resetPage(); }
    public function updatingSelectedStatus(): void { $this->resetPage(); }
    public function updatingFilterLowStock(): void { $this->resetPage(); }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:150',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:1000',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.min' => 'Nama produk minimal 2 karakter.',
            'name.max' => 'Nama produk maksimal 150 karakter.',
            'category.required' => 'Kategori produk wajib dipilih.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok tidak boleh negatif.',
            'status.required' => 'Status produk wajib ditentukan.',
            'status.in' => 'Status harus Aktif (active) atau Nonaktif (inactive).',
            'description.max' => 'Deskripsi tidak boleh melebihi 1000 karakter.',
        ];
    }

    public function mount(): void
    {
        // Read URL query parameters if present (e.g., from Dashboard links)
        if (request()->has('search')) {
            $this->search = (string) request()->query('search');
        }
        if (request()->has('low_stock') && request()->query('low_stock') == '1') {
            $this->filterLowStock = true;
        }
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->selectedCategory = '';
        $this->selectedStatus = '';
        $this->filterLowStock = false;
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $product = Product::findOrFail($id);

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->category = $product->category;
        $this->category_id = $product->category_id;
        $this->price = (string) $product->price;
        $this->stock = (string) $product->stock;
        $this->status = $product->status;
        $this->description = (string) ($product->description ?? '');

        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    public function saveProduct(): void
    {
        $validated = $this->validate();

        // Cari atau buat ID kategori sesuai nama kategori
        $categoryModel = Category::firstOrCreate(
            ['name' => $this->category],
            ['slug' => \Illuminate\Support\Str::slug($this->category)]
        );
        $categoryId = $categoryModel->id;

        if ($this->isEditing && $this->productId) {
            $product = Product::findOrFail($this->productId);
            $product->update([
                'name' => $validated['name'],
                'category' => $validated['category'],
                'category_id' => $categoryId,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'status' => $validated['status'],
                'description' => $validated['description'],
            ]);

            $message = "Produk '{$product->name}' berhasil diperbarui!";
        } else {
            $product = Product::create([
                'name' => $validated['name'],
                'category' => $validated['category'],
                'category_id' => $categoryId,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'status' => $validated['status'],
                'description' => $validated['description'],
            ]);

            $message = "Produk baru '{$product->name}' berhasil ditambahkan ke inventaris!";
        }

        // Jalankan Job pengecekan stok jika stok <= 10
        if ($product->stock <= 10) {
            CheckLowStockJob::dispatch($product);
        }

        // Cache invalidation (otomatis via booted trait di Product, namun kita panggil eksplisit)
        Product::flushProductCache();

        $this->closeFormModal();
        $this->dispatch('toast', message: $message, type: 'success');
        session()->flash('success', $message);
    }

    public function confirmDelete(int $id): void
    {
        $product = Product::findOrFail($id);
        $this->deleteTargetId = $product->id;
        $this->deleteTargetName = $product->name;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deleteTargetId = null;
        $this->deleteTargetName = '';
    }

    public function deleteProduct(): void
    {
        if ($this->deleteTargetId) {
            $product = Product::findOrFail($this->deleteTargetId);
            $productName = $product->name;
            // Soft delete
            $product->delete();

            Product::flushProductCache();

            $this->cancelDelete();
            $msg = "Produk '{$productName}' telah dipindahkan ke Sampah (Soft Delete). Anda dapat memulihkannya kapan saja di menu Sampah.";
            $this->dispatch('toast', message: $msg, type: 'success');
            session()->flash('success', $msg);
        }
    }

    private function resetForm(): void
    {
        $this->productId = null;
        $this->name = '';
        $this->category = '';
        $this->category_id = null;
        $this->price = '';
        $this->stock = '';
        $this->status = 'active';
        $this->description = '';
    }

    public function render()
    {
        // Query builder dengan SoftDeletes
        $query = Product::query();

        // Filter Pencarian Realtime
        if (!empty($this->search)) {
            $query->search($this->search);
        }

        // Filter Kategori
        if (!empty($this->selectedCategory)) {
            $query->where('category', $this->selectedCategory);
        }

        // Filter Status
        if (!empty($this->selectedStatus)) {
            $query->where('status', $this->selectedStatus);
        }

        // Filter Stok Menipis (<= 10)
        if ($this->filterLowStock) {
            $query->lowStock();
        }

        $products = $query->latest()->paginate($this->perPage);

        // Ambil daftar kategori dari Cache
        $categories = Cache::remember('categories_list', 3600, function () {
            return Category::all();
        });

        return view('livewire.product-management', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
