<?php

namespace App\Http\Controllers;

use App\Actions\Purchase\ReceiveGoodsAction;
use App\Models\Location;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('po_number', 'like', "%{$request->search}%");
        }

        $purchases = $query->paginate(15)->withQueryString();

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'locations', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'location_id' => 'nullable|exists:locations,id',
            'po_date' => 'required|date',
            'expected_date' => 'nullable|date|after_or_equal:po_date',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $validated, &$purchase) {
            $purchase = PurchaseOrder::create([
                'supplier_id' => $validated['supplier_id'],
                'location_id' => $validated['location_id'] ?? null,
                'po_date' => $validated['po_date'],
                'expected_date' => $validated['expected_date'] ?? null,
                'user_id' => auth()->id(),
                'status' => 'draft',
                'discount' => $validated['discount'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($request->items as $item) {
                $qty = (int) $item['quantity_ordered'];
                $price = (float) $item['unit_price'];
                $disc = (float) ($item['discount'] ?? 0);

                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $qty,
                    'quantity_received' => 0,
                    'unit_price' => $price,
                    'discount' => $disc,
                    'subtotal' => ($qty * $price) - $disc,
                ]);
            }

            $purchase->refresh();
            $purchase->calculateTotal();
        });

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Purchase Order berhasil dibuat');
    }

    public function show(PurchaseOrder $purchase)
    {
        $purchase->load(['supplier', 'user', 'location', 'items.product.unit']);

        return view('purchases.show', compact('purchase'));
    }

    public function edit(PurchaseOrder $purchase)
    {
        if ($purchase->status !== 'draft') {
            return redirect()->route('purchases.show', $purchase)
                ->with('error', 'Purchase Order tidak dapat diedit');
        }

        $purchase->load('items');
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('purchases.edit', compact('purchase', 'suppliers', 'locations', 'products'));
    }

    public function update(Request $request, PurchaseOrder $purchase)
    {
        if ($purchase->status !== 'draft') {
            return redirect()->route('purchases.show', $purchase)
                ->with('error', 'Purchase Order tidak dapat diupdate');
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'location_id' => 'nullable|exists:locations,id',
            'po_date' => 'required|date',
            'expected_date' => 'nullable|date|after_or_equal:po_date',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $validated, $purchase) {
            $purchase->update([
                'supplier_id' => $validated['supplier_id'],
                'location_id' => $validated['location_id'] ?? null,
                'po_date' => $validated['po_date'],
                'expected_date' => $validated['expected_date'] ?? null,
                'discount' => $validated['discount'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            $purchase->items()->delete();
            foreach ($request->items as $item) {
                $qty = (int) $item['quantity_ordered'];
                $price = (float) $item['unit_price'];
                $disc = (float) ($item['discount'] ?? 0);
                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $qty,
                    'quantity_received' => 0,
                    'unit_price' => $price,
                    'discount' => $disc,
                    'subtotal' => ($qty * $price) - $disc,
                ]);
            }

            $purchase->refresh();
            $purchase->calculateTotal();
        });

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Purchase Order berhasil diperbarui');
    }

    public function destroy(PurchaseOrder $purchase)
    {
        if ($purchase->status !== 'draft') {
            return redirect()->route('purchases.index')
                ->with('error', 'Purchase Order tidak dapat dihapus');
        }

        $purchase->delete();

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase Order berhasil dihapus');
    }

    public function submit(PurchaseOrder $purchase)
    {
        if ($purchase->status !== 'draft') {
            return back()->with('error', 'PO tidak dapat di-submit');
        }

        $purchase->update(['status' => 'ordered']);

        return back()->with('success', 'PO telah dikirim ke supplier');
    }

    public function cancel(PurchaseOrder $purchase)
    {
        if (! in_array($purchase->status, ['draft', 'ordered'])) {
            return back()->with('error', 'PO tidak dapat dibatalkan');
        }

        $purchase->update(['status' => 'cancelled']);

        return back()->with('success', 'PO telah dibatalkan');
    }

    public function receive(Request $request, PurchaseOrder $purchase, ReceiveGoodsAction $action)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*' => 'integer|min:0',
        ]);

        if (! in_array($purchase->status, ['ordered', 'partial'])) {
            return back()->with('error', 'PO tidak siap diterima. Submit PO terlebih dahulu.');
        }

        try {
            $action->execute($purchase, $validated['items']);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Barang berhasil diterima');
    }

    public function print(PurchaseOrder $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product.unit']);

        return view('purchases.print', compact('purchase'));
    }
}
