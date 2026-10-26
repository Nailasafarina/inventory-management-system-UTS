@extends('layouts.app')

@section('title', 'Detail Pembelian - ' . $purchase->purchase_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="h4 fw-bold mb-1 text-dark">Detail Transaksi Pembelian</h2>
        <p class="text-muted small mb-0">Nomor Faktur: <code style="color: #b77747;">{{ $purchase->purchase_number }}</code></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn btn-outline-dark">
            <i class="bi bi-printer me-1"></i> Cetak PO
        </button>
        @can('cancel-transaction')
            @if($purchase->status === 'completed')
            <form action="{{ route('purchases.cancel', $purchase) }}" method="POST" class="d-inline" onsubmit="return confirm('PERINGATAN: Membatalkan pembelian ini akan mengurangi kembali stok produk di database. Apakah Anda yakin?');">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-x-circle me-1"></i> Batalkan Transaksi
                </button>
            </form>
            @endif
        @endcan
    </div>
</div>

<div class="card card-custom mb-4">
    <div class="card-body p-4">
        <!-- Header Info -->
        <div class="row g-4 mb-4 pb-3 border-bottom">
            <div class="col-12 col-md-6">
                <span class="text-muted small text-uppercase fw-semibold">Informasi Pemasok:</span>
                <h5 class="fw-bold text-dark mt-1 mb-1">{{ $purchase->supplier->name ?? '-' }}</h5>
                <p class="text-muted small mb-0">
                    <i class="bi bi-telephone me-1 text-muted"></i> {{ $purchase->supplier->phone ?? '-' }}<br>
                    <i class="bi bi-envelope me-1 text-muted"></i> {{ $purchase->supplier->email ?? '-' }}<br>
                    <i class="bi bi-geo-alt me-1 text-muted"></i> {{ $purchase->supplier->address ?? '-' }}
                </p>
            </div>
            <div class="col-12 col-md-6 text-md-end">
                <span class="text-muted small text-uppercase fw-semibold">Data Transaksi:</span>
                <div class="mt-1">
                    <span class="fw-bold text-dark">Status: </span>
                    @if($purchase->status === 'completed')
                        <span class="badge rounded-pill" style="background-color: #e6f4ea; color: #21774b; font-weight: 500;">
                            <i class="bi bi-check-circle me-1"></i>Selesai (Stok Masuk)
                        </span>
                    @else
                        <span class="badge rounded-pill bg-secondary" style="font-weight: 500;">
                            <i class="bi bi-x-circle me-1"></i>Dibatalkan
                        </span>
                    @endif
                </div>
                <div class="text-muted small mt-1">
                    Tanggal: <strong>{{ $purchase->purchased_at->format('d F Y, H:i') }}</strong>
                </div>
                <div class="text-muted small">
                    Diterima Oleh: <strong>{{ $purchase->user->name ?? '-' }} ({{ ucfirst($purchase->user->role ?? '') }})</strong>
                </div>
                @if($purchase->notes)
                <div class="text-muted small mt-1">
                    Catatan: <em>{{ $purchase->notes }}</em>
                </div>
                @endif
            </div>
        </div>

        <!-- Detail Item Barang -->
        <h6 class="fw-bold text-dark mb-3">Item Barang Masuk</h6>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-custom-header">
                    <tr>
                        <th style="width: 50px;" class="ps-3">No</th>
                        <th>Kode SKU</th>
                        <th>Nama Produk</th>
                        <th class="text-center" style="width: 140px;">Kuantitas</th>
                        <th class="text-end" style="width: 180px;">Harga Beli Satuan</th>
                        <th class="text-end pe-3" style="width: 200px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->items as $item)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration }}</td>
                        <td><code>{{ $item->product->sku ?? '-' }}</code></td>
                        <td class="fw-semibold">
                            @if($item->product)
                                <a href="{{ route('products.show', $item->product_id) }}" class="text-decoration-none" style="color: #23150e;">
                                    {{ $item->product->name }}
                                </a>
                            @else
                                <span class="text-muted">Produk Dihapus</span>
                            @endif
                        </td>
                        <td class="text-center fw-bold" style="color: #21774b;">+{{ $item->quantity }}</td>
                        <td class="text-end">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                        <td class="text-end pe-3 fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <th colspan="5" class="text-end fs-6">Total Pembelian:</th>
                        <th class="text-end pe-3 fs-5 fw-bold" style="color: #21774b;">
                            Rp {{ number_format($purchase->total, 0, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
