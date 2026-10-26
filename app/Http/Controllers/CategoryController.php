<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $categories = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'quantity' => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah terdaftar.',
            'quantity.integer' => 'Kuantitas harus berupa bilangan bulat.',
            'quantity.min' => 'Kuantitas tidak boleh bernilai negatif.',
        ]);

        $validated['quantity'] = $validated['quantity'] ?? 0;

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        $category->load('products');

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'quantity' => ['required', 'integer', 'min:0'],
            'product_stocks' => ['nullable', 'array'],
            'product_stocks.*' => ['integer', 'min:0'],
        ], [
            'name.unique' => 'Nama kategori sudah digunakan.',
            'quantity.required' => 'Kuantitas wajib diisi.',
            'quantity.integer' => 'Kuantitas harus berupa bilangan bulat.',
            'quantity.min' => 'Kuantitas tidak boleh bernilai negatif.',
        ]);

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'quantity' => $validated['quantity'],
        ]);

        if ($request->has('product_stocks') && is_array($request->product_stocks)) {
            foreach ($request->product_stocks as $productId => $stockVal) {
                \App\Models\Product::where('id', $productId)
                    ->where('category_id', $category->id)
                    ->update(['stock' => max(0, (int)$stockVal)]);
            }
        }

        return redirect()->route('categories.index')
            ->with('success', 'Kategori dan kuantitas berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk yang terkait.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
