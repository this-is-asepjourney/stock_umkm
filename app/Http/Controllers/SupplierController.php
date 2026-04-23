<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount(['products', 'purchaseOrders'])
            ->latest()
            ->paginate(20);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'payment_term' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['code'] = Supplier::generateCode();
        $validated['is_active'] = $request->boolean('is_active', true);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }

    public function show(Supplier $supplier)
    {
        $supplier->loadCount(['products', 'purchaseOrders']);

        $purchaseOrders = $supplier->purchaseOrders()
            ->latest()
            ->limit(10)
            ->get();

        $products = $supplier->products()
            ->latest()
            ->limit(10)
            ->get();

        return view('suppliers.show', compact('supplier', 'purchaseOrders', 'products'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'payment_term' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus');
    }
}