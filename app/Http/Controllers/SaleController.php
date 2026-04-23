<?php

namespace App\Http\Controllers;

use App\Actions\Stock\RecordStockMovement;
use App\Enums\MovementType;
use App\Models\Location;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_number', 'like', "%{$request->search}%")
                    ->orWhere('customer_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }

        $sales = $query->paginate(15)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $locations = Location::orderBy('name')->get();
        $products = Product::where('is_active', true)
            ->where('current_stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('locations', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'nullable|exists:locations,id',
            'sale_date' => 'required|date',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'payment_method' => 'required|in:cash,transfer,qris,other',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if ((int) $product->current_stock < (int) $item['quantity']) {
                return back()
                    ->withInput()
                    ->with('error', "Stok {$product->name} tidak mencukupi (tersedia: {$product->current_stock})");
            }
        }

        $sale = DB::transaction(function () use ($request, $validated) {
            $sale = Sale::create([
                'location_id' => $validated['location_id'] ?? null,
                'sale_date' => $validated['sale_date'],
                'customer_name' => $validated['customer_name'] ?? null,
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_email' => $validated['customer_email'] ?? null,
                'payment_method' => $validated['payment_method'],
                'discount' => $validated['discount'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'user_id' => auth()->id(),
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($request->items as $item) {
                $qty = (int) $item['quantity'];
                $price = (float) $item['unit_price'];
                $disc = (float) ($item['discount'] ?? 0);
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'discount' => $disc,
                    'subtotal' => ($qty * $price) - $disc,
                ]);
            }

            $sale->refresh();
            $sale->calculateTotal();

            return $sale;
        });

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Penjualan berhasil dibuat. Lanjutkan dengan "Selesaikan" untuk memotong stok.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'location', 'items.product.unit']);

        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        if ($sale->status !== 'pending') {
            return redirect()->route('sales.show', $sale)
                ->with('error', 'Penjualan tidak dapat diedit');
        }

        $sale->load('items');
        $locations = Location::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('sales.edit', compact('sale', 'locations', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        if ($sale->status !== 'pending') {
            return redirect()->route('sales.show', $sale)
                ->with('error', 'Penjualan tidak dapat diupdate');
        }

        $validated = $request->validate([
            'location_id' => 'nullable|exists:locations,id',
            'sale_date' => 'required|date',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'payment_method' => 'required|in:cash,transfer,qris,other',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $validated, $sale) {
            $sale->update([
                'location_id' => $validated['location_id'] ?? null,
                'sale_date' => $validated['sale_date'],
                'customer_name' => $validated['customer_name'] ?? null,
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_email' => $validated['customer_email'] ?? null,
                'payment_method' => $validated['payment_method'],
                'discount' => $validated['discount'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            $sale->items()->delete();
            foreach ($request->items as $item) {
                $qty = (int) $item['quantity'];
                $price = (float) $item['unit_price'];
                $disc = (float) ($item['discount'] ?? 0);
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'discount' => $disc,
                    'subtotal' => ($qty * $price) - $disc,
                ]);
            }

            $sale->refresh();
            $sale->calculateTotal();
        });

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Penjualan berhasil diperbarui');
    }

    public function destroy(Sale $sale)
    {
        if ($sale->status === 'completed') {
            return redirect()->route('sales.index')
                ->with('error', 'Penjualan yang sudah selesai tidak dapat dihapus');
        }

        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Penjualan berhasil dihapus');
    }

    public function complete(Sale $sale, RecordStockMovement $mover)
    {
        if ($sale->status !== 'pending') {
            return back()->with('error', 'Penjualan tidak dapat diselesaikan');
        }

        $sale->load('items.product');

        foreach ($sale->items as $item) {
            if ((int) $item->product->current_stock < (int) $item->quantity) {
                return back()->with('error', "Stok {$item->product->name} tidak mencukupi");
            }
        }

        DB::transaction(function () use ($sale, $mover) {
            foreach ($sale->items as $item) {
                $mover->execute(
                    product: $item->product,
                    quantity: (int) $item->quantity,
                    type: MovementType::OUT,
                    reference: $sale,
                    locationId: $sale->location_id,
                    costPrice: (float) $item->product->cost_price,
                    notes: "Penjualan {$sale->sale_number}",
                );
            }
            $sale->update(['status' => 'completed']);
        });

        return back()->with('success', 'Penjualan selesai, stok sudah dipotong.');
    }

    public function cancel(Sale $sale)
    {
        if ($sale->status === 'completed') {
            return back()->with('error', 'Penjualan selesai tidak bisa dibatalkan dari sini.');
        }
        $sale->update(['status' => 'cancelled']);
        return back()->with('success', 'Penjualan dibatalkan');
    }

    public function print(Sale $sale)
    {
        $sale->load(['user', 'items.product.unit']);
        return view('sales.print', compact('sale'));
    }
}
