@extends('layouts.app')

@section('title', 'Transaksi Penjualan (Stock-Out)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="h4 fw-bold mb-1 text-dark">Transaksi Penjualan (Stock-Out)</h2>
        <p class="text-muted small mb-0">Riwayat penjualan produk ke pelanggan dan pengurangan stok otomatis dengan validasi anti-overselling</p>
    </div>
    <div>
        <a href="{{ route('sales.create') }}" class="btn btn-terracotta">
            <i class="bi bi-cart-plus me-1"></i> Buat Penjualan Baru
        </a>
    </div>
</div>

<!-- Search & Date Filter Card -->
<div class="card card-custom mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('sales.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label for="search" class="form-label small fw-semibold text-muted mb-1">Cari No. Faktur / Invoice</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" id="search" name="search" value="{{ request('search') }}" placeholder="Contoh: INV-2024...">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <label for="status" class="form-label small fw-semibold text-muted mb-1">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label for="start_date" class="form-label small fw-semibold text-muted mb-1">Dari Tanggal</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-12 col-md-2">
                <label for="end_date" class="form-label small fw-semibold text-muted mb-1">Sampai Tanggal</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-terracotta flex-fill">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-custom-header">
                    <tr>
                        <th class="ps-4">No. Invoice</th>
                        <th>Tanggal Penjualan</th>
                        <th>Kasir / Staf</th>
                        <th class="text-center">Jml Item</th>
                        <th class="text-end">Total Penjualan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('sales.show', $sale) }}" class="fw-bold text-decoration-none" style="color: #b77747;">
                                <code>{{ $sale->sale_number }}</code>
                            </a>
                        </td>
                        <td>{{ $sale->sold_at->format('d/m/Y H:i') }}</td>
                        <td><span class="small text-muted">{{ $sale->user->name ?? '-' }}</span></td>
                        <td class="text-center">
                            <span class="badge rounded-pill bg-light text-dark border">{{ $sale->items->count() }} Produk</span>
                        </td>
                        <td class="text-end fw-bold" style="color: #21774b;">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($sale->status === 'completed')
                                <span class="badge rounded-pill" style="background-color: #e6f4ea; color: #21774b; font-weight: 500;">
                                    <i class="bi bi-check-circle me-1"></i>Selesai
                                </span>
                            @else
                                <span class="badge rounded-pill bg-secondary" style="font-weight: 500;">
                                    <i class="bi bi-x-circle me-1"></i>Dibatalkan
                                </span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-terracotta" title="Detail">
                                <i class="bi bi-eye me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                            Tidak ada riwayat transaksi penjualan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($sales->hasPages())
    <div class="card-footer bg-transparent py-3 px-4 d-flex justify-content-between align-items-center border-top">
        <span class="small text-muted">
            Menampilkan {{ $sales->firstItem() ?? 0 }} - {{ $sales->lastItem() ?? 0 }} dari {{ $sales->total() }} transaksi
        </span>
        {{ $sales->links() }}
    </div>
    @endif
</div>
@endsection
