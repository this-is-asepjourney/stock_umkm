@extends('layouts.app')
@section('title', 'PO ' . $purchase->po_number)
@section('content')
@php
    $fmt = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $statusColors = ['draft'=>'slate','ordered'=>'blue','partial'=>'amber','received'=>'emerald','cancelled'=>'rose'];
@endphp
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header :title="$purchase->po_number" :subtitle="'Status: ' . ucfirst($purchase->status)" :back="route('purchases.index')">
            <x-slot name="actions">
                <a href="{{ route('purchases.print', $purchase) }}" target="_blank"
                    class="px-4 py-2 rounded-md text-sm text-white bg-slate-600 hover:bg-slate-700">🖨️ Cetak</a>

                @if ($purchase->status === 'draft')
                    <a href="{{ route('purchases.edit', $purchase) }}"
                        class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">Edit</a>
                    <form method="POST" action="{{ route('purchases.submit', $purchase) }}" class="inline">
                        @csrf
                        <button class="px-4 py-2 rounded-md text-sm text-white bg-blue-600 hover:bg-blue-700">Submit ke Supplier</button>
                    </form>
                @endif

                @if (in_array($purchase->status, ['ordered','partial']))
                    <button type="button"
                        onclick="document.getElementById('receive-modal').classList.remove('hidden')"
                        class="px-4 py-2 rounded-md text-sm text-white bg-emerald-600 hover:bg-emerald-700">Terima Barang</button>
                @endif

                @if (in_array($purchase->status, ['draft','ordered']))
                    <form method="POST" action="{{ route('purchases.cancel', $purchase) }}" class="inline"
                        onsubmit="return confirm('Batalkan PO ini?');">
                        @csrf
                        <button class="px-4 py-2 rounded-md text-sm text-white bg-rose-600 hover:bg-rose-700">Batalkan</button>
                    </form>
                @endif
            </x-slot>
        </x-page-header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Info</h3>
                <dl class="space-y-2 text-sm">
                    <div><dt class="text-gray-500">Supplier</dt><dd class="text-gray-900 dark:text-white">{{ $purchase->supplier?->name }}</dd></div>
                    <div><dt class="text-gray-500">Lokasi</dt><dd class="text-gray-900 dark:text-white">{{ $purchase->location?->name ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500">Tanggal PO</dt><dd class="text-gray-900 dark:text-white">{{ optional($purchase->po_date)->format('d M Y') }}</dd></div>
                    <div><dt class="text-gray-500">Estimasi</dt><dd class="text-gray-900 dark:text-white">{{ optional($purchase->expected_date)->format('d M Y') ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500">Dibuat oleh</dt><dd class="text-gray-900 dark:text-white">{{ $purchase->user?->name ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500">Status</dt><dd><x-status-badge :color="$statusColors[$purchase->status] ?? 'slate'" :label="ucfirst($purchase->status)" /></dd></div>
                    @if ($purchase->notes)
                        <div><dt class="text-gray-500">Catatan</dt><dd class="text-gray-900 dark:text-white">{{ $purchase->notes }}</dd></div>
                    @endif
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
                            <th class="px-4 py-2 text-right">Qty Pesan</th>
                            <th class="px-4 py-2 text-right">Qty Terima</th>
                            <th class="px-4 py-2 text-right">Harga</th>
                            <th class="px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($purchase->items as $item)
                            <tr>
                                <td class="px-4 py-2">
                                    <a href="{{ route('products.show', $item->product) }}" class="text-indigo-600 hover:underline">{{ $item->product?->name }}</a>
                                    <div class="text-xs text-gray-500">{{ $item->product?->code }}</div>
                                </td>
                                <td class="px-4 py-2 text-right text-gray-900 dark:text-white">{{ $item->quantity_ordered }}</td>
                                <td class="px-4 py-2 text-right">
                                    <span class="{{ $item->quantity_received >= $item->quantity_ordered ? 'text-emerald-600' : 'text-amber-600' }}">{{ $item->quantity_received }}</span>
                                </td>
                                <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-300">{{ $fmt($item->unit_price) }}</td>
                                <td class="px-4 py-2 text-right font-semibold text-gray-900 dark:text-white">{{ $fmt($item->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700/30">
                        <tr><td colspan="4" class="px-4 py-2 text-right text-gray-500">Subtotal</td><td class="px-4 py-2 text-right text-gray-900 dark:text-white">{{ $fmt($purchase->subtotal) }}</td></tr>
                        <tr><td colspan="4" class="px-4 py-2 text-right text-gray-500">Diskon</td><td class="px-4 py-2 text-right text-gray-900 dark:text-white">-{{ $fmt($purchase->discount) }}</td></tr>
                        <tr><td colspan="4" class="px-4 py-2 text-right text-gray-500">Pajak</td><td class="px-4 py-2 text-right text-gray-900 dark:text-white">{{ $fmt($purchase->tax) }}</td></tr>
                        <tr><td colspan="4" class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">Total</td><td class="px-4 py-3 text-right text-lg font-bold text-indigo-600">{{ $fmt($purchase->total) }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @if (in_array($purchase->status, ['ordered','partial']))
        <div id="receive-modal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Terima Barang</h3>
                    <button onclick="document.getElementById('receive-modal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <form method="POST" action="{{ route('purchases.receive', $purchase) }}" class="p-4 space-y-3">
                    @csrf
                    <p class="text-sm text-gray-600 dark:text-gray-300">Masukkan jumlah barang yang diterima (maksimal sama dengan sisa).</p>
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500">
                            <tr><th class="px-2 py-2 text-left">Produk</th><th class="px-2 py-2 text-right">Sisa</th><th class="px-2 py-2 text-right">Diterima Sekarang</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($purchase->items as $item)
                                @php $sisa = $item->quantity_ordered - $item->quantity_received; @endphp
                                <tr>
                                    <td class="px-2 py-2 text-gray-900 dark:text-white">{{ $item->product?->name }}</td>
                                    <td class="px-2 py-2 text-right">{{ $sisa }}</td>
                                    <td class="px-2 py-2">
                                        <input type="number" min="0" max="{{ $sisa }}" name="items[{{ $item->id }}]" value="{{ $sisa }}"
                                            class="w-full text-right rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="document.getElementById('receive-modal').classList.add('hidden')"
                            class="px-4 py-2 rounded-md text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Batal</button>
                        <button class="px-4 py-2 rounded-md text-sm text-white bg-emerald-600 hover:bg-emerald-700">Terima</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
