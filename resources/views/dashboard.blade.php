<x-app-layout>
{{-- DESAIN BARU - TOP NAVBAR + CARD 2x2 --}}
<div style="max-width:1100px; margin:0 auto; padding:20px; font-family:sans-serif;">

  {{-- HEADER ATAS --}}
  <div style="display:flex; justify-content:space-between; align-items:center; background:#fff; padding:16px 20px; border-radius:20px; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin-bottom:20px;">
    <div>
      <h1 style="margin:0; font-size:22px;">Halo, Admin SweetLili! 👋</h1>
      <p style="margin:4px 0 0; color:#888; font-size:13px;">Dashboard Ikhtisar Toko - Pantau stok & produkmu disini</p>
    </div>
    
    <span style="background:#ff2e7e; color:#fff; padding:10px 18px; border-radius:999px; font-weight:bold; font-size:13px;">+ Tambah Produk</span>
  </div>

  {{-- 4 CARD JADI 2x2 --}}
  <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
    <div style="background:linear-gradient(135deg,#6d28d9,#9333ea); color:#fff; padding:22px; border-radius:24px;">
      <p style="margin:0; opacity:.8; font-size:12px; letter-spacing:1px;">TOTAL KATALOG PRODUK</p>
      <h2 style="margin:8px 0 4px; font-size:36px;">{{ $stats['total_products'] ?? 11 }}</h2>
      <p style="margin:0; font-size:12px; opacity:.8;">{{ $stats['active_products'] ?? 11 }} aktif • {{ $stats['inactive_products'] ?? 0 }} nonaktif</p>
    </div>
    <div style="background:linear-gradient(135deg,#ff2e7e,#ff6b9d); color:#fff; padding:22px; border-radius:24px;">
      <p style="margin:0; opacity:.8; font-size:12px; letter-spacing:1px;">TOTAL STOK FISIK</p>
      <h2 style="margin:8px 0 4px; font-size:36px;">{{ $stats['total_stock'] ?? 273 }}</h2>
      <p style="margin:0; font-size:12px; opacity:.8;">Nilai Aset: Rp {{ number_format($stats['total_value'] ?? 4788000, 0, ',', '.') }}</p>
    </div>
    <div style="background:#fff; padding:22px; border-radius:24px; box-shadow:0 4px 20px rgba(0,0,0,0.06); border-left:6px solid #f59e0b;">
      <p style="margin:0; color:#888; font-size:12px; letter-spacing:1px;">STOK MENIPIS (≤ 10)</p>
      <h2 style="margin:8px 0 4px; font-size:36px; color:#f59e0b;">{{ $stats['low_stock_count'] ?? 1 }}</h2>
      <p style="margin:0; font-size:12px; color:#888;">Perlu restock segera</p>
    </div>
    <div style="background:#fff; padding:22px; border-radius:24px; box-shadow:0 4px 20px rgba(0,0,0,0.06); border-left:6px solid #10b981;">
      <p style="margin:0; color:#888; font-size:12px; letter-spacing:1px;">PRODUK TERSEDIA & AKTIF</p>
      <h2 style="margin:8px 0 4px; font-size:36px; color:#10b981;">{{ $stats['active_products'] ?? 11 }}</h2>
      <p style="margin:0; font-size:12px; color:#888;">Siap transaksi pelanggan</p>
    </div>
  </div>

  {{-- TABEL + KATEGORI JADI ATAS-BAWAH, BUKAN KIRI-KANAN --}}
  <div style="background:#fff; padding:20px; border-radius:24px; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin-bottom:16px;">
    <h3 style="margin:0 0 14px;">⚠️ Ringkasan Produk Stok Kritis (≤ 10 Unit)</h3>
    @if(($stats['low_stock_count'] ?? 0) > 0)
  <p style="color:#d97706; font-size:13px;">Ada {{ $stats['low_stock_count'] }} produk yang stoknya ≤ 10 unit. Cek di menu Katalog Produk ya.</p>
@else
  <p style="color:#888; font-size:13px;">Aman, tidak ada produk yang stoknya kritis 🎉</p>
@endif
  </div>

  <div style="background:#fff; padding:20px; border-radius:24px; box-shadow:0 4px 20px rgba(0,0,0,0.06);">
    <h3 style="margin:0 0 14px;">📦 Distribusi Kategori</h3>
    @if(!empty($stats['categories']))
      @foreach($categories ?? [] as $cat)
      <div>{{ $cat->name }} - {{ $cat->products_count }} produk</div>
<div style="background:#e5e7eb; height:8px; border-radius:99px;">
  <div style="background:#7c3aed; height:8px; border-radius:99px; width:{{ $cat->products_count > 0 ? ($cat->products_count / max($total_products,1) * 100) : 0 }}%"></div>
</div>
      <div style="margin-bottom:12px;">
        <div style="display:flex; justify-content:space-between; font-size:13px;"><span>{{ $cat->name }}</span><span>{{ $cat->products_count }} produk</span></div>
        <div style="height:8px; background:#f1f1f1; border-radius:999px; margin-top:6px;"><div style="height:8px; width:{{ ($cat->products_count / max($stats['total_products'],1)) * 100 }}%; background:#8b5cf6; border-radius:999px;"></div></div>
      </div>
      @endforeach
    @endif
  </div>

</div>
</x-app-layout>