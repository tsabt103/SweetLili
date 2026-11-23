<div>
    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('products.index') }}" class="text-xs text-sage-500 hover:text-sage-700">
                    &larr; Kembali ke Katalog
                </a>
            </div>
            <h2 class="text-xl font-extrabold text-sage-900 tracking-tight mt-1">Sampah Produk (Soft Delete)</h2>
            <p class="text-xs text-sage-600">Daftar produk yang dinonaktifkan sementara. Anda dapat memulihkan (restore) atau menghapus permanen.</p>
        </div>

        @if($trashedProducts->total() > 0)
            <div>
                <button wire:click="restoreAll"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-sage-800 bg-white hover:bg-sage-50 border border-sage-300 transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Pulihkan Semua ({{ $trashedProducts->total() }})
                </button>
            </div>
        @endif
    </div>

    <!-- Search in Trash -->
    <div class="bg-white p-4 rounded-2xl border border-sage-200/80 shadow-sm mb-6">
        <div class="max-w-md relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-sage-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Cari produk terhapus..."
                class="block w-full pl-9 pr-3 py-2 border border-sage-300 rounded-xl text-xs text-sage-900 placeholder-sage-400 focus:outline-none focus:ring-2 focus:ring-sage-500 focus:border-sage-500">
        </div>
    </div>

    <!-- Trash Table -->
    <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sage-100 text-left text-xs">
                <thead class="bg-sage-50/70 text-sage-600 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Nama Produk</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Harga Terakhir</th>
                        <th class="py-3.5 px-4">Stok Saat Dihapus</th>
                        <th class="py-3.5 px-4">Waktu Dihapus</th>
                        <th class="py-3.5 px-4 text-right">Opsi Pemulihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sage-100 text-sage-800">
                    @forelse($trashedProducts as $item)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-sage-900">
                                {{ $item->name }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-md bg-sage-100 text-sage-700 font-medium text-[11px]">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                {{ $item->stock }} unit
                            </td>
                            <td class="py-3.5 px-4 text-sage-500 text-[11px]">
                                {{ $item->deleted_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button wire:click="restore({{ $item->id }})"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 font-semibold text-xs transition-colors"
                                        title="Pulihkan produk">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Restore
                                    </button>

                                    <button wire:click="confirmForceDelete({{ $item->id }})"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 font-semibold text-xs transition-colors"
                                        title="Hapus permanen">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus Permanen
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-4 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-sage-50 text-sage-400 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-sage-800">Sampah kosong</p>
                                    <p class="text-xs text-sage-500 mt-1">Tidak ada produk yang sedang berada di folder sampah.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-sage-100 bg-sage-50/30">
            {{ $trashedProducts->links() }}
        </div>
    </div>

    <!-- Modal Konfirmasi Force Delete -->
    @if($showForceDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-force-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div wire:click="cancelForceDelete" class="fixed inset-0 bg-sage-950/40 backdrop-blur-sm transition-opacity"></div>

                <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-sage-200 p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-sage-900" id="modal-force-title">
                                Hapus Permanen?
                            </h3>
                            <p class="text-xs text-sage-600 mt-1.5 leading-relaxed">
                                Anda akan menghapus <strong class="text-sage-900 font-semibold">"{{ $forceDeleteName }}"</strong> secara permanen. Data ini tidak akan dapat dikembalikan lagi.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-2.5">
                        <button type="button" wire:click="cancelForceDelete"
                            class="px-4 py-2 rounded-xl border border-sage-300 text-sage-700 hover:bg-sage-100 font-semibold text-xs transition-colors">
                            Batal
                        </button>
                        <button type="button" wire:click="forceDelete"
                            class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs transition-colors shadow-sm shadow-rose-600/20">
                            Hapus Permanen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
