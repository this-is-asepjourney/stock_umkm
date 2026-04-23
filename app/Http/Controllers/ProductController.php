<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'unit', 'supplier'])
            ->latest()
            ->paginate(20);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $units = Unit::all();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('products.create', compact('categories', 'units', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'barcode' => 'nullable|string|unique:products',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'unit', 'supplier']);

        $movements = $product->stockMovements()
            ->with('user')
            ->latest()
            ->limit(20)
            ->get();

        return view('products.show', compact('product', 'movements'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $units = Unit::all();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('products.edit', compact('product', 'categories', 'units', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
    }

    // Search products for select2
    public function search(Request $request)
    {
        $search = $request->input('q');

        $products = Product::where('is_active', true)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'code', 'current_stock', 'selling_price']);

        return response()->json($products);
    }
}