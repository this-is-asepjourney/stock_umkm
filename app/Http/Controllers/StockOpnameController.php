<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    public function index()
    {
        $opnames = StockOpname::with(['user', 'location', 'category'])
            ->latest()
            ->paginate(20);

        return view('stock-opname.index', compact('opnames'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $locations = Location::all();

        return view('stock-opname.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'nullable|exists:locations,id',
            'category_id' => 'nullable|exists:categories,id',
            'opname_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'draft';

        $opname = StockOpname::create($validated);

        // Generate items
        $opname->generateItems();

        return redirect()->route('stock-opname.show', $opname)
            ->with('success', 'Sesi Stock Opname berhasil dibuat');
    }

    public function show(StockOpname $opname)
    {
        $opname->load(['user', 'location', 'category']);

        $items = $opname->items()
            ->with(['product.category', 'product.unit', 'countedBy'])
            ->paginate(20);

        return view('stock-opname.show', compact('opname', 'items'));
    }

    public function edit(StockOpname $opname)
    {
        if ($opname->status !== 'draft') {
            return redirect()->route('stock-opname.show', $opname)
                ->with('error', 'Stock Opname tidak dapat diedit');
        }

        $categories = Category::where('is_active', true)->get();
        $locations = Location::all();

        return view('stock-opname.edit', compact('opname', 'categories', 'locations'));
    }

    public function update(Request $request, StockOpname $opname)
    {
        if ($opname->status !== 'draft') {
            return redirect()->route('stock-opname.show', $opname)
                ->with('error', 'Stock Opname tidak dapat diupdate');
        }

        $validated = $request->validate([
            'location_id' => 'nullable|exists:locations,id',
            'category_id' => 'nullable|exists:categories,id',
            'opname_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $opname->update($validated);

        return redirect()->route('stock-opname.show', $opname)
            ->with('success', 'Stock Opname berhasil diperbarui');
    }

    public function destroy(StockOpname $opname)
    {
        if ($opname->status === 'completed') {
            return redirect()->route('stock-opname.index')
                ->with('error', 'Stock Opname yang sudah selesai tidak dapat dihapus');
        }

        $opname->delete();

        return redirect()->route('stock-opname.index')
            ->with('success', 'Stock Opname berhasil dihapus');
    }

    public function start(StockOpname $opname)
    {
        if ($opname->status !== 'draft') {
            return redirect()->route('stock-opname.show', $opname)
                ->with('error', 'Stock Opname tidak dapat dimulai');
        }

        $opname->start();

        return redirect()->route('stock-opname.show', $opname)
            ->with('success', 'Stock Opname telah dimulai');
    }

    public function complete(StockOpname $opname)
    {
        if ($opname->status !== 'in_progress') {
            return redirect()->route('stock-opname.show', $opname)
                ->with('error', 'Stock Opname tidak dapat diselesaikan');
        }

        // Check if all items are counted
        $uncountedItems = $opname->items()->where('is_counted', false)->count();
        if ($uncountedItems > 0) {
            return redirect()->route('stock-opname.show', $opname)
                ->with('error', "Masih ada {$uncountedItems} item yang belum dihitung");
        }

        $opname->complete();

        return redirect()->route('stock-opname.show', $opname)
            ->with('success', 'Stock Opname telah selesai');
    }

    public function countItem(Request $request, StockOpname $opname, StockOpnameItem $item)
    {
        if ($opname->status !== 'in_progress') {
            return back()->with('error', 'Stock Opname tidak dalam progress');
        }

        $validated = $request->validate([
            'physical_qty' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $item->count($validated['physical_qty'], $validated['notes'] ?? null);

        return back()->with('success', 'Hasil hitung berhasil disimpan');
    }

    public function applyAdjustments(StockOpname $opname)
    {
        if ($opname->status !== 'completed') {
            return redirect()->route('stock-opname.show', $opname)
                ->with('error', 'Stock Opname belum selesai');
        }

        $items = $opname->items()
            ->where('discrepancy', '!=', 0)
            ->get();

        foreach ($items as $item) {
            $item->applyAdjustment();
        }

        return redirect()->route('stock-opname.show', $opname)
            ->with('success', 'Penyesuaian stok berhasil diterapkan');
    }
}