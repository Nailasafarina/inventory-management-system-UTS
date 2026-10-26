@extends('layouts.app')

@section('title', 'Pemasok (Suppliers)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="h4 fw-bold mb-1 text-dark">Daftar Pemasok (Suppliers)</h2>
        <p class="text-muted small mb-0">Kelola informasi mitra pemasok untuk transaksi pembelian stok</p>
    </div>
    @can('admin-only')
    <div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-terracotta">
            <i class="bi bi-plus-circle me-1"></i> Tambah Pemasok
        </a>
    </div>
    @endcan
</div>

<div class="card card-custom mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('suppliers.index') }}" class="row g-2">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" name="search" value="{{ request('search') }}" placeholder="Cari nama, telepon, atau email pemasok...">
                    <button class="btn btn-terracotta" type="submit">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
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
                        <th style="width: 60px;" class="ps-3">No</th>
                        <th>Nama Pemasok</th>
                        <th>No. Telepon</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th class="text-center">Riwayat Pembelian</th>
                        <th class="text-center pe-3" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}</td>
                        <td class="fw-bold text-dark">{{ $supplier->name }}</td>
                        <td><a href="tel:{{ $supplier->phone }}" class="text-decoration-none" style="color: #b77747;">{{ $supplier->phone }}</a></td>
                        <td>{{ $supplier->email ?? '-' }}</td>
                        <td class="text-muted small">{{ $supplier->address ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge rounded-pill" style="background-color: #e6f4ea; color: #21774b; font-weight: 500;">
                                {{ $supplier->purchases_count }} Transaksi
                            </span>
                        </td>
                        <td class="text-center pe-3">
                            @can('admin-only')
                            <div class="d-inline-flex gap-1 text-nowrap">
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemasok ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-truck fs-2 d-block mb-2 text-muted"></i>
                            Belum ada data pemasok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($suppliers->hasPages())
    <div class="card-footer bg-transparent py-3 px-4 border-top">
        {{ $suppliers->links() }}
    </div>
    @endif
</div>
@endsection
