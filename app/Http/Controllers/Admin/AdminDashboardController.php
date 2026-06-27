<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        // Global stats (tanpa company scope)
        $stats = [
            'total_companies' => Company::count(),
            'total_users' => User::withoutGlobalScope('company')->count(),
            'total_products' => Product::withoutGlobalScope('company')->count(),
            'total_sales' => Sale::withoutGlobalScope('company')
                ->where('status', 'completed')
                ->count(),
            'total_sales_value' => (float) Sale::withoutGlobalScope('company')
                ->where('status', 'completed')
                ->sum('total'),
            'total_purchases' => PurchaseOrder::withoutGlobalScope('company')->count(),
            'monthly_new_users' => User::withoutGlobalScope('company')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // User registration trend (12 bulan terakhir)
        $userTrend = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = User::withoutGlobalScope('company')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $userTrend->push([
                'month' => $date->format('M Y'),
                'count' => $count,
            ]);
        }

        // Transaksi 7 hari terakhir
        $salesChart = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $total = (float) Sale::withoutGlobalScope('company')
                ->whereDate('sale_date', $date)
                ->where('status', 'completed')
                ->sum('total');
            $salesChart->push([
                'date' => $date->format('d M'),
                'total' => $total,
            ]);
        }

        // Recent users
        $recentUsers = User::withoutGlobalScope('company')
            ->with('company')
            ->latest()
            ->limit(8)
            ->get();

        // Top companies by user count
        $topCompanies = Company::withCount('users')
            ->orderByDesc('users_count')
            ->limit(5)
            ->get();

        // Online users (active session in last 5 minutes)
        $onlineThreshold = now()->subMinutes(5)->getTimestamp();
        $onlineUsers = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', $onlineThreshold)
            ->count();

        return view('admin.dashboard', compact(
            'stats',
            'userTrend',
            'salesChart',
            'recentUsers',
            'topCompanies',
            'onlineUsers'
        ));
    }
}
