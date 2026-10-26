@extends('layouts.app')

@section('title', 'Edit Kategori - ' . $category->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="card card-custom">
            <div class="card-header bg-transparent py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="card-title mb-0 fw-bold text-dark">
                    <i class="bi bi-pencil-square me-2" style="color: #b77747;"></i>Edit Kategori
                </h5>
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-semibold">Kuantitas / Jumlah Stok <span class="text-danger">*</span></label>
                        <input type="number" min="0" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $category->quantity ?? 0) }}" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kuantitas / total jumlah unit fisik untuk kategori ini.</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($category->products && $category->products->count() > 0)
                    <div class="mb-3 pt-3 border-top">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center justify-content-between mb-2">
                            <span><i class="bi bi-boxes me-1" style="color: #b77747;"></i> Edit Kuantitas Produk Terkait</span>
                            <span class="badge rounded-pill text-dark border bg-light">{{ $category->products->count() }} Produk</span>
                        </label>
                        <div class="table-responsive border rounded">
                            <table class="table table-sm table-hover align-middle mb-0 text-start" style="font-size: 0.85rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-2">Nama Produk &amp; SKU</th>
                                        <th style="width: 140px;" class="pe-2">Kuantitas Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->products as $prod)
                                    <tr>
                                        <td class="ps-2">
                                            <div class="fw-semibold text-dark">{{ $prod->name }}</div>
                                            <small class="text-muted font-monospace">{{ $prod->sku }}</small>
                                        </td>
                                        <td class="pe-2">
                                            <input type="number" min="0" name="product_stocks[{{ $prod->id }}]" value="{{ old('product_stocks.' . $prod->id, $prod->stock) }}" class="form-control form-control-sm" title="Edit Kuantitas Produk">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted mt-1 d-block">Anda juga dapat langsung mengedit kuantitas stok masing-masing produk di atas.</small>
                    </div>
                    @endif
                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-terracotta px-4">
                            <i class="bi bi-save me-1"></i> Perbarui Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
