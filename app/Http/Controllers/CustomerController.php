<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleItem;

class CustomerController extends Controller
{
    /**
     * Dashboard khusus Customer: Menampilkan ringkasan belanja, katalog produk, dan riwayat transaksi sendiri.
     */
    public function dashboard(Request $request)
    {
        $user = auth()->user();

        // 1. Metrik Belanja Khusus Customer
        $salesQuery = Sale::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('customer_id', $user->id);
        });

        $totalSpent = (clone $salesQuery)->where('status', 'completed')->sum('total');
        $totalOrders = (clone $salesQuery)->where('status', 'completed')->count();
        $totalItemsBought = SaleItem::whereHas('sale', function ($sq) use ($user) {
            $sq->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_id', $user->id);
            })->where('status', 'completed');
        })->sum('quantity');

        // 2. Riwayat Transaksi Belanja Milik Customer Sendiri
        $mySales = (clone $salesQuery)
            ->with(['items.product'])
            ->latest('sold_at')
            ->paginate(5, ['*'], 'orders_page')
            ->withQueryString();

        // 3. Katalog Produk yang Tersedia
        $catalogQuery = Product::with('category')->where('stock', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $catalogQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $catalogQuery->where('category_id', $request->category_id);
        }

        $catalog = $catalogQuery->orderBy('name')->paginate(8, ['*'], 'products_page')->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('customer.dashboard', compact(
            'user',
            'totalSpent',
            'totalOrders',
            'totalItemsBought',
            'mySales',
            'catalog',
            'categories'
        ));
    }

    /**
     * Fitur Pesan / Beli Langsung dari Katalog Produk
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'product_id.required' => 'Pilih produk yang ingin dibeli.',
            'quantity.min' => 'Kuantitas minimal 1 unit.',
        ]);

        $sale = DB::transaction(function () use ($request) {
            $product = Product::lockForUpdate()->findOrFail($request->product_id);
            $qty = (int) $request->quantity;

            if ($product->stock < $qty) {
                throw ValidationException::withMessages([
                    'quantity' => "Maaf, stok '{$product->name}' tidak mencukupi. Sisa stok: {$product->stock} unit.",
                ]);
            }

            $datePrefix = 'INV-' . date('Ymd') . '-';
            $latest = Sale::where('sale_number', 'like', $datePrefix . '%')->orderByDesc('id')->first();
            $seq = $latest ? ((int) substr($latest->sale_number, -4)) + 1 : 1;
            $saleNumber = $datePrefix . str_pad($seq, 4, '0', STR_PAD_LEFT);

            $subtotal = $product->selling_price * $qty;

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'customer_id' => auth()->id(),
                'sale_number' => $saleNumber,
                'total' => $subtotal,
                'status' => 'completed',
                'sold_at' => now(),
                'notes' => $request->notes ?? 'Pesanan online mandiri pelanggan',
            ]);

            $sale->items()->create([
                'product_id' => $product->id,
                'quantity' => $qty,
                'unit_price' => $product->selling_price,
                'subtotal' => $subtotal,
            ]);

            // Potong stok produk
            $product->decrement('stock', $qty);

            return $sale;
        });

        return redirect()->route('customer.sales.show', $sale)
            ->with('success', 'Pesanan Anda (' . $sale->sale_number . ') berhasil dibuat dan pembayaran selesai!');
    }

    /**
     * Detail Nota Belanja Milik Customer
     */
    public function showSale(Sale $sale)
    {
        $user = auth()->user();

        // Validasi kepemilikan transaksi: Customer hanya boleh melihat transaksinya sendiri
        if (!$user->isAdmin() && (int) $sale->user_id !== (int) $user->id && (int) $sale->customer_id !== (int) $user->id) {
            abort(403, 'Akses ditolak: Anda hanya dapat melihat rincian transaksi belanja milik Anda sendiri.');
        }

        $sale->load(['items.product', 'user']);

        return view('customer.sale_detail', compact('sale'));
    }
}
