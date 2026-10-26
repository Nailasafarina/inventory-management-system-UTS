@extends('layouts.tailwind')

@section('title', 'Nota Belanja - ' . $sale->sale_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header Aksi & Navigasi -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-[#2E2218] tracking-tight">Faktur Pembelian</h1>
            <p class="text-xs text-[#735B44] mt-0.5">
                No. Transaksi: <code class="font-mono font-bold text-[#4A392B] bg-[#EBDCCB] px-1.5 py-0.5 rounded-sm">{{ $sale->sale_number }}</code>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('customer.dashboard') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-sm bg-white hover:bg-[#FAF4EC] text-[#4A392B] border border-gray-300 transition shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Katalog</span>
            </a>
            <button onclick="window.print()" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-sm text-white bg-[#4A392B] hover:bg-[#382B20] border border-gray-300 transition shadow-sm cursor-pointer">
                <i class="bi bi-printer"></i>
                <span>Cetak Nota</span>
            </button>
        </div>
    </div>

    <!-- Card Faktur (Pastel Coksu - Rigid & Functional) -->
    <div class="bg-[#FAF3EA] rounded-sm border border-gray-200 shadow-sm overflow-hidden p-5 sm:p-6">
        <!-- Brand Header & Status Transaksi -->
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-200 flex-wrap gap-3">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-sm bg-[#DEC8B0] border border-gray-300 flex items-center justify-center text-[#4A392B] text-base shadow-sm">
                    <i class="bi bi-shop"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-[#2E2218] leading-tight">InventPro Cemilan &amp; Minuman</h2>
                    <p class="text-xs text-[#735B44]">Bukti Pembelian Kasir Resmi</p>
                </div>
            </div>
            <div>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-sm text-xs font-semibold bg-green-100 text-green-800 border border-green-300">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Completed</span>
                </span>
            </div>
        </div>

        <!-- Info Penerima & Info Transaksi -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
            <div class="p-3 rounded-sm bg-white border border-gray-200">
                <span class="text-[10px] font-semibold text-[#735B44] uppercase tracking-wider block">Pelanggan:</span>
                <h3 class="text-sm font-bold text-[#2E2218] mt-0.5">{{ auth()->user()->name }}</h3>
                <p class="text-xs text-[#735B44]">{{ auth()->user()->email }}</p>
            </div>
            <div class="p-3 rounded-sm bg-white border border-gray-200 sm:text-right">
                <span class="text-[10px] font-semibold text-[#735B44] uppercase tracking-wider block">Detail Faktur:</span>
                <div class="text-xs font-bold text-[#2E2218] mt-0.5">
                    No. Faktur: <code class="font-mono text-[#4A392B]">{{ $sale->sale_number }}</code>
                </div>
                <div class="text-xs text-[#735B44] mt-0.5">Waktu: {{ $sale->sold_at->format('d/m/Y, H:i') }} WIB</div>
                @if($sale->notes)
                <div class="text-xs text-[#5C4A3E] mt-1 italic">Catatan: "{{ $sale->notes }}"</div>
                @endif
            </div>
        </div>

        <!-- Tabel Item Pembelian (Rigid) -->
        <div class="overflow-x-auto rounded-sm border border-gray-200 mb-4 bg-white">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#EBDCCB] text-[#4A392B] text-[11px] font-semibold border-b border-gray-200">
                        <th class="px-3 py-2 w-10 text-center">No</th>
                        <th class="px-3 py-2">Produk</th>
                        <th class="px-3 py-2 text-center">Jumlah</th>
                        <th class="px-3 py-2 text-right">Harga Satuan</th>
                        <th class="px-3 py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($sale->items as $item)
                    <tr class="hover:bg-[#FAF7F2] transition">
                        <td class="px-3 py-2 text-center text-[#735B44] font-medium">{{ $loop->iteration }}</td>
                        <td class="px-3 py-2">
                            <div class="font-semibold text-[#2E2218]">{{ $item->product->name ?? 'Produk' }}</div>
                            <div class="text-[10px] text-[#735B44] font-mono">SKU: {{ $item->product->sku ?? '-' }}</div>
                        </td>
                        <td class="px-3 py-2 text-center font-medium text-[#4A392B]">{{ $item->quantity }} unit</td>
                        <td class="px-3 py-2 text-right text-[#735B44]">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 text-right font-bold text-[#2E2218]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-[#FAF7F2] border-t border-gray-300">
                        <td colspan="4" class="px-3 py-2 text-right font-bold text-[#4A392B]">Total Pembayaran:</td>
                        <td class="px-3 py-2 text-right text-sm font-bold text-[#2E2218]">
                            Rp {{ number_format($sale->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-center text-xs text-[#735B44]">
            Terima kasih telah berbelanja cemilan &amp; minuman di <strong>InventPro</strong>. Harap simpan bukti pembayaran ini untuk verifikasi.
        </div>
    </div>
</div>
@endsection