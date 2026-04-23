@extends('layouts.app')
@section('title', 'Penjualan ' . $sale->sale_number)
@section('content')
@php
    $fmt = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $statusColors = ['pending'=>'amber','completed'=>'emerald','cancelled'=>'rose'];
@endphp
<div class="py-6"><div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header :title="$sale->sale_number" :subtitle="'Status: ' . ucfirst($sale->status)" :back="route('sales.index')">
        <x-slot name="actions">
            <a href="{{ route('sales.print', $sale) }}" target="_blank"
                class="px-4 py-2 rounded-md text-sm text-white bg-slate-600 hover:bg-slate-700">🖨️ Struk</a>

            @if ($sale->status === 'pending')
                <a href="{{ route('sales.edit', $sale) }}"
                    class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">Edit</a>
                <form method="POST" action="{{ route('sales.complete', $sale) }}" class="inline"
                    onsubmit="return confirm('Selesaikan transaksi? Stok akan otomatis dipotong.');">
                    @csrf
                    <button class="px-4 py-2 rounded-md text-sm text-white bg-emerald-600 hover:bg-emerald-700">✓ Selesaikan</button>
                </form>
                <form method="POST" action="{{ route('sales.cancel', $sale) }}" class="inline"
                    onsubmit="return confirm('Batalkan penjualan ini?');">
                    @csrf
                    <button class="px-4 py-2 rounded-md text-sm text-white bg-rose-600 hover:bg-rose-700">Batal</button>
                </form>
            @endif
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-3">
            <h3 class="font-semibold text-gray-900 dark:text-white">Info</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-gray-500">Tanggal</dt><dd class="text-gray-900 dark:text-white">{{ optional($sale->sale_date)->format('d M Y') }}</dd></div>
                <div><dt class="text-gray-500">Customer</dt><dd class="text-gray-900 dark:text-white">{{ $sale->customer_name ?? 'Walk-in' }}</dd></div>
                <div><dt class="text-gray-500">Kontak</dt><dd class="text-gray-900 dark:text-white">{{ $sale->customer_phone ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Metode Bayar</dt><dd><x-status-badge color="blue" :label="strtoupper($sale->payment_method)" /></dd></div>
                <div><dt class="text-gray-500">Kasir</dt><dd class="text-gray-900 dark:text-white">{{ $sale->user?->name }}</dd></div>
                <div><dt class="text-gray-500">Status</dt><dd><x-status-badge :color="$statusColors[$sale->status] ?? 'slate'" :label="ucfirst($sale->status)" /></dd></div>
            </dl>
        </div>

        <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Item</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-2 text-left">Produk</th>
                        <th class="px-4 py-2 text-right">Qty</th>
                        <th class="px-4 py-2 text-right">Harga</th>
                        <th class="px-4 py-2 text-right">Diskon</th>
                        <th class="px-4 py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($sale->items as $item)
                        <tr>
                            <td class="px-4 py-2"><a href="{{ route('products.show', $item->product) }}" class="text-indigo-600 hover:underline">{{ $item->product?->name }}</a></td>
                            <td class="px-4 py-2 text-right text-gray-900 dark:text-white">{{ $item->quantity }} {{ $item->product?->unit?->symbol }}</td>
                            <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-300">{{ $fmt($item->unit_price) }}</td>
                            <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-300">{{ $fmt($item->discount) }}</td>
                            <td class="px-4 py-2 text-right font-semibold text-gray-900 dark:text-white">{{ $fmt($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-700/30">
                    <tr><td colspan="4" class="px-4 py-2 text-right text-gray-500">Subtotal</td><td class="px-4 py-2 text-right text-gray-900 dark:text-white">{{ $fmt($sale->subtotal) }}</td></tr>
                    <tr><td colspan="4" class="px-4 py-2 text-right text-gray-500">Diskon</td><td class="px-4 py-2 text-right text-gray-900 dark:text-white">-{{ $fmt($sale->discount) }}</td></tr>
                    <tr><td colspan="4" class="px-4 py-2 text-right text-gray-500">Pajak</td><td class="px-4 py-2 text-right text-gray-900 dark:text-white">{{ $fmt($sale->tax) }}</td></tr>
                    <tr><td colspan="4" class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">Total</td><td class="px-4 py-3 text-right text-lg font-bold text-emerald-600">{{ $fmt($sale->total) }}</td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div></div>
@endsection
