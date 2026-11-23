<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductTrash extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showForceDeleteModal = false;
    public ?int $forceDeleteId = null;
    public string $forceDeleteName = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function restore(int $id): void
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $name = $product->name;
        $product->restore();

        Product::flushProductCache();

        $msg = "Produk '{$name}' berhasil dipulihkan ke katalog aktif!";
        $this->dispatch('toast', message: $msg, type: 'success');
        session()->flash('success', $msg);
    }

    public function confirmForceDelete(int $id): void
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $this->forceDeleteId = $product->id;
        $this->forceDeleteName = $product->name;
        $this->showForceDeleteModal = true;
    }

    public function cancelForceDelete(): void
    {
        $this->showForceDeleteModal = false;
        $this->forceDeleteId = null;
        $this->forceDeleteName = '';
    }

    public function forceDelete(): void
    {
        if ($this->forceDeleteId) {
            $product = Product::onlyTrashed()->findOrFail($this->forceDeleteId);
            $name = $product->name;
            $product->forceDelete();

            Product::flushProductCache();

            $this->cancelForceDelete();
            $msg = "Produk '{$name}' telah dihapus permanen dari basis data.";
            $this->dispatch('toast', message: $msg, type: 'success');
            session()->flash('success', $msg);
        }
    }

    public function restoreAll(): void
    {
        $count = Product::onlyTrashed()->count();
        Product::onlyTrashed()->restore();

        Product::flushProductCache();

        $msg = "Seluruh {$count} produk di sampah berhasil dipulihkan!";
        $this->dispatch('toast', message: $msg, type: 'success');
        session()->flash('success', $msg);
    }

    public function render()
    {
        $query = Product::onlyTrashed();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%');
            });
        }

        $trashedProducts = $query->latest('deleted_at')->paginate(10);

        return view('livewire.product-trash', [
            'trashedProducts' => $trashedProducts,
        ]);
    }
}
