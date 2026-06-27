<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Sale;
use App\Models\StockOpname;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display list of all users with search & filter.
     */
    public function index(Request $request): View
    {
        $query = User::withoutGlobalScope('company')->with('company');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by company
        if ($companyId = $request->input('company_id')) {
            $query->where('company_id', $companyId);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        // Companies for filter dropdown
        $companies = \App\Models\Company::orderBy('name')->get();

        // Online user IDs (active session in last 5 minutes)
        $onlineThreshold = now()->subMinutes(5)->getTimestamp();
        $onlineUserIds = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', $onlineThreshold)
            ->pluck('user_id')
            ->unique()
            ->toArray();

        return view('admin.users.index', compact('users', 'companies', 'onlineUserIds'));
    }

    /**
     * Show detailed user information with usage stats.
     */
    public function show(int $userId): View
    {
        $user = User::withoutGlobalScope('company')
            ->with('company')
            ->findOrFail($userId);

        // Usage statistics (scoped to this user's company)
        $companyId = $user->company_id;

        $usageStats = [
            'products' => Product::withoutGlobalScope('company')
                ->where('company_id', $companyId)
                ->count(),
            'sales' => Sale::withoutGlobalScope('company')
                ->where('company_id', $companyId)
                ->count(),
            'sales_completed' => Sale::withoutGlobalScope('company')
                ->where('company_id', $companyId)
                ->where('status', 'completed')
                ->count(),
            'sales_value' => (float) Sale::withoutGlobalScope('company')
                ->where('company_id', $companyId)
                ->where('status', 'completed')
                ->sum('total'),
            'purchases' => PurchaseOrder::withoutGlobalScope('company')
                ->where('company_id', $companyId)
                ->count(),
            'opnames' => StockOpname::withoutGlobalScope('company')
                ->where('company_id', $companyId)
                ->count(),
        ];

        // Session info
        $onlineThreshold = now()->subMinutes(5)->getTimestamp();
        $session = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderByDesc('last_activity')
            ->first();

        $isOnline = $session && $session->last_activity >= $onlineThreshold;

        return view('admin.users.show', compact('user', 'usageStats', 'session', 'isOnline'));
    }

    /**
     * Show the edit form for a user.
     */
    public function edit(int $userId): View
    {
        $user = User::withoutGlobalScope('company')
            ->with('company')
            ->findOrFail($userId);

        $companies = \App\Models\Company::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'companies'));
    }

    /**
     * Update the user.
     */
    public function update(Request $request, int $userId): RedirectResponse
    {
        $user = User::withoutGlobalScope('company')->findOrFail($userId);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'company_id' => ['required', 'exists:companies,id'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->company_id = $validated['company_id'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Delete a user.
     */
    public function destroy(int $userId): RedirectResponse
    {
        $user = User::withoutGlobalScope('company')->findOrFail($userId);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
