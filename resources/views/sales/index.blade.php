@extends('layouts.app')
@section('title', 'Penjualan')
@section('content')
@php
    $fmt = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $statusColors = ['pending' => 'amber', 'completed' => 'emerald', 'cancelled' => 'rose'];
    $statusIcons = ['pending' => '⏳', 'completed' => '✓', 'cancelled' => '✕'];
@endphp
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
        <x-page-header title="Penjualan" subtitle="Riwayat transaksi penjualan ke customer" icon="🛒">
            <x-slot name="actions">
                <a href="{{ route('sales.create') }}" class="aj-btn-success">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Transaksi Baru
                </a>
            </x-slot>
        </x-page-header>

        <form method="GET" class="aj-card p-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div class="md:col-span-2 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor transaksi atau customer..."
                        class="aj-input pl-10">
                </div>
                <select name="status" class="aj-input">
                    <option value="">Semua Status</option>
                    @foreach (['pending','completed','cancelled'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="aj-input">
                <div class="flex gap-2">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="aj-input">
                    <button class="aj-btn-primary flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </div>
        </form>

        <div class="aj-card overflow-hidden">
            @if ($sales->isEmpty())
                <x-empty-state title="Belum ada penjualan"
                    message="Mulai dengan mencatat transaksi penjualan pertamamu."
                    :action="route('sales.create')" actionLabel="Transaksi Baru" />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200/60 dark:divide-white/5">
                        <thead class="bg-gray-50/80 dark:bg-gray-800/60 text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3 text-left">Nomor</th>
                                <th class="px-6 py-3 text-left">Tanggal</th>
                                <th class="px-6 py-3 text-left">Customer</th>
                                <th class="px-6 py-3 text-left">Bayar</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-right">Total</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/60 dark:divide-white/5">
                            @foreach ($sales as $sale)
                                <tr class="hover:bg-indigo-50/40 dark:hover:bg-indigo-500/5 transition">
                                    <td class="px-6 py-3.5">
                                        <a href="{{ route('sales.show', $sale) }}" class="font-mono text-sm font-semibold text-indigo-600 dark:text-indigo-300 hover:underline">
                                            {{ $sale->sale_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-300">{{ optional($sale->sale_date)->format('d M Y') }}</td>
                                    <td class="px-6 py-3.5 text-sm text-gray-900 dark:text-white font-medium">
                                        {{ $sale->customer_name ?? 'Walk-in Customer' }}
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <span class="aj-chip bg-gray-100 dark:bg-gray-700/60 text-gray-700 dark:text-gray-200">
                                            {{ strtoupper($sale->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <x-status-badge :color="$statusColors[$sale->status] ?? 'slate'" dot :label="ucfirst($sale->status)" />
                                    </td>
                                    <td class="px-6 py-3.5 text-right text-sm font-extrabold text-gray-900 dark:text-white">{{ $fmt($sale->total) }}</td>
                                    <td class="px-6 py-3.5 text-right whitespace-nowrap">
                                        <a href="{{ route('sales.show', $sale) }}" class="aj-link text-xs font-semibold">Detail</a>
                                        @if ($sale->status === 'pending')
                                            <span class="text-gray-300 dark:text-gray-600 mx-1">·</span>
                                            <a href="{{ route('sales.edit', $sale) }}" class="aj-link text-xs font-semibold">Edit</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t border-gray-200/60 dark:border-white/5">{{ $sales->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
