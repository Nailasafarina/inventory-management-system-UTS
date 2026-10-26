<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereColumn('stock', '<=', 'minimum_stock');
            } elseif ($request->stock_status === 'out') {
                $query->where('stock', '<=', 0);
            } elseif ($request->stock_status === 'available') {
                $query->where('stock', '>', 0);
            }
        }

        // Sesuai slide 10: with('category') untuk menghindari N+1 query problem
        $products = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Sesuai slide 15: validasi ketat server-side
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'sku.required' => 'SKU wajib diisi.',
            'sku.unique' => 'SKU sudah digunakan oleh produk lain.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'purchase_price.min' => 'Harga beli tidak boleh negatif.',
            'selling_price.min' => 'Harga jual tidak boleh negatif.',
            'stock.min' => 'Stok awal tidak boleh bernilai negatif.',
            'minimum_stock.min' => 'Stok minimum tidak boleh negatif.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk ' . $validated['name'] . ' berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'saleItems.sale', 'purchaseItems.purchase']);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product->id)],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'sku.unique' => 'SKU sudah digunakan oleh produk lain.',
            'category_id.exists' => 'Kategori tidak valid.',
            'purchase_price.min' => 'Harga beli tidak boleh negatif.',
            'selling_price.min' => 'Harga jual tidak boleh negatif.',
            'stock.required' => 'Kuantitas stok wajib diisi.',
            'stock.integer' => 'Kuantitas stok harus berupa bilangan bulat.',
            'stock.min' => 'Kuantitas stok tidak boleh bernilai negatif.',
            'minimum_stock.min' => 'Stok minimum tidak boleh negatif.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = null;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Data produk ' . $product->name . ' berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Sesuai slide 8: Produk yang sudah ada riwayat transaksi dilindungi dari hard delete
        $hasTransactions = $product->saleItems()->exists() || $product->purchaseItems()->exists();

        if ($hasTransactions) {
            // Gunakan soft delete agar histori transaksi tetap terjaga
            $product->delete();
            return redirect()->route('products.index')
                ->with('success', 'Produk ' . $product->name . ' diarsipkan (soft delete) karena memiliki riwayat transaksi.');
        }

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->forceDelete();

        return redirect()->route('products.index')
            ->with('success', 'Produk ' . $product->name . ' berhasil dihapus.');
    }
}
