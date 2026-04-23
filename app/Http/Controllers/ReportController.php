<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Category;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stock(Request $request)
    {
        $query = Product::with(['category', 'unit', 'supplier']);

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->where('current_stock', '<=', \DB::raw('min_stock'))
                    ->where('current_stock', '>', 0);
            } elseif ($request->stock_status === 'out') {
                $query->where('current_stock', '<=', 0);
            }
        }

        $products = $query->latest()->paginate(20);

        $categories = Category::where('is_active', true)->get();
        $totalStockValue = Product::sum(\DB::raw('current_stock * cost_price'));
        $totalProducts = Product::count();
        $lowStockCount = Product::where('current_stock', '<=', \DB::raw('min_stock'))
            ->where('current_stock', '>', 0)
            ->count();
        $outOfStockCount = Product::where('current_stock', '<=', 0)->count();

        return view('reports.stock', compact(
            'products',
            'categories',
            'totalStockValue',
            'totalProducts',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    public function movement(Request $request)
    {
        $query = StockMovement::with(['product', 'user', 'location']);

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $movements = $query->latest()->paginate(20);

        $products = Product::where('is_active', true)->get();

        // Summary
        $summary = [
            'total_in' => $query->clone()->where('type', 'IN')->sum('quantity'),
            'total_out' => $query->clone()->where('type', 'OUT')->sum('quantity'),
            'total_adjustment' => $query->clone()->where('type', 'ADJUSTMENT')->sum('quantity'),
        ];

        return view('reports.movement', compact('movements', 'products', 'summary'));
    }

    public function valuation(Request $request)
    {
        $query = Product::with(['category']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(20);
        $categories = Category::where('is_active', true)->get();

        // Calculate valuation
        $totalCostValue = 0;
        $totalSellingValue = 0;
        $totalPotentialProfit = 0;

        foreach ($products as $product) {
            $totalCostValue += $product->current_stock * $product->cost_price;
            $totalSellingValue += $product->current_stock * $product->selling_price;
            $totalPotentialProfit += $product->current_stock * ($product->selling_price - $product->cost_price);
        }

        return view('reports.valuation', compact(
            'products',
            'categories',
            'totalCostValue',
            'totalSellingValue',
            'totalPotentialProfit'
        ));
    }

    public function export($type)
    {
        // Export logic here
        return back()->with('info', 'Fitur export akan segera hadir');
    }
}