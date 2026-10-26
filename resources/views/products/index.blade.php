@extends('layouts.tailwind')

@section('title', 'Daftar Produk & Stok')

@section('content')
<!-- Header Halaman -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
    <div>
        <h1 class="text-xl font-bold tracking-tight text-[#2E2218]">Daftar Produk &amp; Inventaris</h1>
        <p class="text-xs text-[#735B44] mt-0.5">Kelola data produk cemilan &amp; minuman, SKU fisik, dan penetapan harga.</p>
    </div>
    @can('admin-only')
    <div>
        <a href="{{ route('products.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-sm font-semibold text-xs text-white bg-[#4A392B] hover:bg-[#382B20] border border-gray-300 shadow-sm transition">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Produk</span>
        </a>
    </div>
    @endcan
</div>

<!-- Filter & Pencarian (Rigid rounded-sm, border-gray-200, shadow-sm) -->
<div class="bg-[#FAF3EA] rounded-sm p-3.5 border border-gray-200 shadow-sm mb-4">
    <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-2.5 items-end">
        <!-- Input Pencarian -->
        <div class="md:col-span-4">
            <label for="search" class="block text-xs font-semibold text-[#5C4A3E] mb-1">
                Cari SKU / Nama Barang
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-[#735B44]">
                    <i class="bi bi-search text-xs"></i>
                </span>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Contoh: CM-KPK atau Keripik Kaca..." 
                       class="w-full pl-8 pr-3 py-1.5 text-xs bg-white border border-gray-300 rounded-sm text-[#2E2218] placeholder-[#9C8167]/60 focus:bg-white focus:outline-none focus:border-[#C2A78C] transition">
            </div>
        </div>

        <!-- Filter Kategori -->
        <div class="md:col-span-3">
            <label for="category_id" class="block text-xs font-semibold text-[#5C4A3E] mb-1">
                Kategori
            </label>
            <select id="category_id" name="category_id" 
                    class="w-full px-2.5 py-1.5 text-xs bg-white border border-gray-300 rounded-sm text-[#4A392B] focus:bg-white focus:outline-none focus:border-[#C2A78C] transition cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Status Stok -->
        <div class="md:col-span-3">
            <label for="stock_status" class="block text-xs font-semibold text-[#5C4A3E] mb-1">
                Status Stok
            </label>
            <select id="stock_status" name="stock_status" 
                    class="w-full px-2.5 py-1.5 text-xs bg-white border border-gray-300 rounded-sm text-[#4A392B] focus:bg-white focus:outline-none focus:border-[#C2A78C] transition cursor-pointer">
                <option value="">Semua Status Stok</option>
                <option value="available" {{ request('stock_status') === 'available' ? 'selected' : '' }}>Stok Aman (&gt; Min)</option>
                <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Stok Kritis (&le; Min)</option>
                <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Stok Habis (0)</option>
            </select>
        </div>

        <!-- Tombol Aksi Filter -->
        <div class="md:col-span-2 flex items-center gap-1.5">
            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-sm text-white bg-[#4A392B] hover:bg-[#382B20] border border-gray-300 shadow-sm transition cursor-pointer">
                <i class="bi bi-funnel text-xs"></i>
                <span>Filter</span>
            </button>
            @if(request()->hasAny(['search', 'category_id', 'stock_status']))
            <a href="{{ route('products.index') }}" class="p-1.5 text-xs font-medium rounded-sm text-[#4A392B] bg-[#EBDCCB] hover:bg-[#DEC8B0] border border-gray-300 transition" title="Reset Filter">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Data Produk (Rigid, rounded-sm, border-gray-200) -->
<div class="bg-[#FAF3EA] rounded-sm border border-gray-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="min-w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-[#EBDCCB] border-b border-gray-200 text-[#4A392B] font-semibold text-[11px]">
                    <th scope="col" class="px-3 py-2.5" style="width: 130px;">
                        Kode SKU
                    </th>
                    <th scope="col" class="px-3 py-2.5" style="min-width: 200px;">
                        Nama Produk
                    </th>
                    <th scope="col" class="px-3 py-2.5">
                        Kategori
                    </th>
                    <th scope="col" class="px-3 py-2.5 text-right">
                        Harga Beli
                    </th>
                    <th scope="col" class="px-3 py-2.5 text-right">
                        Harga Jual
                    </th>
                    <th scope="col" class="px-3 py-2.5 text-center">
                        Stok Fisik
                    </th>
                    <th scope="col" class="px-3 py-2.5 text-center">
                        Batas Min.
                    </th>
                    <th scope="col" class="px-3 py-2.5 text-center" style="min-width: 180px;">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($products as $product)
                <tr class="hover:bg-[#FAF7F2] transition group">
                    <!-- SKU Column -->
                    <td class="px-3 py-2.5 whitespace-nowrap">
                        <a href="{{ route('products.show', $product) }}" class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-xs font-mono font-semibold bg-[#FAF4EC] text-[#4A392B] border border-gray-200 hover:bg-[#EBDCCB] transition">
                            {{ $product->sku }}
                        </a>
                    </td>

                    <!-- Nama Produk & Foto -->
                    <td class="px-3 py-2.5">
                        <div class="flex items-center gap-2.5">
                            @if($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image))
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-9 h-9 object-cover rounded-sm border border-gray-300 shrink-0 shadow-xs">
                            @else
                                <div class="w-9 h-9 rounded-sm bg-[#FAF4EC] border border-gray-200 flex items-center justify-center text-[#735B44] text-xs shrink-0" title="Belum ada gambar">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                            <div>
                                <div class="font-semibold text-[#2E2218] group-hover:text-[#4A392B] transition">
                                    {{ $product->name }}
                                </div>
                                <span class="text-[10px] text-[#735B44] font-mono">ID: #{{ $product->id }}</span>
                            </div>
                        </div>
                    </td>

                    <!-- Kategori -->
                    <td class="px-3 py-2.5 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[11px] font-medium bg-[#FAF4EC] text-[#735B44] border border-gray-200">
                            {{ $product->category->name ?? 'Tanpa Kategori' }}
                        </span>
                    </td>

                    <!-- Harga Beli -->
                    <td class="px-3 py-2.5 text-right whitespace-nowrap text-[#735B44] font-medium">
                        Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                    </td>

                    <!-- Harga Jual -->
                    <td class="px-3 py-2.5 text-right whitespace-nowrap font-bold text-[#2E2218]">
                        Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                    </td>

                    <!-- Sisa Stok Fisik (Lencana Status Standar Operasional: Hijau = Aman, Merah = Kritis / Habis) -->
                    <td class="px-3 py-2.5 text-center whitespace-nowrap">
                        @if($product->stock <= 0)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-sm text-[11px] font-semibold bg-red-100 text-red-800 border border-red-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                Habis (0)
                            </span>
                        @elseif($product->stock <= $product->minimum_stock)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-sm text-[11px] font-semibold bg-red-100 text-red-800 border border-red-300" title="Stok kritis (&le; batas minimum)">
                                <i class="bi bi-exclamation-triangle-fill text-xs"></i>
                                {{ $product->stock }} unit (Kritis)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-sm text-[11px] font-semibold bg-green-100 text-green-800 border border-green-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                {{ $product->stock }} unit
                            </span>
                        @endif
                    </td>

                    <!-- Minimum Stok -->
                    <td class="px-3 py-2.5 text-center whitespace-nowrap text-[#735B44] font-medium">
                        {{ $product->minimum_stock }}
                    </td>

                    <!-- Kolom Aksi -->
                    <td class="px-3 py-2.5 text-center whitespace-nowrap">
                        <div class="inline-flex items-center gap-1">
                            <!-- Detail -->
                            <a href="{{ route('products.show', $product) }}" 
                               class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-sm text-[#4A392B] bg-[#FAF7F2] hover:bg-white border border-gray-300 transition shadow-sm" 
                               title="Lihat Detail Produk">
                                <i class="bi bi-eye"></i>
                                <span>Detail</span>
                            </a>

                            @can('admin-only')
                            <!-- Edit -->
                            <a href="{{ route('products.edit', $product) }}" 
                               class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-sm text-[#4A392B] bg-[#EBDCCB] hover:bg-[#DEC8B0] border border-gray-300 transition shadow-sm" 
                               title="Ubah Data Produk">
                                <i class="bi bi-pencil"></i>
                                <span>Edit</span>
                            </a>

                            <!-- Hapus -->
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ addslashes($product->name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-sm text-red-800 bg-red-50 hover:bg-red-100 border border-red-300 transition shadow-sm cursor-pointer" 
                                        title="Hapus Produk">
                                    <i class="bi bi-trash"></i>
                                    <span>Hapus</span>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-[#735B44] bg-[#FAF3EA]">
                        <i class="bi bi-inbox text-2xl d-block mb-2 text-[#9C8167]"></i>
                        <p class="font-semibold text-[#2E2218] text-xs">Tidak ada produk ditemukan</p>
                        <p class="text-[11px] text-[#735B44] mt-0.5">Silakan sesuaikan kata kunci pencarian atau filter.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages() || $products->total() > 0)
    <div class="px-3 py-2 bg-[#FAF3EA] border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#735B44]">
        <div>
            Menampilkan <span class="font-semibold text-[#2E2218]">{{ $products->firstItem() ?? 0 }}</span> - 
            <span class="font-semibold text-[#2E2218]">{{ $products->lastItem() ?? 0 }}</span> dari 
            <span class="font-semibold text-[#2E2218]">{{ $products->total() }}</span> produk
        </div>
        <div>
            {{ $products->links() }}
        </div>
    </div>
    @endif
</div>
@endsection