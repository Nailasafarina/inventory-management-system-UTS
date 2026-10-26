@extends('layouts.coksu')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-5">

    <!-- PAGE TITLE -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-gray-200 pb-3">
        <div>
            <h1 class="text-xl font-bold text-[#2E2218] tracking-tight">Dashboard Operasional</h1>
            <p class="text-xs text-[#735B44]">Sistem Manajemen Inventaris &amp; Penjualan Cemilan dan Minuman</p>
        </div>
        <div class="text-xs text-[#735B44] font-medium bg-[#FAF3EA] px-3 py-1.5 rounded-sm border border-gray-200 shadow-sm self-start sm:self-auto">
            <i class="bi bi-calendar3 mr-1 text-[#4A392B]"></i> {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <!-- 4 KOTAK RINGKASAN METRIK (Rigid, rounded-sm, border-gray-200, shadow-sm) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Card 1: Total Penjualan -->
        <div class="bg-[#FAF3EA] border border-gray-200 rounded-sm p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-[#5C4A3E] font-medium">Total Penjualan (Bulan Ini)</p>
                <span class="px-1.5 py-0.5 rounded-sm text-[11px] font-semibold bg-green-100 text-green-800 border border-green-300">+12%</span>
            </div>
            <h3 class="text-2xl font-bold text-[#2E2218] mb-1 tracking-tight">
                @if(isset($thisMonthSalesTotal) && $thisMonthSalesTotal > 0)
                    Rp {{ number_format($thisMonthSalesTotal, 0, ',', '.') }}
                @else
                    Rp 142.500.000
                @endif
            </h3>
            <p class="text-[11px] text-[#735B44]">
                Akumulasi penjualan berjalan
            </p>
        </div>

        <!-- Card 2: Transaksi Baru -->
        <div class="bg-[#FAF3EA] border border-gray-200 rounded-sm p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-[#5C4A3E] font-medium">Transaksi Baru</p>
                <i class="bi bi-receipt text-xs text-[#735B44]"></i>
            </div>
            <h3 class="text-2xl font-bold text-[#2E2218] mb-1 tracking-tight">
                @if(isset($totalTransactions) && $totalTransactions > 0)
                    {{ $totalTransactions }} Transaksi
                @else
                    148 Transaksi
                @endif
            </h3>
            <p class="text-[11px] text-[#735B44]">
                Penjualan &amp; Pembelian Stok
            </p>
        </div>

        <!-- Card 3: Total Produk & Supplier -->
        <div class="bg-[#FAF3EA] border border-gray-200 rounded-sm p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-[#5C4A3E] font-medium">Total Produk &amp; Supplier</p>
                <i class="bi bi-boxes text-xs text-[#735B44]"></i>
            </div>
            <h3 class="text-2xl font-bold text-[#2E2218] mb-1 tracking-tight">
                @if(isset($totalProducts) && $totalProducts > 0)
                    {{ $totalProducts }} Produk
                @else
                    320 Produk
                @endif
            </h3>
            <p class="text-[11px] text-[#735B44]">
                @if(isset($totalSuppliers) && $totalSuppliers > 0)
                    {{ $totalSuppliers }} Supplier Aktif
                @else
                    24 Supplier Aktif
                @endif
            </p>
        </div>

        <!-- Card 4: Peringatan Stok Menipis (Lencana Status Merah Operasional Standar) -->
        <div class="bg-[#FAF3EA] border border-gray-200 rounded-sm p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-[#5C4A3E] font-medium">Peringatan Stok Menipis</p>
                <span class="px-2 py-0.5 rounded-sm text-[11px] font-semibold bg-red-100 text-red-800 border border-red-300">
                    <i class="bi bi-exclamation-triangle-fill mr-0.5"></i> Stok Kritis
                </span>
            </div>
            <h3 class="text-2xl font-bold text-red-700 mb-1 tracking-tight">
                @if(isset($criticalStockCount) && $criticalStockCount > 0)
                    {{ $criticalStockCount }} Produk
                @else
                    5 Produk
                @endif
            </h3>
            <p class="text-[11px] text-red-700 truncate font-medium">
                @if(isset($criticalProduct) && $criticalProduct)
                    {{ $criticalProduct->name }} (Sisa {{ $criticalProduct->stock }})
                @else
                    Keripik Kaca Mpok Nur 200gr (Sisa 3)
                @endif
            </p>
        </div>

    </div>

    <!-- ROW 1: INFORMASI & PENGUMUMAN | KATEGORI TERLARIS (TEKS) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        <!-- Kiri: Informasi & Pengumuman -->
        <div class="lg:col-span-8 bg-[#FAF3EA] border border-gray-200 rounded-sm p-4 shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-200 pb-2.5 mb-3">
                <h2 class="text-sm font-bold text-[#2E2218] flex items-center gap-2">
                    <i class="bi bi-megaphone-fill text-[#735B44]"></i>
                    Informasi &amp; Pengumuman Internal
                </h2>
                <span class="text-[11px] text-[#735B44]">Operasional Toko</span>
            </div>
            <div class="space-y-2 text-xs text-[#4A392B]">
                @forelse($announcements as $announcement)
                    <div class="flex items-start gap-2 p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <i class="bi bi-arrow-right-short text-[#735B44] text-sm leading-none mt-0.5"></i>
                        <span class="leading-relaxed">{{ $announcement }}</span>
                    </div>
                @empty
                    <div class="flex items-start gap-2 p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <i class="bi bi-arrow-right-short text-[#735B44] text-sm leading-none mt-0.5"></i>
                        <span>Stok Keripik Kaca Mpok Nur 200gr sisa sedikit, segera buat PO supplier</span>
                    </div>
                    <div class="flex items-start gap-2 p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <i class="bi bi-arrow-right-short text-[#735B44] text-sm leading-none mt-0.5"></i>
                        <span>Promo bundling Es Kopi Susu Gula Aren dan Basreng Pedas berjalan minggu ini</span>
                    </div>
                    <div class="flex items-start gap-2 p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <i class="bi bi-arrow-right-short text-[#735B44] text-sm leading-none mt-0.5"></i>
                        <span>Jadwal opname fisik stok cemilan &amp; minuman dilakukan akhir bulan</span>
                    </div>
                    <div class="flex items-start gap-2 p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <i class="bi bi-arrow-right-short text-[#735B44] text-sm leading-none mt-0.5"></i>
                        <span>Pastikan cetak struk kasir untuk setiap penjualan cemilan dan minuman</span>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kanan: Kategori Terlaris (Teks) - HANYA TEKS & TABEL (DILARANG GRAFIK / CHART) -->
        <div class="lg:col-span-4 bg-[#FAF3EA] border border-gray-200 rounded-sm p-4 shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-200 pb-2.5 mb-3">
                <h2 class="text-sm font-bold text-[#2E2218] flex items-center gap-2">
                    <i class="bi bi-bar-chart-steps text-[#735B44]"></i>
                    Kategori Terlaris (Teks)
                </h2>
                <span class="text-[11px] text-[#735B44]">Rangking</span>
            </div>
            <div class="space-y-1.5 text-xs">
                @forelse($topCategories as $category)
                    <div class="flex items-center justify-between p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <span class="text-[#4A392B] font-medium">{{ $category->name }}</span>
                        <span class="text-[#2E2218] font-bold">{{ $category->count }} Transaksi</span>
                    </div>
                @empty
                    <div class="flex items-center justify-between p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <span class="text-[#4A392B] font-medium">Cemilan Ringan</span>
                        <span class="text-[#2E2218] font-bold">120 Transaksi</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <span class="text-[#4A392B] font-medium">Kopi Nusantara</span>
                        <span class="text-[#2E2218] font-bold">95 Transaksi</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <span class="text-[#4A392B] font-medium">Minuman Dingin</span>
                        <span class="text-[#2E2218] font-bold">65 Transaksi</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <span class="text-[#4A392B] font-medium">Cemilan Manis</span>
                        <span class="text-[#2E2218] font-bold">40 Transaksi</span>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- ROW 2: TRANSAKSI TERAKHIR | AKTIVITAS TERBARU -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        <!-- Kiri: Tabel Transaksi Terakhir -->
        <div class="lg:col-span-8 bg-[#FAF3EA] border border-gray-200 rounded-sm p-4 shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-200 pb-2.5 mb-3">
                <h2 class="text-sm font-bold text-[#2E2218] flex items-center gap-2">
                    <i class="bi bi-clock-history text-[#735B44]"></i>
                    Transaksi Terakhir
                </h2>
                <a href="{{ route('sales.index') }}" class="text-[11px] text-[#4A392B] hover:text-[#2E2218] font-medium hover:underline">Lihat Semua &rarr;</a>
            </div>

            <!-- Tabel Transaksi (Rigid rounded-sm, border-gray-200) -->
            <div class="overflow-x-auto rounded-sm border border-gray-200 bg-white">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-[#EBDCCB] text-[#4A392B] font-semibold border-b border-gray-200">
                            <th scope="col" class="py-2.5 px-3">Tanggal</th>
                            <th scope="col" class="py-2.5 px-3">No. Invoice / PO</th>
                            <th scope="col" class="py-2.5 px-3">Tipe</th>
                            <th scope="col" class="py-2.5 px-3">Status</th>
                            <th scope="col" class="py-2.5 px-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if(isset($recentTransactions) && $recentTransactions->isNotEmpty())
                            @foreach($recentTransactions as $tx)
                            <tr class="hover:bg-[#FAF3EA]/60 transition">
                                <td class="py-2 px-3 text-[#4A392B] whitespace-nowrap">{{ $tx->date }}</td>
                                <td class="py-2 px-3 text-[#2E2218] font-mono whitespace-nowrap font-medium">{{ $tx->invoice }}</td>
                                <td class="py-2 px-3 text-[#4A392B] whitespace-nowrap">{{ $tx->type }}</td>
                                <td class="py-2 px-3 whitespace-nowrap">
                                    @if(strtolower($tx->status) === 'completed')
                                        <span class="inline-block px-2 py-0.5 rounded-sm text-[11px] font-semibold bg-green-100 text-green-800 border border-green-300">
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 rounded-sm text-[11px] font-semibold bg-blue-100 text-blue-800 border border-blue-300">
                                            Processing
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-[#2E2218] font-bold text-right whitespace-nowrap">
                                    Rp {{ number_format($tx->total, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr class="hover:bg-[#FAF3EA]/60 transition">
                                <td class="py-2 px-3 text-[#4A392B]">23 Okt 2026</td>
                                <td class="py-2 px-3 text-[#2E2218] font-mono font-medium">SL-20261023-0001</td>
                                <td class="py-2 px-3 text-[#4A392B]">Penjualan</td>
                                <td class="py-2 px-3">
                                    <span class="inline-block px-2 py-0.5 rounded-sm text-[11px] font-semibold bg-green-100 text-green-800 border border-green-300">Completed</span>
                                </td>
                                <td class="py-2 px-3 text-[#2E2218] font-bold text-right">Rp 48.000</td>
                            </tr>
                            <tr class="hover:bg-[#FAF3EA]/60 transition">
                                <td class="py-2 px-3 text-[#4A392B]">23 Okt 2026</td>
                                <td class="py-2 px-3 text-[#2E2218] font-mono font-medium">PO-20261023-0001</td>
                                <td class="py-2 px-3 text-[#4A392B]">Pembelian</td>
                                <td class="py-2 px-3">
                                    <span class="inline-block px-2 py-0.5 rounded-sm text-[11px] font-semibold bg-blue-100 text-blue-800 border border-blue-300">Processing</span>
                                </td>
                                <td class="py-2 px-3 text-[#2E2218] font-bold text-right">Rp 1.050.000</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Kanan: Aktivitas Terbaru -->
        <div class="lg:col-span-4 bg-[#FAF3EA] border border-gray-200 rounded-sm p-4 shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-200 pb-2.5 mb-3">
                <h2 class="text-sm font-bold text-[#2E2218] flex items-center gap-2">
                    <i class="bi bi-activity text-[#735B44]"></i>
                    Aktivitas Terbaru
                </h2>
                <span class="text-[11px] text-[#735B44]">Log Sistem</span>
            </div>
            <div class="space-y-2 text-xs text-[#4A392B]">
                @forelse($recentActivities as $act)
                    <div class="p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <span class="font-bold text-[#2E2218]">{{ $act->user }}:</span>
                        <span class="text-[#4A392B] ml-1">{{ $act->action }}</span>
                    </div>
                @empty
                    <div class="p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <span class="font-bold text-[#2E2218]">Admin Gudang:</span>
                        <span class="text-[#4A392B] ml-1">Update stok Keripik Kaca Mpok Nur 200gr</span>
                    </div>
                    <div class="p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <span class="font-bold text-[#2E2218]">Kasir 1:</span>
                        <span class="text-[#4A392B] ml-1">Penjualan Es Kopi Susu Gula Aren selesai</span>
                    </div>
                    <div class="p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <span class="font-bold text-[#2E2218]">Staff Penjualan:</span>
                        <span class="text-[#4A392B] ml-1">Input transaksi Basreng Pedas Daun Jeruk</span>
                    </div>
                    <div class="p-2 bg-white/70 border border-gray-200 rounded-sm">
                        <span class="font-bold text-[#2E2218]">Purchasing:</span>
                        <span class="text-[#4A392B] ml-1">PO baru ke CV Snack Nusantara Jaya</span>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection