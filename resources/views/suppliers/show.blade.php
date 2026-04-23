@extends('layouts.app')

@section('title', 'Supplier: ' . $supplier->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header :title="$supplier->name" :subtitle="$supplier->code" :back="route('suppliers.index')">
            <x-slot name="actions">
                <a href="{{ route('suppliers.edit', $supplier) }}"
                    class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">Edit</a>
                <a href="{{ route('purchases.create') }}?supplier_id={{ $supplier->id }}"
                    class="px-4 py-2 rounded-md text-sm text-white bg-emerald-600 hover:bg-emerald-700">Buat PO</a>
            </x-slot>
        </x-page-header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-3">
                <h3 class="font-semibold text-gray-900 dark:text-white">Informasi Kontak</h3>
                <dl class="space-y-2 text-sm">
                    <div><dt class="text-gray-500 dark:text-gray-400">Kontak</dt><dd class="text-gray-900 dark:text-white">{{ $supplier->contact_person ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500 dark:text-gray-400">Telepon</dt><dd class="text-gray-900 dark:text-white">{{ $supplier->phone ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500 dark:text-gray-400">Email</dt><dd class="text-gray-900 dark:text-white">{{ $supplier->email ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500 dark:text-gray-400">Alamat</dt><dd class="text-gray-900 dark:text-white">{{ $supplier->address ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500 dark:text-gray-400">Termin</dt><dd class="text-gray-900 dark:text-white">{{ $supplier->payment_term ?? '—' }}</dd></div>
                </dl>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Purchase Order Terbaru</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-2 text-left">Nomor</th>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($purchaseOrders as $po)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="px-4 py-2"><a href="{{ route('purchases.show', $po) }}" class="text-indigo-600 hover:underline">{{ $po->po_number }}</a></td>
                                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ optional($po->po_date)->format('d M Y') }}</td>
                                    <td class="px-4 py-2"><x-status-badge :color="['draft'=>'slate','ordered'=>'blue','partial'=>'amber','received'=>'emerald','cancelled'=>'rose'][$po->status] ?? 'slate'" :label="ucfirst($po->status)" /></td>
                                    <td class="px-4 py-2 text-right text-gray-900 dark:text-white">Rp {{ number_format((float) $po->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Belum ada PO.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Produk Terkait</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                            <tr><th class="px-4 py-2 text-left">Produk</th><th class="px-4 py-2 text-right">Stok</th><th class="px-4 py-2 text-right">Harga Modal</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($products as $p)
                                <tr>
                                    <td class="px-4 py-2"><a href="{{ route('products.show', $p) }}" class="text-indigo-600 hover:underline">{{ $p->name }}</a></td>
                                    <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-200">{{ $p->current_stock }}</td>
                                    <td class="px-4 py-2 text-right text-gray-900 dark:text-white">Rp {{ number_format((float) $p->cost_price, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Tidak ada produk.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
