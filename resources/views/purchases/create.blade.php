@extends('layouts.app')

@section('title', 'Buat Transaksi Pembelian (Stock-In)')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="card card-custom">
            <div class="card-header bg-transparent py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                <div>
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-box-arrow-in-down me-2" style="color: #b77747;"></i>Transaksi Pembelian Baru (Penambahan Stok)
                    </h5>
                    <small class="text-muted">Stok produk akan bertambah otomatis secara real-time setelah transaksi tersimpan</small>
                </div>
                <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
                    @csrf

                    <!-- Header Transaksi -->
                    <div class="row g-3 mb-4 pb-3 border-bottom">
                        <div class="col-12 col-md-6">
                            <label for="supplier_id" class="form-label fw-semibold">Pemasok / Supplier <span class="text-danger">*</span></label>
                            <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                                <option value="">-- Pilih Pemasok --</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }} ({{ $supplier->phone }})
                                </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="purchased_at" class="form-label fw-semibold">Tanggal & Waktu Pembelian <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('purchased_at') is-invalid @enderror" id="purchased_at" name="purchased_at" value="{{ old('purchased_at', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('purchased_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label fw-semibold">Catatan Transaksi (Opsional)</label>
                            <input type="text" class="form-control" id="notes" name="notes" value="{{ old('notes') }}" placeholder="Contoh: No. Surat Jalan / Pengiriman batch #1">
                        </div>
                    </div>

                    <!-- Detail Multi-Item Produk -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-list-check me-1" style="color: #b77747;"></i> Daftar Barang Masuk
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-terracotta" id="addRowBtn">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Baris Produk
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="itemsTable">
                                <thead class="table-custom-header">
                                    <tr>
                                        <th style="min-width: 250px;" class="ps-3">Produk <span class="text-danger">*</span></th>
                                        <th style="width: 140px;">Jumlah Masuk <span class="text-danger">*</span></th>
                                        <th style="width: 180px;">Harga Beli Satuan (Rp) <span class="text-danger">*</span></th>
                                        <th style="width: 180px;" class="text-end">Subtotal (Rp)</th>
                                        <th style="width: 60px;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="itemRows">
                                    <!-- Baris item awal -->
                                    <tr class="item-row">
                                        <td class="ps-3">
                                            <select name="items[0][product_id]" class="form-select product-select" required onchange="onProductChange(this)">
                                                <option value="">-- Pilih Produk --</option>
                                                @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-cost="{{ $product->purchase_price }}" data-stock="{{ $product->stock }}">
                                                    {{ $product->name }} (SKU: {{ $product->sku }} | Stok Saat Ini: {{ $product->stock }})
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" class="form-control qty-input" min="1" value="1" required oninput="calculateSubtotal(this)">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="items[0][unit_cost]" class="form-control cost-input" min="0" value="0" required oninput="calculateSubtotal(this)">
                                        </td>
                                        <td class="text-end fw-semibold subtotal-text">Rp 0</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" onclick="removeRow(this)" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th colspan="3" class="text-end fs-6">Total Estimasi Pembelian:</th>
                                        <th class="text-end fs-5 fw-bold" style="color: #21774b;" id="grandTotalText">Rp 0</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-terracotta px-4 fw-semibold" id="submitBtn">
                            <i class="bi bi-check-circle me-1"></i> Konfirmasi & Simpan Pembelian
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
        const cost = selected.getAttribute('data-cost') || 0;
        const row = select.closest('tr');
        const costInput = row.querySelector('.cost-input');
        costInput.value = cost;
        calculateSubtotal(costInput);
    }

    function calculateSubtotal(element) {
        const row = element.closest('tr');
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
        const subtotal = qty * cost;

        row.querySelector('.subtotal-text').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
            total += (qty * cost);
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
        newRow.querySelector('.cost-input').name = `items[${rowIndex}][unit_cost]`;
        newRow.querySelector('.cost-input').value = 0;
        newRow.querySelector('.subtotal-text').textContent = 'Rp 0';
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
