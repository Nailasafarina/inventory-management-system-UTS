@extends('layouts.app')

@section('title', auth()->user()->isAdmin() ? 'Dashboard Administrator' : 'Dashboard Staff')

@section('content')
<!-- Header: Judul & Tombol Aksi Cepat -->
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h1 class="h4 fw-bold text-dark mb-0" style="letter-spacing: -0.3px;">Dashboard</h1>
            @if(auth()->user()->isAdmin())
                <span class="badge bg-dark text-white rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.7rem;">
                    Owner / Administrator
                </span>
            @else
                <span class="badge bg-secondary text-white rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.7rem;">
                    Staff Operasional
                </span>
            @endif
        </div>
        <p class="text-muted mb-0" style="font-size: 0.82rem;">
            @if(auth()->user()->isAdmin())
                Ringkasan data inventaris produk, valuasi aset, dan pencatatan transaksi terkini.
            @else
                Pantau ketersediaan stok, transaksi barang masuk/keluar, dan peringatan restock barang.
            @endif
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('purchases.create') }}" class="btn btn-dark d-flex align-items-center gap-1.5 shadow-sm py-1.5 px-3" style="font-size: 0.82rem;">
            <i class="bi bi-box-arrow-in-down"></i>
            <span>Stok Masuk (PO)</span>
        </a>
        <a href="{{ route('sales.create') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1.5 shadow-sm py-1.5 px-3 bg-white" style="font-size: 0.82rem;">
            <i class="bi bi-cart-plus"></i>
            <span>Penjualan Kasir</span>
        </a>
    </div>
</div>

<!-- 4 Kotak Ringkasan Metrik -->
<div class="row g-2.5 g-xl-3 mb-3">
    @if(auth()->user()->isAdmin())
        <!-- Card 1: Total Produk -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-custom p-3 h-100 border">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="overflow-hidden pe-2">
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                            TOTAL PRODUK
                        </span>
                        <div class="fw-bold text-dark text-truncate mb-0" style="font-size: 1.45rem; line-height: 1.2;">
                            {{ $totalProducts }}
                        </div>
                        <span class="text-muted text-truncate d-block" style="font-size: 0.74rem;">SKU aktif terdaftar</span>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-primary bg-primary-subtle flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-boxes fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Valuasi Aset -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-custom p-3 h-100 border">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="overflow-hidden pe-2">
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                            VALUASI ASET STOK
                        </span>
                        <div class="fw-bold text-success text-truncate mb-0" style="font-size: 1.3rem; line-height: 1.2;" title="Rp {{ number_format($assetValuation, 0, ',', '.') }}">
                            Rp {{ number_format($assetValuation, 0, ',', '.') }}
                        </div>
                        <span class="text-muted text-truncate d-block" style="font-size: 0.74rem;">Nilai modal stok fisik</span>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success-subtle flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-cash-stack fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Penjualan Hari Ini -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-custom p-3 h-100 border">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="overflow-hidden pe-2">
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                            PENJUALAN HARI INI
                        </span>
                        <div class="fw-bold text-primary text-truncate mb-0" style="font-size: 1.3rem; line-height: 1.2;" title="Rp {{ number_format($todaySalesTotal, 0, ',', '.') }}">
                            Rp {{ number_format($todaySalesTotal, 0, ',', '.') }}
                        </div>
                        <span class="text-muted text-truncate d-block" style="font-size: 0.74rem;" title="Bulan Ini: Rp {{ number_format($thisMonthSalesTotal, 0, ',', '.') }}">
                            Bulan ini: <strong>Rp {{ number_format($thisMonthSalesTotal, 0, ',', '.') }}</strong>
                        </span>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-info bg-info-subtle flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-graph-up-arrow fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Stok Menipis -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-custom p-3 h-100 border">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="overflow-hidden pe-2">
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                            STOK MENIPIS
                        </span>
                        <div class="fw-bold text-danger mb-0" style="font-size: 1.45rem; line-height: 1.2;">
                            {{ $criticalStockCount }}
                        </div>
                        <span class="text-danger fw-semibold text-truncate d-block" style="font-size: 0.74rem;">&le; Batas minimum stok</span>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-danger bg-danger-subtle flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-exclamation-triangle fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- Card 1: Total Produk (Staff) -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-custom p-3 h-100 border">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="overflow-hidden pe-2">
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                            TOTAL PRODUK
                        </span>
                        <div class="fw-bold text-dark text-truncate mb-0" style="font-size: 1.45rem; line-height: 1.2;">
                            {{ $totalProducts }}
                        </div>
                        <span class="text-muted text-truncate d-block" style="font-size: 0.74rem;">Katalog SKU aktif</span>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-primary bg-primary-subtle flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-boxes fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Stok Perlu Restock (Staff) -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-custom p-3 h-100 border">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="overflow-hidden pe-2">
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                            PERLU RESTOCK
                        </span>
                        <div class="fw-bold text-danger mb-0" style="font-size: 1.45rem; line-height: 1.2;">
                            {{ $criticalStockCount }}
                        </div>
                        <span class="text-danger fw-semibold text-truncate d-block" style="font-size: 0.74rem;">Stok di bawah minimum</span>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-danger bg-danger-subtle flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-exclamation-triangle fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Barang Masuk (Staff) -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-custom p-3 h-100 border">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="overflow-hidden pe-2">
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                            BARANG MASUK
                        </span>
                        <div class="fw-bold text-success text-truncate mb-0" style="font-size: 1.45rem; line-height: 1.2;">
                            +{{ number_format($mutationIn) }}
                        </div>
                        <span class="text-muted text-truncate d-block" style="font-size: 0.74rem;">Unit dari pembelian supplier</span>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success-subtle flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-box-arrow-in-down fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Barang Keluar (Staff) -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-custom p-3 h-100 border">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="overflow-hidden pe-2">
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                            BARANG KELUAR
                        </span>
                        <div class="fw-bold text-secondary text-truncate mb-0" style="font-size: 1.45rem; line-height: 1.2;">
                            -{{ number_format($mutationOut) }}
                        </div>
                        <span class="text-muted text-truncate d-block" style="font-size: 0.74rem;">Unit terjual lewat kasir</span>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-secondary bg-secondary-subtle flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-cart-check fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- 2 Bagian Utama Bawah: Tabel Stok Menipis & Tabel Mutasi -->
