<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::withCount('products')
            ->latest()
            ->paginate(20);

        return view('units.index', compact('units'));
    }

    public function create()
    {
        return view('units.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'description' => 'nullable|string',
        ]);

        Unit::create($validated);

        return redirect()->route('units.index')
            ->with('success', 'Satuan berhasil ditambahkan');
    }

    public function show(Unit $unit)
    {
        $products = $unit->products()
            ->latest()
            ->paginate(20);

        return view('units.show', compact('unit', 'products'));
    }

    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'description' => 'nullable|string',
        ]);

        $unit->update($validated);

        return redirect()->route('units.index')
            ->with('success', 'Satuan berhasil diperbarui');
    }

    public function destroy(Unit $unit)
    {
        // Cek apakah unit masih digunakan
        if ($unit->products()->count() > 0) {
            return redirect()->route('units.index')
                ->with('error', 'Satuan tidak dapat dihapus karena masih digunakan oleh produk');
        }

        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Satuan berhasil dihapus');
    }
}