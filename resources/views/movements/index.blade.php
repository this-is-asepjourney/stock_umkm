@extends('layouts.app')
@section('title', 'Mutasi Stok')
@section('content')
@php
    $fmt = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
@endphp
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header title="Mutasi Stok / Kartu Stok" subtitle="Riwayat pergerakan stok per produk" />

        <form method="GET" class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <select name="product_id" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Produk</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}" @selected(request('product_id') == $p->id)>{{ $p->name }} ({{ $p->code }})</option>
                    @endforeach
                </select>
                <select name="type" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Tipe</option>
                    @foreach (\App\Enums\MovementType::options() as $opt)
                        <option value="{{ $opt['value'] }}" @selected(request('type') === $opt['value'])>{{ $opt['label'] }}</option>
                    @endforeach
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <button class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm">Filter</button>
            </div>
        </form>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            @if ($movements->isEmpty())
                <x-empty-state title="Belum ada mutasi stok" message="Belum ada pergerakan stok tercatat." />
            @else
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Produk</th>
                            <th class="px-4 py-3 text-center">Tipe</th>
                            <th class="px-4 py-3 text-right">Sebelum</th>
                            <th class="px-4 py-3 text-right">Qty</th>
                            <th class="px-4 py-3 text-right">Sesudah</th>
                            <th class="px-4 py-3 text-left">Referensi</th>
                            <th class="px-4 py-3 text-left">Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($movements as $m)
                            @php $tc = $m->type_color; @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                <td class="px-4 py-2 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $m->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('products.show', $m->product) }}" class="text-indigo-600 hover:underline">{{ $m->product?->name }}</a>
                                    <div class="text-xs text-gray-500">{{ $m->product?->code }}</div>
                                </td>
                                <td class="px-4 py-2 text-center"><x-status-badge :color="$tc" :label="$m->type_label" /></td>
                                <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-300">{{ $m->quantity_before }}</td>
                                <td class="px-4 py-2 text-right font-semibold {{ $m->type === 'IN' || ($m->type === 'ADJUSTMENT' && $m->quantity > 0) ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}
                                </td>
                                <td class="px-4 py-2 text-right text-gray-900 dark:text-white font-semibold">{{ $m->quantity_after }}</td>
                                <td class="px-4 py-2 text-gray-700 dark:text-gray-300 text-xs">
                                    {{ class_basename($m->reference_type ?? '-') }}
                                    @if ($m->reference_id) #{{ $m->reference_id }} @endif
                                    @if ($m->notes)
                                        <div class="text-gray-500">{{ $m->notes }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $m->user?->name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $movements->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
