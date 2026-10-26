@extends('layouts.app')

@section('title', 'Buat Transaksi Penjualan (Stock-Out)')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="card card-custom">
            <div class="card-header bg-transparent py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                <div>
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-cart-plus me-2" style="color: #b77747;"></i>Transaksi Penjualan Baru (Pengurangan Stok)
                    </h5>
                    <small class="text-muted">Dilindungi transaksi atomik (DB::transaction) dengan validasi anti-overselling & lockForUpdate</small>
                </div>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('sales.store') }}" method="POST" id="salesForm">
                    @csrf

                    <!-- Header Transaksi -->
                    <div class="row g-3 mb-4 pb-3 border-bottom">
                        <div class="col-12 col-md-6">
                            <label for="sold_at" class="form-label fw-semibold">Tanggal & Waktu Penjualan <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('sold_at') is-invalid @enderror" id="sold_at" name="sold_at" value="{{ old('sold_at', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('sold_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="notes" class="form-label fw-semibold">Catatan / Keterangan Pelanggan (Opsional)</label>
                            <input type="text" class="form-control" id="notes" name="notes" value="{{ old('notes') }}" placeholder="Contoh: Pelanggan Ritel / Tunai">
                        </div>
                    </div>

                    <!-- Detail Multi-Item Produk -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="bi bi-basket me-1" style="color: #b77747;"></i> Item Barang yang Dijual
                                </h6>
                                <small class="text-muted">Pilih produk dan tentukan jumlah yang dibeli</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-terracotta" id="addRowBtn">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Item
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="itemsTable">
                                <thead class="table-custom-header">
                                    <tr>
                                        <th style="min-width: 280px;" class="ps-3">Produk & Ketersediaan Stok <span class="text-danger">*</span></th>
                                        <th style="width: 140px;">Jumlah Beli <span class="text-danger">*</span></th>
                                        <th style="width: 180px;" class="text-end">Harga Satuan (Rp)</th>
                                        <th style="width: 180px;" class="text-end">Subtotal (Rp)</th>
                                        <th style="width: 60px;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="itemRows">
                                    <!-- Baris item pertama -->
                                    <tr class="item-row">
                                        <td class="ps-3">
                                            <select name="items[0][product_id]" class="form-select product-select" required onchange="onProductChange(this)">
                                                <option value="">-- Pilih Produk --</option>
                                                @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-stock="{{ $product->stock }}">
                                                    {{ $product->name }} (SKU: {{ $product->sku }}) — Stok Tersedia: [{{ $product->stock }}]
                                                </option>
                                                @endforeach
                                            </select>
                                            <div class="stock-warning text-danger small mt-1 d-none fw-semibold">
                                                <i class="bi bi-exclamation-triangle-fill"></i> Jumlah melebihi stok yang tersedia!
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" class="form-control qty-input" min="1" value="1" required oninput="calculateSubtotal(this)">
                                        </td>
                                        <td class="text-end text-muted price-text">
                                            Rp 0
                                        </td>
                                        <td class="text-end fw-semibold text-dark subtotal-text">
                                            Rp 0
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" onclick="removeRow(this)" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th colspan="3" class="text-end fs-6">Total Pembayaran:</th>
                                        <th class="text-end fs-5 fw-bold" style="color: #21774b;" id="grandTotalText">Rp 0</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="alert alert-secondary py-2 small mb-4" style="background-color: #f6efe9; border-color: #e6dacd; color: #6e4d3b;">
                        <i class="bi bi-shield-check me-1"></i>
                        <strong>Keamanan Transaksi:</strong> Harga satuan dan ketersediaan stok diverifikasi langsung dari database server dengan mekanisme <code>Product::lockForUpdate()</code> untuk menjamin integritas data secara atomik.
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-terracotta px-4 fw-semibold" id="submitBtn">
                            <i class="bi bi-check-circle me-1"></i> Selesaikan Penjualan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let rowIndex = 1;

    function onProductChange(select) {
        const selected = select.options[select.selectedIndex];
        const price = parseFloat(selected.getAttribute('data-price')) || 0;
        const row = select.closest('tr');
        
        row.querySelector('.price-text').textContent = 'Rp ' + price.toLocaleString('id-ID');
        calculateSubtotal(select);
    }

    function calculateSubtotal(element) {
        const row = element.closest('tr');
        const select = row.querySelector('.product-select');
        const selected = select.options[select.selectedIndex];
        
        const price = parseFloat(selected ? selected.getAttribute('data-price') : 0) || 0;
        const maxStock = parseInt(selected ? selected.getAttribute('data-stock') : 0) || 0;
        const qty = parseInt(row.querySelector('.qty-input').value) || 0;

        // Validasi stok di client-side untuk kenyamanan UX
        const warningDiv = row.querySelector('.stock-warning');
        if (selected && selected.value && qty > maxStock) {
            warningDiv.classList.remove('d-none');
            warningDiv.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> Melebihi stok! Maksimum: ${maxStock} unit.`;
        } else {
            warningDiv.classList.add('d-none');
        }

        const subtotal = qty * price;
        row.querySelector('.subtotal-text').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('.product-select');
            const selected = select.options[select.selectedIndex];
            const price = parseFloat(selected ? selected.getAttribute('data-price') : 0) || 0;
            const qty = parseInt(row.querySelector('.qty-input').value) || 0;
            total += (qty * price);
        });
        document.getElementById('grandTotalText').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.getElementById('addRowBtn').addEventListener('click', function() {
        const itemRows = document.getElementById('itemRows');
        const firstRow = itemRows.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);

        // Reset values
        newRow.querySelector('.product-select').name = `items[${rowIndex}][product_id]`;
        newRow.querySelector('.product-select').selectedIndex = 0;
        newRow.querySelector('.qty-input').name = `items[${rowIndex}][quantity]`;
        newRow.querySelector('.qty-input').value = 1;
        newRow.querySelector('.price-text').textContent = 'Rp 0';
        newRow.querySelector('.subtotal-text').textContent = 'Rp 0';
        newRow.querySelector('.stock-warning').classList.add('d-none');
        newRow.querySelector('.remove-row-btn').disabled = false;

        itemRows.appendChild(newRow);
        rowIndex++;
        updateRemoveButtons();
    });

    function removeRow(btn) {
        const itemRows = document.getElementById('itemRows');
        if (itemRows.querySelectorAll('.item-row').length > 1) {
            btn.closest('tr').remove();
            updateGrandTotal();
            updateRemoveButtons();
        }
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach(r => {
            const removeBtn = r.querySelector('.remove-row-btn');
            removeBtn.disabled = (rows.length === 1);
        });
    }
</script>
@endpush
@endsection
