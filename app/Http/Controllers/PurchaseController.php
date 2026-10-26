<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'user', 'items.product'])
            ->latest('purchased_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('purchased_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('purchased_at', '<=', $request->end_date);
        }

        $purchases = $query->paginate(10)->withQueryString();

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchased_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
        ], [
            'supplier_id.required' => 'Pemasok wajib dipilih.',
            'supplier_id.exists' => 'Pemasok tidak valid.',
            'items.required' => 'Minimal satu produk harus ditambahkan.',
            'items.*.product_id.required' => 'Produk wajib dipilih.',
            'items.*.quantity.min' => 'Jumlah pembelian minimal 1.',
            'items.*.unit_cost.min' => 'Harga beli tidak boleh negatif.',
        ]);

        $purchase = DB::transaction(function () use ($request) {
            $purchaseNumber = $this->generatePurchaseNumber();

            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'purchase_number' => $purchaseNumber,
                'total' => 0,
                'status' => 'completed',
                'purchased_at' => $request->purchased_at,
                'notes' => $request->notes,
            ]);

            $total = 0;

            foreach ($request->items as $itemData) {
                $qty = (int) $itemData['quantity'];
                $unitCost = (float) $itemData['unit_cost'];

                if ($qty < 1 || $unitCost < 0) {
                    throw ValidationException::withMessages([
                        'items' => 'Kuantitas atau harga beli tidak valid.',
                    ]);
                }

                $product = Product::lockForUpdate()->findOrFail($itemData['product_id']);

                $subtotal = $qty * $unitCost;

                $purchase->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'subtotal' => $subtotal,
                ]);

                // Update harga beli terakhir dan tambah stok produk
                $product->update([
                    'purchase_price' => $unitCost,
                ]);
                $product->increment('stock', $qty);

                $total += $subtotal;
            }

            $purchase->update(['total' => $total]);

            return $purchase;
        });

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Transaksi Pembelian ' . $purchase->purchase_number . ' berhasil disimpan dan stok produk telah ditambahkan.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product']);

        return view('purchases.show', compact('purchase'));
    }

    public function cancel(Purchase $purchase)
    {
        $this->authorize('cancel-transaction');

        if ($purchase->status === 'cancelled') {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        DB::transaction(function () use ($purchase) {
            // Cek apakah stok cukup jika dikurangi kembali
            foreach ($purchase->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->product_id);
                if ($product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'cancel' => "Gagal membatalkan transaksi: Stok {$product->name} tersisa {$product->stock}, tidak cukup untuk dikurangi {$item->quantity}.",
                    ]);
                }
                $product->decrement('stock', $item->quantity);
            }

            $purchase->update(['status' => 'cancelled']);
        });

        return back()->with('success', 'Transaksi Pembelian ' . $purchase->purchase_number . ' berhasil dibatalkan dan penambahan stok telah ditarik kembali.');
    }

    private function generatePurchaseNumber(): string
    {
        $datePrefix = 'PO-' . date('Ymd') . '-';
        $latest = Purchase::where('purchase_number', 'like', $datePrefix . '%')
            ->orderByDesc('id')
            ->first();

        if ($latest) {
            $seq = (int) substr($latest->purchase_number, -4);
            $newSeq = str_pad($seq + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSeq = '0001';
        }

        return $datePrefix . $newSeq;
    }
}
