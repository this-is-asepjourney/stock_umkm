<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::with('parent')
            ->withCount(['children', 'products'])
            ->latest()
            ->paginate(20);

        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        $locations = Location::all();
        return view('locations.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
            'type' => 'required|in:warehouse,rack,bin',
            'description' => 'nullable|string',
        ]);

        $validated['code'] = Location::generateCode();

        Location::create($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function show(Location $location)
    {
        $location->load('parent', 'children');

        $stockMovements = $location->stockMovements()
            ->with('product')
            ->latest()
            ->limit(20)
            ->get();

        return view('locations.show', compact('location', 'stockMovements'));
    }

    public function edit(Location $location)
    {
        $locations = Location::where('id', '!=', $location->id)->get();
        return view('locations.edit', compact('location', 'locations'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
            'type' => 'required|in:warehouse,rack,bin',
            'description' => 'nullable|string',
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil diperbarui');
    }

    public function destroy(Location $location)
    {
        // Cek apakah lokasi memiliki child
        if ($location->children()->count() > 0) {
            return redirect()->route('locations.index')
                ->with('error', 'Lokasi tidak dapat dihapus karena memiliki sub-lokasi');
        }

        $location->delete();

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil dihapus');
    }
}