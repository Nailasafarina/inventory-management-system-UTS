@extends('layouts.tailwind')

@section('title', 'Katalog & Pesanan Pelanggan')

@section('content')
<div class="space-y-4">

    <!-- Header Sambutan Pelanggan (Pastel Coksu - Rigid & Functional) -->
    <div class="bg-[#FAF3EA] border border-gray-200 rounded-sm px-4 py-3 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-sm bg-[#DEC8B0] border border-gray-300 text-[#4A392B] flex items-center justify-center text-lg shrink-0 shadow-sm">
                <i class="bi bi-shop"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-base font-bold text-[#2E2218]">Halo, {{ $user->name }}</h1>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-sm text-[11px] font-medium bg-[#EBDCCB] text-[#4A392B] border border-gray-300">
                        <i class="bi bi-person-check text-xs"></i> Portal Pelanggan
                    </span>
                </div>
                <p class="text-xs text-[#735B44] mt-0.5">
                    Katalog belanja cemilan dan minuman dengan sistem pemesanan langsung.
                </p>
            </div>
        </div>
        <div class="shrink-0 hidden sm:flex items-center gap-2 px-2.5 py-1 rounded-sm bg-[#FAF7F2] text-xs border border-gray-200 text-[#735B44]">
            <i class="bi bi-envelope text-[#9C8167]"></i>
            <span>{{ $user->email }}</span>
        </div>
    </div>

    <!-- 3 Kotak Ringkasan Belanja (Summary Cards - Rigid, rounded-sm, border-gray-200, shadow-sm) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
        <!-- Card 1: Total Pesanan -->
        <div class="bg-[#FAF3EA] rounded-sm p-3.5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="overflow-hidden pe-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#735B44] block">Total Pesanan</span>
                    <div class="mt-1 flex items-baseline gap-1.5">
                        <span class="text-xl font-bold text-[#2E2218] tracking-tight">{{ number_format($totalOrders) }}</span>
                        <span class="text-xs text-[#735B44]">Pesanan</span>
                    </div>
                    <div class="text-[11px] text-[#9C8167] mt-1 truncate flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-sm bg-[#C2A78C]"></span>
                        <span>Riwayat transaksi Anda</span>
                    </div>
                </div>
                <div class="w-9 h-9 rounded-sm bg-[#DEC8B0] border border-gray-300 text-[#4A392B] flex items-center justify-center text-base shrink-0 shadow-sm">
                    <i class="bi bi-bag-check"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Pengeluaran -->
        <div class="bg-[#FAF3EA] rounded-sm p-3.5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="overflow-hidden pe-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#735B44] block">Total Belanja</span>
                    <div class="mt-1 flex items-baseline">
                        <span class="text-xl font-bold tracking-tight text-[#2E2218] truncate" title="Rp {{ number_format($totalSpent, 0, ',', '.') }}">
                            Rp {{ number_format($totalSpent, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="text-[11px] text-green-800 mt-1 truncate flex items-center gap-1.5 font-medium">
                        <span class="w-1.5 h-1.5 rounded-sm bg-green-600"></span>
                        <span>Transaksi terkonfirmasi</span>
                    </div>
                </div>
                <div class="w-9 h-9 rounded-sm bg-[#EBDCCB] border border-gray-300 text-[#4A392B] flex items-center justify-center text-base shrink-0 shadow-sm">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Barang Diterima -->
        <div class="bg-[#FAF3EA] rounded-sm p-3.5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="overflow-hidden pe-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#735B44] block">Barang Diterima</span>
                    <div class="mt-1 flex items-baseline gap-1.5">
                        <span class="text-xl font-bold text-[#2E2218] tracking-tight">{{ number_format($totalItemsBought) }}</span>
                        <span class="text-xs text-[#735B44]">Unit Produk</span>
                    </div>
                    <div class="text-[11px] text-[#9C8167] mt-1 truncate flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-sm bg-[#9C8167]"></span>
                        <span>Total unit terbeli</span>
                    </div>
                </div>
                <div class="w-9 h-9 rounded-sm bg-[#DEC8B0] border border-gray-300 text-[#4A392B] flex items-center justify-center text-base shrink-0 shadow-sm">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Layout Dua Kolom: Katalog Produk (Kiri 7) & Pesanan Saya (Kanan 5) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">

        <!-- Kolom Kiri: Katalog Produk (7 Kolom) -->
        <div class="lg:col-span-7 bg-[#FAF3EA] rounded-sm border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <!-- Header Katalog -->
            <div class="px-4 py-2.5 border-b border-gray-200 bg-[#EBDCCB] flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <i class="bi bi-grid text-[#4A392B]"></i>
                    <h2 class="text-xs font-bold text-[#2E2218] uppercase tracking-wider">Katalog Produk</h2>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-sm text-[11px] font-medium bg-[#FAF7F2] text-[#4A392B] border border-gray-300">
                    <span>{{ $catalog->total() }} Produk Tersedia</span>
                </span>
            </div>

            <div class="p-4">
                <!-- Form Pencarian & Filter -->
                <form method="GET" action="{{ route('customer.dashboard') }}" class="mb-4 p-2.5 rounded-sm bg-[#FAF7F2] border border-gray-200">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                        <!-- Input Pencarian -->
                        <div class="sm:col-span-6 relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-[#735B44]">
                                <i class="bi bi-search text-xs"></i>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Cari nama barang atau SKU..." 
                                   class="w-full pl-8 pr-3 py-1.5 text-xs bg-white border border-gray-300 rounded-sm text-[#2E2218] placeholder-[#9C8167]/60 focus:outline-none focus:border-[#C2A78C] transition">
                        </div>

                        <!-- Dropdown Kategori -->
                        <div class="sm:col-span-4">
                            <select name="category_id" 
                                    class="w-full px-2.5 py-1.5 text-xs bg-white border border-gray-300 rounded-sm text-[#4A392B] focus:outline-none focus:border-[#C2A78C] transition cursor-pointer">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tombol Filter -->
                        <div class="sm:col-span-2 flex items-center gap-1.5">
                            <button type="submit" 
                                    class="flex-1 inline-flex items-center justify-center gap-1 py-1.5 px-3 text-xs font-semibold rounded-sm text-white bg-[#4A392B] hover:bg-[#382B20] border border-gray-300 shadow-sm transition cursor-pointer"
                                    title="Terapkan Filter">
                                <i class="bi bi-funnel text-xs"></i>
                                <span>Filter</span>
                            </button>
                            @if(request()->hasAny(['search', 'category_id']))
                            <a href="{{ route('customer.dashboard') }}" 
                               class="p-1.5 inline-flex items-center justify-center text-xs font-semibold rounded-sm text-[#4A392B] bg-[#EBDCCB] hover:bg-[#DEC8B0] border border-gray-300 transition"
                               title="Reset">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Grid Card Produk (Pastel Coksu - Rigid) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @forelse($catalog as $product)
                    <div class="bg-white rounded-sm border border-gray-200 p-3 shadow-sm hover:border-gray-400 transition flex flex-col justify-between group">
                        <div>
                            @if($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image))
                            <div class="mb-2 overflow-hidden rounded-sm border border-gray-200 h-28 bg-[#FAF7F2]">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-200">
                            </div>
                            @endif

                            <!-- Kategori & SKU -->
                            <div class="flex items-center justify-between gap-1.5 mb-2">
                                <span class="inline-block px-2 py-0.5 rounded-sm text-[10px] font-medium bg-[#FAF4EC] text-[#735B44] border border-gray-200 truncate max-w-[130px]">
                                    {{ $product->category->name ?? 'Umum' }}
                                </span>
                                <code class="text-[10px] text-[#735B44] font-mono bg-[#FAF7F2] px-1.5 py-0.5 rounded-sm border border-gray-200">
                                    {{ $product->sku }}
                                </code>
                            </div>

                            <!-- Nama Produk -->
                            <h3 class="font-semibold text-[#2E2218] text-xs leading-snug group-hover:text-[#4A392B] transition line-clamp-2" title="{{ $product->name }}">
                                {{ $product->name }}
                            </h3>
                        </div>

                        <!-- Harga, Stok & Tombol Pesan -->
                        <div class="mt-3 pt-2 border-t border-gray-100 flex items-end justify-between gap-2">
                            <div>
                                <span class="text-[10px] text-[#9C8167] font-medium block leading-none">Harga</span>
                                <div class="text-xs sm:text-sm font-bold mt-0.5 text-[#2E2218]">
                                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-[#735B44] mt-1 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-sm {{ $product->stock > 5 ? 'bg-green-600' : 'bg-red-600' }}"></span>
                                    <span>Stok: <strong>{{ $product->stock }}</strong> unit</span>
                                </div>
                            </div>

                            <button type="button" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-sm text-white bg-[#4A392B] hover:bg-[#382B20] border border-gray-300 shadow-sm transition cursor-pointer shrink-0"
                                    onclick="openBuyModal('{{ $product->id }}', '{{ addslashes($product->name) }}', '{{ $product->selling_price }}', '{{ $product->stock }}')">
                                <i class="bi bi-cart-plus text-xs"></i>
                                <span>Pesan</span>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-8 text-center text-[#735B44]">
                        <i class="bi bi-inbox text-2xl block mb-2 text-[#9C8167]"></i>
                        <p class="font-semibold text-[#2E2218] text-xs">Tidak ada produk ditemukan</p>
                        <p class="text-[11px] text-[#735B44] mt-0.5">Silakan gunakan kata kunci lain atau pilih semua kategori.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination Katalog Produk -->
                @if($catalog->hasPages())
                <div class="mt-3 pt-2.5 border-t border-gray-200">
                    {{ $catalog->links('pagination::tailwind') }}
                </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Pesanan Saya (5 Kolom) -->
        <div class="lg:col-span-5 bg-[#FAF3EA] rounded-sm border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <!-- Header Pesanan -->
            <div class="px-4 py-2.5 border-b border-gray-200 bg-[#EBDCCB] flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <i class="bi bi-receipt text-[#4A392B]"></i>
                    <h2 class="text-xs font-bold text-[#2E2218] uppercase tracking-wider">Pesanan Saya</h2>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-sm text-[11px] font-semibold bg-green-100 text-green-800 border border-green-300">
                    <span>{{ $mySales->total() }} Transaksi</span>
                </span>
            </div>

            <!-- Tabel Riwayat Belanja (Rigid rounded-sm) -->
            <div class="overflow-x-auto max-h-[420px] overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-10 bg-[#EBDCCB] border-b border-gray-200">
                        <tr class="text-[11px] font-semibold text-[#4A392B]">
                            <th class="px-3 py-2">No. Faktur</th>
                            <th class="px-3 py-2">Waktu &amp; Item</th>
                            <th class="px-3 py-2 text-right">Total</th>
                            <th class="px-3 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-xs bg-white">
                        @forelse($mySales as $sale)
                        <tr class="hover:bg-[#FAF7F2] transition">
                            <!-- Nomor Faktur -->
                            <td class="px-3 py-2.5 whitespace-nowrap">
                                <a href="{{ route('customer.sales.show', $sale) }}" 
                                   class="font-mono font-semibold text-[#4A392B] hover:text-[#2E2218] hover:underline inline-flex items-center gap-1 text-xs" 
                                   title="Lihat Faktur">
                                    <code>{{ $sale->sale_number }}</code>
                                </a>
                            </td>

                            <!-- Tanggal & Item -->
                            <td class="px-3 py-2.5">
                                <div class="font-semibold text-[#2E2218] text-xs">{{ $sale->sold_at->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-[#735B44]">{{ $sale->sold_at->format('H:i') }} WIB</div>
                                <div class="mt-1 text-[11px] text-[#5C4A3E] space-y-0.5">
                                    @foreach($sale->items->take(2) as $sItem)
                                    <div class="truncate max-w-[130px]">&bull; {{ $sItem->quantity }}x {{ $sItem->product->name ?? 'Produk' }}</div>
                                    @endforeach
                                    @if($sale->items->count() > 2)
                                    <div class="text-[10px] text-[#9C8167] italic">+{{ $sale->items->count() - 2 }} item lainnya</div>
                                    @endif
                                </div>
                            </td>

                            <!-- Total Transaksi -->
                            <td class="px-3 py-2.5 text-right whitespace-nowrap">
                                <div class="font-bold text-xs text-[#2E2218]">
                                    Rp {{ number_format($sale->total, 0, ',', '.') }}
                                </div>
                                <span class="inline-flex items-center px-1.5 py-0.2 rounded-sm text-[10px] font-semibold bg-green-100 text-green-800 border border-green-300">
                                    Completed
                                </span>
                            </td>

                            <!-- Tombol Aksi Detail Nota -->
                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                <a href="{{ route('customer.sales.show', $sale) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-sm bg-[#FAF7F2] hover:bg-white text-[#4A392B] border border-gray-300 transition shadow-sm" 
                                   title="Lihat Bukti Nota">
                                    <i class="bi bi-file-earmark-text text-xs"></i>
                                    <span>Nota</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-[#735B44] bg-[#FAF3EA]">
                                <i class="bi bi-bag-x text-2xl block mb-2 text-[#9C8167]"></i>
                                <p class="font-semibold text-[#2E2218] text-xs">Belum ada riwayat pesanan</p>
                                <p class="text-[11px] text-[#735B44] mt-0.5">Pesanan Anda akan dicatat otomatis di sini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Riwayat Belanja -->
            @if($mySales->hasPages())
            <div class="p-2.5 bg-[#FAF3EA] border-t border-gray-200">
                {{ $mySales->links('pagination::tailwind') }}
            </div>
            @endif
        </div>

    </div>

</div>

<!-- Modal Beli Produk (Rigid rounded-sm, border-gray-200, shadow-sm) -->
<div id="buyModal" class="fixed inset-0 z-50 hidden bg-[#2E2218]/30 backdrop-blur-xs items-center justify-center p-4">
    <div class="relative w-full max-w-md bg-[#FAF3EA] rounded-sm shadow-sm overflow-hidden border border-gray-200 transform transition-all">
        <form action="{{ route('customer.checkout') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" id="modalProductId">

            <!-- Modal Header (Soft Tan / Coksu) -->
            <div class="px-4 py-3 bg-[#EBDCCB] text-[#2E2218] flex items-center justify-between border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-sm bg-[#DEC8B0] border border-gray-300 flex items-center justify-center text-[#4A392B]">
                        <i class="bi bi-cart-plus"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-[#2E2218] leading-tight">Konfirmasi Pesanan</h3>
                        <p class="text-[11px] text-[#735B44]">Tentukan jumlah pesanan cemilan/minuman</p>
                    </div>
                </div>
                <button type="button" 
                        onclick="closeBuyModal()" 
                        class="w-6 h-6 rounded-sm text-[#735B44] hover:text-[#2E2218] hover:bg-[#FAF4EC] flex items-center justify-center transition cursor-pointer">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-4 space-y-3">
                <!-- Info Produk -->
                <div class="p-3 rounded-sm border border-gray-200 bg-white">
                    <span class="text-[10px] text-[#735B44] font-semibold uppercase tracking-wider block">Produk Terpilih</span>
                    <h4 class="font-bold text-[#2E2218] text-sm mt-0.5" id="modalProductName">-</h4>
                    
                    <div class="grid grid-cols-2 gap-3 mt-2 pt-2 border-t border-gray-100">
                        <div>
                            <span class="text-[10px] text-[#9C8167] font-medium block">Harga Satuan</span>
                            <span class="font-bold text-sm block mt-0.5 text-[#2E2218]" id="modalProductPrice">Rp 0</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-[#9C8167] font-medium block">Sisa Stok</span>
                            <span class="font-bold text-[#4A392B] text-sm block mt-0.5" id="modalProductStock">0 unit</span>
                        </div>
                    </div>
                </div>

                <!-- Input Kuantitas -->
                <div>
                    <label for="modalQuantity" class="block text-xs font-semibold text-[#5C4A3E] mb-1">
                        Jumlah Kuantitas <span class="text-red-600">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                                id="modalQuantity" 
                                name="quantity" 
                                min="1" 
                                value="1" 
                                required 
                                oninput="calcModalSubtotal()"
                                class="w-full px-3 py-1.5 text-sm bg-white border border-gray-300 rounded-sm focus:outline-none focus:border-[#C2A78C] text-[#2E2218] font-semibold transition">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-xs text-[#9C8167] font-medium">
                            Unit
                        </div>
                    </div>
                </div>

                <!-- Catatan Pesanan -->
                <div>
                    <label for="modalNotes" class="block text-xs font-semibold text-[#5C4A3E] mb-1">
                        Catatan Pesanan <span class="text-[#9C8167] font-normal lowercase">(opsional)</span>
                    </label>
                    <input type="text" 
                           id="modalNotes" 
                           name="notes" 
                           placeholder="Contoh: Kurangi manis, bungkus terpisah..." 
                           class="w-full px-3 py-1.5 text-xs bg-white border border-gray-300 rounded-sm focus:outline-none focus:border-[#C2A78C] text-[#2E2218] placeholder-[#9C8167]/60 transition">
                </div>

                <!-- Total Pembayaran -->
                <div class="p-3 rounded-sm border border-gray-200 bg-white flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-[#5C4A3E] block">Total Pembayaran</span>
                        <span class="text-[10px] text-[#9C8167]">Harga Satuan &times; Kuantitas</span>
                    </div>
                    <div class="text-base font-bold text-[#2E2218]" id="modalSubtotalText">
                        Rp 0
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-4 py-2.5 bg-[#FAF4EC] border-t border-gray-200 flex items-center justify-end gap-2">
                <button type="button" 
                        onclick="closeBuyModal()" 
                        class="px-3 py-1.5 rounded-sm border border-gray-300 text-[#5C4A3E] font-medium hover:bg-white text-xs transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" 
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-sm text-white font-semibold text-xs bg-[#4A392B] hover:bg-[#382B20] border border-gray-300 shadow-sm transition cursor-pointer">
                    <i class="bi bi-check-circle text-xs"></i>
                    <span>Konfirmasi Pesanan</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let currentPrice = 0;
    let currentStock = 0;

    function openBuyModal(id, name, price, stock) {
        document.getElementById('modalProductId').value = id;
        document.getElementById('modalProductName').textContent = name;
        currentPrice = parseFloat(price);
        currentStock = parseInt(stock);

        document.getElementById('modalProductPrice').textContent = 'Rp ' + currentPrice.toLocaleString('id-ID');
        document.getElementById('modalProductStock').textContent = currentStock + ' unit';
        
        const qtyInput = document.getElementById('modalQuantity');
        qtyInput.value = 1;
        qtyInput.max = currentStock;

        calcModalSubtotal();

        const modal = document.getElementById('buyModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        qtyInput.focus();
    }

    function closeBuyModal() {
        const modal = document.getElementById('buyModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function calcModalSubtotal() {
        const qty = parseInt(document.getElementById('modalQuantity').value) || 0;
        const subtotal = qty * currentPrice;
        document.getElementById('modalSubtotalText').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    }

    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBuyModal();
        }
    });

    document.getElementById('buyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBuyModal();
        }
    });
</script>
@endpush
@endsection