<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['user', 'items.product'])
            ->latest('sold_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('sale_number', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('sold_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('sold_at', '<=', $request->end_date);
        }

        $sales = $query->paginate(10)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        // Hanya tampilkan produk aktif yang stoknya > 0
        $products = Product::where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sold_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ], [
            'items.required' => 'Minimal satu produk harus dipilih untuk transaksi penjualan.',
            'items.*.product_id.required' => 'Produk wajib dipilih.',
            'items.*.product_id.exists' => 'Produk yang dipilih tidak valid di database.',
            'items.*.quantity.required' => 'Jumlah kuantitas wajib diisi.',
            'items.*.quantity.min' => 'Jumlah kuantitas minimal 1.',
        ]);

        $sale = DB::transaction(function () use ($request) {
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'sale_number' => $this->generateSaleNumber(),
                'total' => 0,
                'status' => 'completed',
                'sold_at' => $request->sold_at,
                'notes' => $request->notes,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                // Sesuai slide 16: lockForUpdate() mencegah race condition dan overselling
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];

                if ($qty < 1 || $product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'items' => "Stok tidak mencukupi untuk '{$product->name}'. Tersedia: {$product->stock}, diminta: {$qty}.",
                    ]);
                }

                // Harga wajib diambil dari database, bukan dari input browser
                $unitPrice = $product->selling_price;
                $subtotal = $unitPrice * $qty;

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                // Kurangi stok di database
                $product->decrement('stock', $qty);

                $total += $subtotal;
            }

            $sale->update(['total' => $total]);

            return $sale;
        });

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Transaksi Penjualan ' . $sale->sale_number . ' berhasil diproses dan stok telah diperbarui.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'items.product']);

        return view('sales.show', compact('sale'));
    }

    public function cancel(Sale $sale)
    {
        $this->authorize('cancel-transaction');

        if ($sale->status === 'cancelled') {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        DB::transaction(function () use ($sale) {
            // Kembalikan stok yang sebelumnya dikurangi
            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->product_id);
                $product->increment('stock', $item->quantity);
            }

            $sale->update(['status' => 'cancelled']);
        });

        return back()->with('success', 'Transaksi Penjualan ' . $sale->sale_number . ' berhasil dibatalkan dan seluruh stok produk telah dikembalikan.');
    }

    private function generateSaleNumber(): string
    {
        $datePrefix = 'INV-' . date('Ymd') . '-';
        $latest = Sale::where('sale_number', 'like', $datePrefix . '%')
            ->orderByDesc('id')
            ->first();

        if ($latest) {
            $seq = (int) substr($latest->sale_number, -4);
            $newSeq = str_pad($seq + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSeq = '0001';
        }

        return $datePrefix . $newSeq;
    }
}
