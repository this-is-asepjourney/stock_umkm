<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')
            ->withCount('products')
            ->latest()
            ->paginate(20);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['color'] = $validated['color'] ?? '#6366F1';

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function show(Category $category)
    {
        $category->load('parent', 'children');

        $products = $category->products()
            ->latest()
            ->paginate(20);

        return view('categories.show', compact('category', 'products'));
    }

    public function edit(Category $category)
    {
        $categories = Category::where('is_active', true)
            ->where('id', '!=', $category->id)
            ->get();

        return view('categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}