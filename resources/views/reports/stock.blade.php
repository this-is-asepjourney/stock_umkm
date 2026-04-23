@extends('layouts.app')
@section('title', 'Laporan Stok')
@section('content')
@php $fmt = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.'); @endphp
<div class="py-6"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header title="Laporan Stok" subtitle="Ringkasan kondisi stok saat ini">
        <x-slot name="actions">
            <a href="{{ route('reports.export', 'stock') }}" class="px-4 py-2 rounded-md text-sm text-white bg-slate-600 hover:bg-slate-700">Export</a>
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Total Produk" :value="number_format($totalProducts)" color="indigo" icon="📦" />
        <x-stat-card label="Nilai Stok" :value="$fmt($totalStockValue)" color="emerald" icon="💰" />
        <x-stat-card label="Stok Rendah" :value="number_format($lowStockCount)" color="amber" icon="⚠️" />
        <x-stat-card label="Stok Habis" :value="number_format($outOfStockCount)" color="rose" icon="🚫" />
    </div>

    <form method="GET" class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <select name="category_id" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" @selected(request('category_id') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            <select name="stock_status" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Semua Status Stok</option>
                <option value="low" @selected(request('stock_status') === 'low')>Stok Rendah</option>
                <option value="out" @selected(request('stock_status') === 'out')>Habis</option>
            </select>
            <button class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm">Filter</button>
        </div>
    </form>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3 text-left">Produk</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-right">Stok</th>
                    <th class="px-4 py-3 text-right">Min</th>
                    <th class="px-4 py-3 text-right">Max</th>
                    <th class="px-4 py-3 text-right">Harga Beli</th>
                    <th class="px-4 py-3 text-right">Nilai</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($products as $product)
                    @php
                        $value = $product->current_stock * $product->cost_price;
                        [$badge, $color] = $product->current_stock <= 0
                            ? ['Habis','rose']
                            : ($product->current_stock <= $product->min_stock
                                ? ['Rendah','amber']
                                : ['Normal','emerald']);
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td class="px-4 py-2">
                            <div class="text-gray-900 dark:text-white font-medium">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500">{{ $product->code }}</div>
                        </td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $product->category?->name }}</td>
                        <td class="px-4 py-2 text-right text-gray-900 dark:text-white font-semibold">{{ $product->current_stock }} {{ $product->unit?->symbol }}</td>
                        <td class="px-4 py-2 text-right text-gray-500">{{ $product->min_stock }}</td>
                        <td class="px-4 py-2 text-right text-gray-500">{{ $product->max_stock }}</td>
                        <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-300">{{ $fmt($product->cost_price) }}</td>
                        <td class="px-4 py-2 text-right font-semibold text-gray-900 dark:text-white">{{ $fmt($value) }}</td>
                        <td class="px-4 py-2 text-center"><x-status-badge :color="$color" :label="$badge" /></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $products->links() }}</div>
    </div>
</div></div>
@endsection
