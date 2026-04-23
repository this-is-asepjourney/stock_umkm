@extends('layouts.app')
@section('title', 'Laporan Mutasi')
@section('content')
<div class="py-6"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header title="Laporan Mutasi Stok" subtitle="Ringkasan IN / OUT / ADJUSTMENT">
        <x-slot name="actions">
            <a href="{{ route('reports.export', 'movement') }}" class="px-4 py-2 rounded-md text-sm text-white bg-slate-600 hover:bg-slate-700">Export</a>
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-stat-card label="Total Masuk" :value="number_format((int)$summary['total_in'])" color="emerald" icon="⬇️" />
        <x-stat-card label="Total Keluar" :value="number_format((int)$summary['total_out'])" color="rose" icon="⬆️" />
        <x-stat-card label="Total Penyesuaian" :value="number_format((int)$summary['total_adjustment'])" color="amber" icon="⚖️" />
    </div>

    <form method="GET" class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <select name="product_id" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Semua Produk</option>
                @foreach ($products as $p)
                    <option value="{{ $p->id }}" @selected(request('product_id') == $p->id)>{{ $p->name }}</option>
                @endforeach
            </select>
            <select name="type" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Semua Tipe</option>
                @foreach (\App\Enums\MovementType::options() as $opt)
                    <option value="{{ $opt['value'] }}" @selected(request('type') === $opt['value'])>{{ $opt['label'] }}</option>
                @endforeach
            </select>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <button class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm">Filter</button>
        </div>
    </form>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Produk</th>
                    <th class="px-4 py-3 text-center">Tipe</th>
                    <th class="px-4 py-3 text-right">Qty</th>
                    <th class="px-4 py-3 text-right">Stok Akhir</th>
                    <th class="px-4 py-3 text-left">Catatan</th>
                    <th class="px-4 py-3 text-left">Oleh</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($movements as $m)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $m->product?->name }}</td>
                        <td class="px-4 py-2 text-center"><x-status-badge :color="$m->type_color" :label="$m->type_label" /></td>
                        <td class="px-4 py-2 text-right font-semibold {{ $m->quantity > 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}</td>
                        <td class="px-4 py-2 text-right text-gray-900 dark:text-white">{{ $m->quantity_after }}</td>
                        <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-xs">{{ $m->notes }}</td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $m->user?->name }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $movements->links() }}</div>
    </div>
</div></div>
@endsection
