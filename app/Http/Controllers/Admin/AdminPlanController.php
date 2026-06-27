<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPlanController extends Controller
{
    public function index()
    {
        $plans = \App\Models\Plan::latest()->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_in_days' => 'required|integer|min:1',
            'max_users' => 'nullable|integer|min:1',
            'max_products' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        \App\Models\Plan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit(\App\Models\Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, \App\Models\Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_in_days' => 'required|integer|min:1',
            'max_users' => 'nullable|integer|min:1',
            'max_products' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // checkbox logic
        if (!$request->has('is_active')) {
            $validated['is_active'] = false;
        }

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(\App\Models\Plan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
    }
}
