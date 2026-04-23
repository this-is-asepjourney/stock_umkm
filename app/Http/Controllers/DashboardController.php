<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\StockOpname;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_stock_value' => (float) Product::sum(DB::raw('current_stock * cost_price')),
            'low_stock_products' => Product::lowStock()->count(),
            'out_of_stock' => Product::outOfStock()->count(),
            'monthly_sales' => (float) Sale::whereMonth('sale_date', now()->month)
                ->whereYear('sale_date', now()->year)
                ->where('status', 'completed')
                ->sum('total'),
            'monthly_sales_count' => Sale::whereMonth('sale_date', now()->month)
                ->whereYear('sale_date', now()->year)
                ->count(),
            'pending_po' => PurchaseOrder::whereIn('status', ['draft', 'ordered', 'partial'])->count(),
            'active_opname' => StockOpname::whereIn('status', ['draft', 'in_progress'])->count(),
        ];

        $recentActivities = StockMovement::with(['product', 'user'])
            ->latest()
            ->limit(8)
            ->get();

        $topProducts = Product::select('products.*')
            ->selectSub(function ($q) {
                $q->from('sale_items')
                    ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
                    ->whereColumn('sale_items.product_id', 'products.id')
                    ->whereMonth('sales.sale_date', now()->month)
                    ->whereYear('sales.sale_date', now()->year)
                    ->where('sales.status', 'completed')
                    ->selectRaw('COALESCE(SUM(sale_items.quantity), 0)');
            }, 'total_sold')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $lowStockList = Product::with('unit')
            ->lowStock()
            ->limit(5)
            ->get();

        // Chart data: penjualan 7 hari terakhir
        $salesChart = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $total = (float) Sale::whereDate('sale_date', $date)
                ->where('status', 'completed')
                ->sum('total');
            $salesChart->push([
                'date' => $date->format('d M'),
                'total' => $total,
            ]);
        }

        return view('dashboard', compact('stats', 'recentActivities', 'topProducts', 'lowStockList', 'salesChart'));
    }
}