<div class="row g-3">
    <!-- Tabel Kiri: Stok Menipis (<= Min Stok) -->
    <div class="col-12 col-lg-6">
        <div class="card card-custom h-100 border overflow-hidden">
            <div class="card-header bg-white py-2.5 px-3 d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-circle text-danger"></i>
                    <h5 class="card-title mb-0 fw-bold" style="font-size: 0.88rem;">
                        Peringatan Stok Menipis
                    </h5>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-semibold" style="font-size: 0.68rem;">
                        {{ $lowStockProducts->count() }} Perlu Restock
                    </span>
                </div>
                <a href="{{ route('products.index', ['stock_status' => 'low']) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-0.5" style="font-size: 0.72rem; font-weight: 500;">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive custom-dashboard-scroll">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="table-custom-header sticky-top">
                            <tr>
                                <th>Produk & SKU</th>
                                <th>Kategori</th>
                                <th class="text-center">Sisa / Min</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $lowProd)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark text-truncate" style="max-width: 200px; font-size: 0.82rem;" title="{{ $lowProd->name }}">
                                        {{ $lowProd->name }}
                                    </div>
                                    <code class="text-muted" style="font-size: 0.72rem;">{{ $lowProd->sku }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-0.5" style="font-size: 0.72rem;">
                                        {{ $lowProd->category->name ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="text-center whitespace-nowrap">
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-bold px-2 py-0.5" style="font-size: 0.76rem;">
                                        {{ $lowProd->stock }}
                                    </span>
                                    <span class="text-muted" style="font-size: 0.72rem;">/ min {{ $lowProd->minimum_stock }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-outline-primary py-0.5 px-2 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                        <i class="bi bi-plus"></i> Restock
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted small">
                                    <i class="bi bi-check2-circle text-success fs-3 d-block mb-1"></i>
                                    Semua stok produk saat ini dalam batas aman.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Kanan: Mutasi Transaksi Terkini -->
    <div class="col-12 col-lg-6">
        <div class="card card-custom h-100 border overflow-hidden">
            <div class="card-header bg-white py-2.5 px-3 d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-muted"></i>
                    <h5 class="card-title mb-0 fw-bold" style="font-size: 0.88rem;">
                        Mutasi Transaksi Terakhir
                    </h5>
                </div>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-0.5" style="font-size: 0.72rem; font-weight: 500;">
                    Riwayat Lengkap
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive custom-dashboard-scroll">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="table-custom-header sticky-top">
                            <tr>
                                <th>Waktu</th>
                                <th class="text-center">Tipe</th>
                                <th>Barang</th>
                                <th class="text-center">Jumlah</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestMutations as $mutation)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark" style="font-size: 0.78rem;">
                                        {{ \Carbon\Carbon::parse($mutation->time)->format('d/m/Y') }}
                                    </div>
                                    <small class="text-muted" style="font-size: 0.7rem;">
                                        {{ \Carbon\Carbon::parse($mutation->time)->format('H:i') }} WIB
                                    </small>
                                </td>
                                <td class="text-center">
                                    @if($mutation->type === 'OUT')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5 fw-semibold" style="font-size: 0.68rem;">
                                            Keluar
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 fw-semibold" style="font-size: 0.68rem;">
                                            Masuk
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark text-truncate" style="max-width: 170px; font-size: 0.82rem;" title="{{ $mutation->goods }}">
                                        {{ $mutation->goods }}
                                    </div>
                                    <code class="text-muted" style="font-size: 0.7rem;">{{ $mutation->sku }}</code>
                                </td>
                                <td class="text-center fw-bold" style="font-size: 0.88rem; color: {{ $mutation->type === 'OUT' ? '#dc2626' : '#16a34a' }};">
                                    {{ $mutation->qty }}
                                </td>
                                <td>
                                    <span class="text-muted text-truncate d-inline-block" style="max-width: 85px; font-size: 0.76rem;" title="{{ $mutation->officer }}">
                                        {{ $mutation->officer }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">
                                    <i class="bi bi-clock fs-3 d-block mb-1"></i>
                                    Belum ada transaksi mutasi fisik tercatat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
