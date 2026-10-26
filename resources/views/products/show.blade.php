@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="h4 fw-bold mb-1 text-dark">{{ $product->name }}</h2>
        <p class="text-muted small mb-0">Kode SKU: <code style="color: #b77747;">{{ $product->sku }}</code> | Kategori: <strong>{{ $product->category->name ?? '-' }}</strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        @can('admin-only')
        <a href="{{ route('products.edit', $product) }}" class="btn btn-terracotta">
            <i class="bi bi-pencil me-1"></i> Edit Data
        </a>
        @endcan
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Card Info Utama -->
    <div class="col-12 col-md-4">
        <div class="card card-custom h-100">
            <div class="card-header bg-transparent py-3 px-4 fw-bold text-dark border-bottom">Informasi Stok & Status</div>
            <div class="card-body p-4">
                @if($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image))
                <div class="text-center mb-3">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded border shadow-sm" style="max-height: 180px; width: 100%; object-fit: cover;">
                </div>
                @endif
                <div class="text-center py-2">
                    <span class="text-muted d-block small text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Sisa Stok Fisik</span>
                    <h1 class="display-4 fw-bold mb-2 {{ $product->stock <= 0 ? 'text-danger' : ($product->stock <= $product->minimum_stock ? 'text-warning' : '') }}" style="{{ $product->stock > $product->minimum_stock ? 'color: #21774b;' : '' }}">
                        {{ $product->stock }}
                    </h1>
                    @if($product->stock <= 0)
                        <span class="badge rounded-pill bg-danger fs-6">Stok Habis</span>
                    @elseif($product->stock <= $product->minimum_stock)
                        <span class="badge rounded-pill text-dark fs-6" style="background-color: #fce8e6; color: #cf3c2e !important; border: 1px solid #fad2cf;"><i class="bi bi-exclamation-triangle-fill me-1"></i> Kritis (&le; {{ $product->minimum_stock }})</span>
                    @else
                        <span class="badge rounded-pill fs-6" style="background-color: #e6f4ea; color: #21774b; font-weight: 500;"><i class="bi bi-check-circle-fill me-1"></i> Stok Aman</span>
                    @endif
                </div>

                <ul class="list-group list-group-flush border-top">
                    <li class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                        <span class="text-muted">Batas Minimum Stok</span>
                        <span class="fw-semibold">{{ $product->minimum_stock }} unit</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                        <span class="text-muted">Harga Beli Terakhir</span>
                        <span class="fw-semibold">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                        <span class="text-muted">Harga Jual Satuan</span>
                        <span class="fw-bold" style="color: #21774b;">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                        <span class="text-muted">Potensi Margin / Unit</span>
                        <span class="fw-semibold" style="color: #21774b;">
                            Rp {{ number_format($product->selling_price - $product->purchase_price, 0, ',', '.') }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi Terkait Produk Ini -->
    <div class="col-12 col-md-8">
        <div class="card card-custom h-100">
            <div class="card-header bg-transparent py-3 px-4 border-bottom">
                <ul class="nav nav-tabs card-header-tabs" id="historyTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales-history" type="button" role="tab" style="color: #23150e;">
                            <i class="bi bi-arrow-up-circle me-1" style="color: #cf3c2e;"></i> Riwayat Penjualan ({{ $product->saleItems->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases-history" type="button" role="tab" style="color: #23150e;">
                            <i class="bi bi-arrow-down-circle me-1" style="color: #21774b;"></i> Riwayat Pembelian ({{ $product->purchaseItems->count() }})
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="tab-content" id="historyTabContent">
                    <!-- Tab Penjualan -->
                    <div class="tab-pane fade show active" id="sales-history" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-custom-header">
                                    <tr>
                                        <th class="ps-3">No. Invoice</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Jumlah Keluar</th>
                                        <th class="text-end">Harga Satuan</th>
                                        <th class="text-end pe-3">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($product->saleItems as $sItem)
                                    <tr>
                                        <td class="ps-3">
                                            <a href="{{ route('sales.show', $sItem->sale_id) }}" class="fw-bold text-decoration-none" style="color: #b77747;">
                                                {{ $sItem->sale->sale_number ?? '-' }}
                                            </a>
                                        </td>
                                        <td>{{ $sItem->sale->sold_at ? $sItem->sale->sold_at->format('d/m/Y H:i') : '-' }}</td>
                                        <td class="text-center fw-bold" style="color: #cf3c2e;">-{{ $sItem->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($sItem->unit_price, 0, ',', '.') }}</td>
                                        <td class="text-end pe-3 fw-semibold">Rp {{ number_format($sItem->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat penjualan untuk produk ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Pembelian -->
                    <div class="tab-pane fade" id="purchases-history" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-custom-header">
                                    <tr>
                                        <th class="ps-3">No. PO</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Jumlah Masuk</th>
                                        <th class="text-end">Harga Beli</th>
                                        <th class="text-end pe-3">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($product->purchaseItems as $pItem)
                                    <tr>
                                        <td class="ps-3">
                                            <a href="{{ route('purchases.show', $pItem->purchase_id) }}" class="fw-bold text-decoration-none" style="color: #b77747;">
                                                {{ $pItem->purchase->purchase_number ?? '-' }}
                                            </a>
                                        </td>
                                        <td>{{ $pItem->purchase->purchased_at ? $pItem->purchase->purchased_at->format('d/m/Y H:i') : '-' }}</td>
                                        <td class="text-center fw-bold" style="color: #21774b;">+{{ $pItem->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($pItem->unit_cost, 0, ',', '.') }}</td>
                                        <td class="text-end pe-3 fw-semibold">Rp {{ number_format($pItem->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat pembelian untuk produk ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
