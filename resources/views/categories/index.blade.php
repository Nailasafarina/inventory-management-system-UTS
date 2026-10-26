@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="h4 fw-bold mb-1 text-dark">Kategori Produk</h2>
        <p class="text-muted small mb-0">Kelola pengelompokan produk dan relasi inventaris</p>
    </div>
    @can('admin-only')
    <div>
        <a href="{{ route('categories.create') }}" class="btn btn-terracotta">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </a>
    </div>
    @endcan
</div>

<div class="card card-custom mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('categories.index') }}" class="row g-2">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori...">
                    <button class="btn btn-terracotta" type="submit">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Produk Terkait</th>
                        <th class="text-center pe-3" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                        <td class="fw-bold text-dark">{{ $category->name }}</td>
                        <td class="text-muted small">{{ $category->description ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('products.index', ['category_id' => $category->id]) }}" class="badge rounded-pill text-decoration-none" style="background-color: #f6efe9; color: #b77747; border: 1px solid #e7dacd; font-weight: 500;">
                                {{ $category->products_count }} Produk
                            </a>
                            <div class="small text-muted mt-0.5" style="font-size: 0.75rem;">
                                Kuantitas: <span class="fw-semibold text-dark">{{ $category->quantity ?? 0 }}</span>
                            </div>
                        </td>
                        <td class="text-center pe-3">
                            @can('admin-only')
                            <div class="d-inline-flex gap-1 text-nowrap">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Kategori tidak dapat dihapus jika masih ada produk terkait.');">
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
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-tags fs-2 d-block mb-2 text-muted"></i>
                            Belum ada kategori yang ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categories->hasPages())
    <div class="card-footer bg-transparent py-3 px-4 border-top">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
