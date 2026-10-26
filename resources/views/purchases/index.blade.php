@extends('layouts.app')

@section('title', 'Transaksi Pembelian (Stock-In)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="h3 fw-bold mb-0 text-dark">Transaksi Pembelian (Stock-In)</h2>
        <p class="text-muted mb-0">Riwayat pengadaan stok masuk dari pemasok ke inventaris gudang</p>
    </div>
    <div>
        <a href="{{ route('purchases.create') }}" class="btn btn-terracotta">
            <i class="bi bi-box-arrow-in-down me-1"></i> Buat Pembelian (Stock-In)
        </a>
    </div>
</div>

<!-- Search & Date Filter Card (Slide 18) -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('purchases.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label for="search" class="form-label small fw-semibold text-muted mb-1">Cari No. PO / Pemasok</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Contoh: PO-2024 atau Sumber...">
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
                <button type="submit" class="btn btn-terracotta flex-fill">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary" title="Reset">
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
                        <th>No. Pembelian</th>
                        <th>Tanggal Masuk</th>
                        <th>Pemasok</th>
                        <th>Staf Penerima</th>
                        <th class="text-center">Jml Item</th>
                        <th class="text-end">Total Biaya</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                    <tr>
                        <td>
                            <a href="{{ route('purchases.show', $purchase) }}" class="fw-bold text-decoration-none">
                                <code>{{ $purchase->purchase_number }}</code>
                            </a>
                        </td>
                        <td>{{ $purchase->purchased_at->format('d/m/Y H:i') }}</td>
                        <td class="fw-semibold">{{ $purchase->supplier->name ?? '-' }}</td>
                        <td><span class="small text-muted">{{ $purchase->user->name ?? '-' }}</span></td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $purchase->items->count() }} Produk</span>
                        </td>
                        <td class="text-end fw-bold text-success">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($purchase->status === 'completed')
                                <span class="badge bg-success">Selesai (Stok Masuk)</span>
                            @else
                                <span class="badge bg-secondary">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                            Tidak ada riwayat transaksi pembelian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($purchases->hasPages())
    <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
        <span class="small text-muted">
            Menampilkan {{ $purchases->firstItem() ?? 0 }} - {{ $purchases->lastItem() ?? 0 }} dari {{ $purchases->total() }} transaksi
        </span>
        {{ $purchases->links() }}
    </div>
    @endif
</div>
@endsection
