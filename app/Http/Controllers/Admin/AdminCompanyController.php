<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminCompanyController extends Controller
{
    /**
     * Display list of all companies.
     */
    public function index(Request $request): View
    {
        $query = Company::withCount('users');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $companies = $query->latest()->paginate(15)->withQueryString();

        // Add stats for each company
        $companies->getCollection()->transform(function ($company) {
            $company->products_count = Product::withoutGlobalScope('company')
                ->where('company_id', $company->id)
                ->count();
            $company->sales_total = (float) Sale::withoutGlobalScope('company')
                ->where('company_id', $company->id)
                ->where('status', 'completed')
                ->sum('total');
            return $company;
        });

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show detailed company information.
     */
    public function show(Company $company): View
    {
        $company->loadCount('users');

        $members = User::withoutGlobalScope('company')
            ->where('company_id', $company->id)
            ->latest()
            ->get();

        // Online user IDs
        $onlineThreshold = now()->subMinutes(5)->getTimestamp();
        $onlineUserIds = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', $onlineThreshold)
            ->pluck('user_id')
            ->unique()
            ->toArray();

        $stats = [
            'products' => Product::withoutGlobalScope('company')
                ->where('company_id', $company->id)
                ->count(),
            'sales' => Sale::withoutGlobalScope('company')
                ->where('company_id', $company->id)
                ->where('status', 'completed')
                ->count(),
            'sales_value' => (float) Sale::withoutGlobalScope('company')
                ->where('company_id', $company->id)
                ->where('status', 'completed')
                ->sum('total'),
        ];

        return view('admin.companies.show', compact('company', 'members', 'onlineUserIds', 'stats'));
    }

    /**
     * Show the form for editing the company's subscription.
     */
    public function edit(Company $company): View
    {
        $plans = \App\Models\Plan::where('is_active', true)->get();
        return view('admin.companies.edit', compact('company', 'plans'));
    }

    /**
     * Update the company's subscription details.
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'plan_id' => 'nullable|exists:plans,id',
            'subscription_status' => 'required|in:trial,active,inactive,cancelled',
            'subscription_ends_at' => 'nullable|date',
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.show', $company)->with('success', 'Company subscription updated successfully.');
    }
}
