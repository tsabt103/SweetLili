<x-app-layout>
    <x-slot name="header">
        Dokumentasi & Generator Token REST API
    </x-slot>
    <x-slot name="subtitle">
        Integrasi REST API terproteksi token Sanctum dengan respons JSON terstruktur
    </x-slot>

    <!-- Token Generator Card -->
    <div class="bg-white rounded-2xl border border-sage-200/80 p-5 shadow-sm mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-sage-100">
            <div>
                <h3 class="font-bold text-sage-900 text-sm">Generator Personal Access Token (Sanctum)</h3>
                <p class="text-xs text-sage-600 mt-0.5">Buat token bearer untuk mengakses endpoint mutasi (POST, PUT, DELETE) produk</p>
            </div>

            <form method="POST" action="{{ route('api.tokens.create') }}" class="flex items-center gap-2">
                @csrf
                <input type="text" name="token_name" required placeholder="Contoh: MobileApp Token"
                    class="px-3 py-1.5 border border-sage-300 rounded-xl text-xs text-sage-900 focus:ring-2 focus:ring-sage-500 focus:outline-none">
                <button type="submit"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-white bg-terracotta-500 hover:bg-terracotta-600 transition-colors shadow-sm">
                    Buat Token Baru
                </button>
            </form>
        </div>

        @if($plainToken)
            <div class="mt-4 p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs">
                <div class="font-bold text-amber-900 mb-1 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Salin Token API Anda Sekarang (Hanya Ditampilkan Sekali):
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <input type="text" readonly value="{{ $plainToken }}" id="plainTokenInput"
                        class="w-full font-mono text-xs p-2 rounded-lg bg-white border border-amber-300 text-amber-900 select-all">
                    <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('plainTokenInput').value); alert('Token disalin!');"
                        class="px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shrink-0">
                        Salin
                    </button>
                </div>
            </div>
        @endif

        <!-- Daftar Token Aktif -->
        <div class="mt-4">
            <h4 class="text-xs font-semibold text-sage-800 uppercase tracking-wider text-[11px] mb-2">Token Aktif Anda:</h4>
            @if($tokens->isEmpty())
                <p class="text-xs text-sage-500">Belum ada token Sanctum yang dibuat. Buat satu di atas untuk pengujian API.</p>
            @else
                <div class="space-y-2">
                    @foreach($tokens as $t)
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-cream-50/70 border border-sage-200/60 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="font-bold text-sage-900">{{ $t->name }}</span>
                                <span class="text-sage-500 text-[11px]">Dibuat: {{ $t->created_at->diffForHumans() }}</span>
                            </div>
                            <form method="POST" action="{{ route('api.tokens.revoke', $t->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold text-xs">
                                    Cabut (Revoke)
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- API Reference Grid -->
    <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-5 space-y-6">
        <div>
            <h3 class="font-bold text-sage-900 text-sm">Referensi Endpoint REST API Produk</h3>
            <p class="text-xs text-sage-600 mt-0.5">Semua respons menggunakan JSON dengan format konsisten: <code>success</code>, <code>message</code>, dan <code>data</code>.</p>
        </div>

        <div class="space-y-4 text-xs">
            <!-- 1. POST /api/auth/login -->
            <div class="border border-sage-200 rounded-xl p-4 bg-cream-50/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 font-mono">
                        <span class="px-2 py-0.5 rounded bg-amber-600 text-white font-bold text-[10px]">POST</span>
                        <span class="font-bold text-sage-900">/api/auth/login</span>
                    </div>
                    <span class="text-[11px] text-sage-500">Publik (Login API)</span>
                </div>
                <p class="text-sage-600 mt-2">Dapatkan token Sanctum dengan mengirim JSON <code>{ "email": "...", "password": "..." }</code>.</p>
            </div>

            <!-- 2. GET /api/products -->
            <div class="border border-sage-200 rounded-xl p-4 bg-cream-50/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 font-mono">
                        <span class="px-2 py-0.5 rounded bg-emerald-600 text-white font-bold text-[10px]">GET</span>
                        <span class="font-bold text-sage-900">/api/products</span>
                    </div>
                    <span class="text-[11px] text-sage-500">Query params: <code>search</code>, <code>category</code>, <code>status</code>, <code>low_stock=1</code>, <code>per_page</code></span>
                </div>
                <p class="text-sage-600 mt-2">Daftar semua produk dengan pagination, filter kategori, status, dan stok menipis.</p>
                <div class="mt-2 bg-sage-900 text-sage-100 p-2.5 rounded-lg font-mono text-[11px] overflow-x-auto">
                    curl -X GET "{{ url('/api/products') }}?low_stock=1" -H "Accept: application/json"
                </div>
            </div>

            <!-- 3. GET /api/products/{id} -->
            <div class="border border-sage-200 rounded-xl p-4 bg-cream-50/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 font-mono">
                        <span class="px-2 py-0.5 rounded bg-emerald-600 text-white font-bold text-[10px]">GET</span>
                        <span class="font-bold text-sage-900">/api/products/{id}</span>
                    </div>
                    <span class="text-[11px] text-sage-500">Detail Produk Spesifik</span>
                </div>
                <p class="text-sage-600 mt-2">Mengambil data lengkap satu produk berdasarkan ID.</p>
            </div>

            <!-- 4. POST /api/products -->
            <div class="border border-sage-200 rounded-xl p-4 bg-cream-50/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 font-mono">
                        <span class="px-2 py-0.5 rounded bg-blue-600 text-white font-bold text-[10px]">POST</span>
                        <span class="font-bold text-sage-900">/api/products</span>
                    </div>
                    <span class="text-[11px] font-bold text-terracotta-600">Perlu Token (auth:sanctum)</span>
                </div>
                <p class="text-sage-600 mt-2">Membuat produk baru. Invalidasi cache otomatis dan memicu pengecekan stok jika stok &le; 10.</p>
                <div class="mt-2 bg-sage-900 text-sage-100 p-2.5 rounded-lg font-mono text-[11px] overflow-x-auto">
curl -X POST "{{ url('/api/products') }}" \<br>
  -H "Authorization: Bearer &lt;TOKEN&gt;" \<br>
  -H "Content-Type: application/json" \<br>
  -H "Accept: application/json" \<br>
  -d '{"name": "Gouache Paint Set", "category": "Cat & Media Lukis", "price": 95000, "stock": 12, "status": "active"}'
                </div>
            </div>

            <!-- 5. PUT /api/products/{id} -->
            <div class="border border-sage-200 rounded-xl p-4 bg-cream-50/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 font-mono">
                        <span class="px-2 py-0.5 rounded bg-indigo-600 text-white font-bold text-[10px]">PUT</span>
                        <span class="font-bold text-sage-900">/api/products/{id}</span>
                    </div>
                    <span class="text-[11px] font-bold text-terracotta-600">Perlu Token (auth:sanctum)</span>
                </div>
                <p class="text-sage-600 mt-2">Memperbarui data produk dan memperbarui cache.</p>
            </div>

            <!-- 6. DELETE /api/products/{id} -->
            <div class="border border-sage-200 rounded-xl p-4 bg-cream-50/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 font-mono">
                        <span class="px-2 py-0.5 rounded bg-rose-600 text-white font-bold text-[10px]">DELETE</span>
                        <span class="font-bold text-sage-900">/api/products/{id}</span>
                    </div>
                    <span class="text-[11px] font-bold text-terracotta-600">Perlu Token (auth:sanctum)</span>
                </div>
                <p class="text-sage-600 mt-2">Soft delete produk (memindahkan ke trash).</p>
            </div>
        </div>
    </div>
</x-app-layout>
