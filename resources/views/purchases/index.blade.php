@extends('layouts.app')
@section('title', 'Purchase Orders')
@section('content')
@php
    $statusColors = ['draft'=>'slate','ordered'=>'blue','partial'=>'amber','received'=>'emerald','cancelled'=>'rose'];
    $fmt = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
@endphp
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header title="Purchase Order" subtitle="Pembelian dari supplier">
            <x-slot name="actions">
                <a href="{{ route('purchases.create') }}" class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">+ Buat PO</a>
            </x-slot>
        </x-page-header>

        <form method="GET" class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor PO..."
                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <select name="status" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Status</option>
                    @foreach (['draft','ordered','partial','received','cancelled'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm hover:bg-indigo-700">Filter</button>
                <a href="{{ route('purchases.index') }}" class="px-4 py-2 rounded-md bg-gray-100 dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-300 text-center">Reset</a>
            </div>
        </form>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            @if ($purchases->isEmpty())
                <x-empty-state title="Belum ada PO" message="Buat Purchase Order pertama Anda." :action="route('purchases.create')" actionLabel="Buat PO" />
            @else
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3 text-left">Nomor</th>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-left">Supplier</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-right">Total</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($purchases as $po)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                <td class="px-6 py-3 font-mono text-sm text-gray-900 dark:text-white">{{ $po->po_number }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300">{{ optional($po->po_date)->format('d M Y') }}</td>
                                <td class="px-6 py-3 text-sm text-gray-900 dark:text-white">{{ $po->supplier?->name }}</td>
                                <td class="px-6 py-3"><x-status-badge :color="$statusColors[$po->status] ?? 'slate'" :label="ucfirst($po->status)" /></td>
                                <td class="px-6 py-3 text-right font-semibold text-gray-900 dark:text-white">{{ $fmt($po->total) }}</td>
                                <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('purchases.show', $po) }}" class="text-indigo-600 hover:underline text-sm">Detail</a>
                                    @if ($po->status === 'draft')
                                        <a href="{{ route('purchases.edit', $po) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $purchases->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
