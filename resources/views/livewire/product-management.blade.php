<div x-data="{
    init() {
        window.addEventListener('toast', event => {
            const data = event.detail?.[0] || event.detail;
            if (data?.message && window.showToast) {
                window.showToast(data.message, data.type || 'success');
            }
        });
    }
}">
    <!-- Header Page Actions -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-sage-900 tracking-tight">Katalog & Manajemen Produk</h2>
            <p class="text-xs text-sage-600">Kelola inventaris Kue, media lukis, dan perlengkapan seni secara real-time</p>
        </div>

        <div class="flex items-center gap-2">
            <button wire:click="openCreateModal"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-terracotta-500 hover:bg-terracotta-600 transition-colors shadow-sm shadow-terracotta-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Produk Baru
            </button>
        </div>
    </div>

    <!-- Filter & Search Toolbar Card -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-sage-200/80 shadow-sm mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5">
            <!-- Search Input (Debounced Real-time) -->
            <div class="lg:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-sage-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nama, kategori, deskripsi..."
                    class="block w-full pl-9 pr-3 py-2 border border-sage-300 rounded-xl text-xs text-sage-900 placeholder-sage-400 focus:outline-none focus:ring-2 focus:ring-sage-500 focus:border-sage-500">
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sage-400 hover:text-sage-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
            </div>

            <!-- Filter Kategori -->
            <div class="lg:col-span-3">
                <select wire:model.live="selectedCategory"
                    class="block w-full py-2 px-3 border border-sage-300 rounded-xl text-xs text-sage-900 bg-white focus:outline-none focus:ring-2 focus:ring-sage-500 focus:border-sage-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
    <option value="{{ is_array($cat) ? ($cat['name'] ?? $cat['id'] ?? '') : $cat }}">{{ is_array($cat) ? ($cat['name'] ?? '') : $cat }}</option>
@endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div class="lg:col-span-2">
                <select wire:model.live="selectedStatus"
                    class="block w-full py-2 px-3 border border-sage-300 rounded-xl text-xs text-sage-900 bg-white focus:outline-none focus:ring-2 focus:ring-sage-500 focus:border-sage-500">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>

            <!-- Filter Stok Menipis Toggle -->
            <div class="lg:col-span-2 flex items-center">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none text-xs text-sage-800">
                    <input type="checkbox" wire:model.live="filterLowStock"
                        class="w-4 h-4 rounded border-sage-300 text-terracotta-500 focus:ring-terracotta-400">
                    <span class="font-medium {{ $filterLowStock ? 'text-rose-700 font-bold' : '' }}">Stok Menipis (&le; 10)</span>
                </label>
            </div>

            <!-- Reset Button -->
            <div class="lg:col-span-1 flex items-center justify-end">
                <button wire:click="resetFilters"
                    title="Reset semua filter pencarian"
                    class="w-full py-2 px-2.5 rounded-xl border border-sage-200 text-sage-600 hover:text-sage-900 hover:bg-sage-50 text-xs font-semibold text-center transition-colors">
                    Reset
                </button>
            </div>
        </div>

        <!-- Filter Active Pills -->
        @if($search || $selectedCategory || $selectedStatus || $filterLowStock)
            <div class="mt-3 pt-3 border-t border-sage-100 flex flex-wrap items-center gap-2 text-xs">
                <span class="text-sage-500 text-[11px]">Filter Aktif:</span>
                @if($search)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-sage-100 text-sage-800 text-[11px]">
                        Kata kunci: "{{ $search }}"
                        <button wire:click="$set('search', '')" class="hover:text-rose-600">&times;</button>
                    </span>
                @endif
                @if($selectedCategory)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-sage-100 text-sage-800 text-[11px]">
                        Kategori: {{ $selectedCategory }}
                        <button wire:click="$set('selectedCategory', '')" class="hover:text-rose-600">&times;</button>
                    </span>
                @endif
                @if($selectedStatus)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-sage-100 text-sage-800 text-[11px]">
                        Status: {{ $selectedStatus === 'active' ? 'Aktif' : 'Nonaktif' }}
                        <button wire:click="$set('selectedStatus', '')" class="hover:text-rose-600">&times;</button>
                    </span>
                @endif
                @if($filterLowStock)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[11px]">
                        Hanya Stok Menipis (&le; 10)
                        <button wire:click="$set('filterLowStock', false)" class="hover:text-rose-600">&times;</button>
                    </span>
                @endif
            </div>
        @endif
    </div>

    <!-- Products Table Card -->
    <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm overflow-hidden">
        <!-- Loading State Indicator -->
        <div wire:loading.delay class="w-full bg-sage-50 py-1.5 px-4 text-center text-xs text-sage-700 border-b border-sage-200 font-medium">
            <span class="inline-flex items-center gap-2">
                <svg class="animate-spin h-3.5 w-3.5 text-sage-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                Memuat data produk...
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sage-100 text-left text-xs">
                <thead class="bg-sage-50/70 text-sage-600 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Info Produk</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Harga Satuan</th>
                        <th class="py-3.5 px-4">Stok Fisik</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sage-100 text-sage-800">
                    @forelse($products as $product)
                        <tr class="hover:bg-cream-50/50 transition-colors {{ $product->isLowStock() ? 'bg-rose-50/30' : '' }}">
                            <!-- Info Produk -->
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-sage-900 text-sm">{{ $product->name }}</div>
                                <div class="text-[11px] text-sage-500 max-w-xs truncate" title="{{ $product->description }}">
                                    {{ $product->description ?: 'Tidak ada deskripsi' }}
                                </div>
                            </td>

                            <!-- Kategori -->
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-lg bg-sage-100 text-sage-800 font-semibold text-[11px]">
                                    {{ $product->category }}
                                </span>
                            </td>

                            <!-- Harga -->
                            <td class="py-3.5 px-4 font-semibold text-sage-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            <!-- Stok Fisik -->
                            <td class="py-3.5 px-4">
                                @if($product->isLowStock())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-700 border border-rose-200" title="Stok menipis, perlu restock!">
                                        <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        {{ $product->stock }} unit (Menipis)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $product->stock }} unit
                                    </span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-4">
                                @if($product->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <!-- Edit Button -->
                                    <button wire:click="openEditModal({{ $product->id }})"
                                        class="p-1.5 text-sage-600 hover:text-sage-900 rounded-lg hover:bg-sage-100 transition-colors"
                                        title="Ubah Produk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    <!-- Soft Delete Button -->
                                    <button wire:click="confirmDelete({{ $product->id }})"
                                        class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition-colors"
                                        title="Hapus Produk (Soft Delete)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-4 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-sage-50 text-sage-400 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-sage-800">Tidak ada produk ditemukan</p>
                                    <p class="text-xs text-sage-500 mt-1">Coba sesuaikan kata kunci pencarian atau ubah filter yang dipilih.</p>
                                    <button wire:click="resetFilters" class="mt-3 text-xs font-semibold text-terracotta-600 hover:text-terracotta-700 underline">
                                        Bersihkan Semua Filter
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        <div class="p-4 border-t border-sage-100 bg-sage-50/30">
            {{ $products->links() }}
        </div>
    </div>

    <!-- FORM MODAL (TAMBAH / EDIT PRODUK) -->
    @if($showFormModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <!-- Modal Backdrop -->
                <div wire:click="closeFormModal" class="fixed inset-0 bg-sage-950/40 backdrop-blur-sm transition-opacity"></div>

                <!-- Modal Dialog -->
                <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-sage-200">
                    <form wire:submit="saveProduct">
                        <!-- Modal Header -->
                        <div class="p-5 border-b border-sage-100 flex items-center justify-between bg-cream-50/50">
                            <h3 class="text-base font-bold text-sage-900" id="modal-title">
                                {{ $isEditing ? 'Ubah Informasi Produk' : 'Tambah Produk Seni Baru' }}
                            </h3>
                            <button type="button" wire:click="closeFormModal" class="text-sage-400 hover:text-sage-600 p-1 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- Modal Body Inputs -->
                        <div class="p-6 space-y-4 text-xs">
                            <!-- Nama Produk -->
                            <div>
                                <label class="block font-medium text-sage-800 mb-1">Nama Produk <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="name"
                                    class="w-full px-3 py-2 border rounded-xl text-sage-900 text-xs focus:ring-2 focus:ring-sage-500 focus:outline-none {{ $errors->has('name') ? 'border-rose-400' : 'border-sage-300' }}"
                                    placeholder="Contoh: Acrylic Color Set 12x12ml">
                                @error('name') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Kategori & Status Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block font-medium text-sage-800 mb-1">Kategori <span class="text-rose-500">*</span></label>
                                    <input list="categorySuggestions" type="text" wire:model="category"
                                        class="w-full px-3 py-2 border rounded-xl text-sage-900 text-xs focus:ring-2 focus:ring-sage-500 focus:outline-none {{ $errors->has('category') ? 'border-rose-400' : 'border-sage-300' }}"
                                        placeholder="Pilih atau ketik kategori">
                                    <datalist id="categorySuggestions">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->name }}"></option>
                                        @endforeach
                                    </datalist>
                                    @error('category') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-medium text-sage-800 mb-1">Status Publikasi <span class="text-rose-500">*</span></label>
                                    <select wire:model="status"
                                        class="w-full px-3 py-2 border rounded-xl text-sage-900 text-xs focus:ring-2 focus:ring-sage-500 focus:outline-none {{ $errors->has('status') ? 'border-rose-400' : 'border-sage-300' }}">
                                        <option value="active">Aktif (Tersedia untuk Pelanggan)</option>
                                        <option value="inactive">Nonaktif (Diarsipkan/Restock)</option>
                                    </select>
                                    @error('status') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Harga & Stok Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block font-medium text-sage-800 mb-1">Harga Satuan (Rp) <span class="text-rose-500">*</span></label>
                                    <input type="number" step="0.01" min="0" wire:model="price"
                                        class="w-full px-3 py-2 border rounded-xl text-sage-900 text-xs focus:ring-2 focus:ring-sage-500 focus:outline-none {{ $errors->has('price') ? 'border-rose-400' : 'border-sage-300' }}"
                                        placeholder="0">
                                    @error('price') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-medium text-sage-800 mb-1">Jumlah Stok Fisik <span class="text-rose-500">*</span></label>
                                    <input type="number" min="0" wire:model="stock"
                                        class="w-full px-3 py-2 border rounded-xl text-sage-900 text-xs focus:ring-2 focus:ring-sage-500 focus:outline-none {{ $errors->has('stock') ? 'border-rose-400' : 'border-sage-300' }}"
                                        placeholder="0">
                                    @error('stock') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block font-medium text-sage-800 mb-1">Deskripsi & Spesifikasi Produk</label>
                                <textarea wire:model="description" rows="3"
                                    class="w-full px-3 py-2 border rounded-xl text-sage-900 text-xs focus:ring-2 focus:ring-sage-500 focus:outline-none {{ $errors->has('description') ? 'border-rose-400' : 'border-sage-300' }}"
                                    placeholder="Ukuran kertas, jenis tinta, gramatur, pigmen, dll..."></textarea>
                                @error('description') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Modal Footer Buttons -->
                        <div class="p-4 bg-sage-50/50 border-t border-sage-100 flex items-center justify-end gap-2.5">
                            <button type="button" wire:click="closeFormModal"
                                class="px-4 py-2 rounded-xl border border-sage-300 text-sage-700 hover:bg-sage-100 font-semibold text-xs transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 rounded-xl bg-terracotta-500 hover:bg-terracotta-600 text-white font-semibold text-xs transition-colors shadow-sm shadow-terracotta-500/20">
                                <span wire:loading.remove wire:target="saveProduct">{{ $isEditing ? 'Simpan Perubahan' : 'Tambahkan Produk' }}</span>
                                <span wire:loading wire:target="saveProduct">Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL KONFIRMASI HAPUS (SOFT DELETE) -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="delete-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <!-- Backdrop -->
                <div wire:click="cancelDelete" class="fixed inset-0 bg-sage-950/40 backdrop-blur-sm transition-opacity"></div>

                <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-sage-200 p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-sage-900" id="delete-title">
                                Konfirmasi Pindahkan ke Sampah?
                            </h3>
                            <p class="text-xs text-sage-600 mt-1.5 leading-relaxed">
                                Anda akan menghapus produk <strong class="text-sage-900 font-semibold">"{{ $deleteTargetName }}"</strong>. Data akan dipindahkan ke Sampah (Soft Delete) dan dapat dipulihkan kapan saja.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-2.5">
                        <button type="button" wire:click="cancelDelete"
                            class="px-4 py-2 rounded-xl border border-sage-300 text-sage-700 hover:bg-sage-100 font-semibold text-xs transition-colors">
                            Batal
                        </button>
                        <button type="button" wire:click="deleteProduct"
                            class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs transition-colors shadow-sm shadow-rose-600/20">
                            <span wire:loading.remove wire:target="deleteProduct">Ya, Pindahkan ke Sampah</span>
                            <span wire:loading wire:target="deleteProduct">Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@livewireScripts