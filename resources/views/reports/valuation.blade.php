@extends('layouts.app')
@section('title', 'Laporan Valuasi')
@section('content')
@php $fmt = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.'); @endphp
<div class="py-6"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header title="Laporan Valuasi Stok" subtitle="Nilai persediaan & potensi laba">
        <x-slot name="actions">
            <a href="{{ route('reports.export', 'valuation') }}" class="px-4 py-2 rounded-md text-sm text-white bg-slate-600 hover:bg-slate-700">Export</a>
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-stat-card label="Nilai HPP" :value="$fmt($totalCostValue)" color="slate" icon="💰" />
        <x-stat-card label="Nilai Jual" :value="$fmt($totalSellingValue)" color="emerald" icon="🏷️" />
        <x-stat-card label="Potensi Laba" :value="$fmt($totalPotentialProfit)" color="indigo" icon="📈" />
    </div>

    <form method="GET" class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <select name="category_id" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" @selected(request('category_id') == $c->id)>{{ $c->name }}</option>
                @endforeach
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
                    <th class="px-4 py-3 text-right">HPP</th>
                    <th class="px-4 py-3 text-right">Jual</th>
                    <th class="px-4 py-3 text-right">Nilai HPP</th>
                    <th class="px-4 py-3 text-right">Nilai Jual</th>
                    <th class="px-4 py-3 text-right">Pot. Laba</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($products as $p)
                    @php
                        $nHPP = $p->current_stock * $p->cost_price;
                        $nJual = $p->current_stock * $p->selling_price;
                        $laba = $nJual - $nHPP;
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td class="px-4 py-2">
                            <div class="text-gray-900 dark:text-white font-medium">{{ $p->name }}</div>
                            <div class="text-xs text-gray-500">{{ $p->code }}</div>
                        </td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $p->category?->name }}</td>
                        <td class="px-4 py-2 text-right text-gray-900 dark:text-white">{{ $p->current_stock }}</td>
                        <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-300">{{ $fmt($p->cost_price) }}</td>
                        <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-300">{{ $fmt($p->selling_price) }}</td>
                        <td class="px-4 py-2 text-right text-gray-900 dark:text-white">{{ $fmt($nHPP) }}</td>
                        <td class="px-4 py-2 text-right text-gray-900 dark:text-white">{{ $fmt($nJual) }}</td>
                        <td class="px-4 py-2 text-right font-semibold text-emerald-600">{{ $fmt($laba) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $products->links() }}</div>
    </div>
</div></div>
@endsection
