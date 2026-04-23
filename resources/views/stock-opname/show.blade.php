@extends('layouts.app')
@section('title', 'Opname ' . $opname->opname_number)
@section('content')
@php
    $fmt = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $statusColors = ['draft'=>'slate','in_progress'=>'amber','completed'=>'emerald','cancelled'=>'rose'];
    $progressCounted = $opname->items()->where('is_counted', true)->count();
    $progressTotal = max(1, $opname->items()->count());
    $progressPct = round(($progressCounted / $progressTotal) * 100);
@endphp
<div class="py-6"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header :title="$opname->opname_number" :subtitle="'Tanggal: ' . optional($opname->opname_date)->format('d M Y')" :back="route('stock-opname.index')">
        <x-slot name="actions">
            @if ($opname->status === 'draft')
                <a href="{{ route('stock-opname.edit', $opname) }}" class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">Edit</a>
                <form method="POST" action="{{ route('stock-opname.start', $opname) }}" class="inline" onsubmit="return confirm('Mulai sesi opname? Tidak dapat diedit lagi setelah dimulai.');">
                    @csrf
                    <button class="px-4 py-2 rounded-md text-sm text-white bg-amber-600 hover:bg-amber-700">▶ Mulai</button>
                </form>
            @endif
            @if ($opname->status === 'in_progress')
                <form method="POST" action="{{ route('stock-opname.complete', $opname) }}" class="inline" onsubmit="return confirm('Selesaikan opname? Semua item harus sudah dihitung.');">
                    @csrf
                    <button class="px-4 py-2 rounded-md text-sm text-white bg-emerald-600 hover:bg-emerald-700">✓ Selesaikan</button>
                </form>
            @endif
            @if ($opname->status === 'completed')
                <form method="POST" action="{{ route('stock-opname.apply', $opname) }}" class="inline" onsubmit="return confirm('Terapkan penyesuaian stok sesuai hasil opname?');">
                    @csrf
                    <button class="px-4 py-2 rounded-md text-sm text-white bg-purple-600 hover:bg-purple-700">Apply Adjustment</button>
                </form>
            @endif
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Status" :value="ucfirst(str_replace('_',' ',$opname->status))" color="indigo" icon="📋">
            <x-slot name="hint">{{ optional($opname->started_at)->format('d M H:i') ?? 'Belum dimulai' }}</x-slot>
        </x-stat-card>
        <x-stat-card label="Total Item" :value="$opname->items()->count()" color="slate" icon="📦">
            <x-slot name="hint">{{ $progressCounted }} sudah dihitung</x-slot>
        </x-stat-card>
        <x-stat-card label="Selisih Qty" :value="$opname->total_discrepancy" :color="$opname->total_discrepancy == 0 ? 'emerald' : ($opname->total_discrepancy > 0 ? 'amber' : 'rose')" icon="⚖️" />
        <x-stat-card label="Nilai Selisih" :value="$fmt($opname->total_discrepancy_value)" :color="$opname->total_discrepancy_value == 0 ? 'emerald' : 'rose'" icon="💰" />
    </div>

    @if ($opname->status === 'in_progress')
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">Progress Hitung</span>
                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $progressCounted }}/{{ $progressTotal }} ({{ $progressPct }}%)</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full transition-all" style="width: {{ $progressPct }}%"></div>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900 dark:text-white">Daftar Item</h3>
            <div class="text-xs text-gray-500">Catatan: {{ $opname->notes ?? '—' }}</div>
        </div>
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-2 text-left">Produk</th>
                    <th class="px-4 py-2 text-right">Sistem</th>
                    <th class="px-4 py-2 text-right">Fisik</th>
                    <th class="px-4 py-2 text-right">Selisih</th>
                    <th class="px-4 py-2 text-right">Nilai Selisih</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    @if ($opname->status === 'in_progress')
                        <th class="px-4 py-2 text-right">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($items as $item)
                    <tr x-data="{ editing: false }" class="{{ $item->is_counted ? '' : 'bg-amber-50/50 dark:bg-amber-900/10' }}">
                        <td class="px-4 py-2">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $item->product?->name }}</div>
                            <div class="text-xs text-gray-500">{{ $item->product?->code }} · {{ $item->product?->category?->name }}</div>
                        </td>
                        <td class="px-4 py-2 text-right text-gray-900 dark:text-white">{{ $item->system_qty }} {{ $item->product?->unit?->symbol }}</td>
                        <td class="px-4 py-2 text-right">
                            @if ($item->is_counted)
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $item->physical_qty }}</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right font-semibold {{ !$item->is_counted ? 'text-gray-400' : ($item->discrepancy == 0 ? 'text-emerald-600' : ($item->discrepancy > 0 ? 'text-amber-600' : 'text-rose-600')) }}">
                            @if ($item->is_counted)
                                {{ $item->discrepancy > 0 ? '+' : '' }}{{ $item->discrepancy }}
                            @else — @endif
                        </td>
                        <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-300">{{ $item->is_counted ? $fmt($item->discrepancy_value) : '—' }}</td>
                        <td class="px-4 py-2 text-center">
                            @if ($item->is_counted)
                                <x-status-badge color="emerald" label="Counted" />
                            @else
                                <x-status-badge color="amber" label="Pending" />
                            @endif
                        </td>
                        @if ($opname->status === 'in_progress')
                            <td class="px-4 py-2 text-right">
                                <button type="button" @click="editing = !editing"
                                    class="text-indigo-600 hover:underline text-xs">{{ $item->is_counted ? 'Update' : 'Hitung' }}</button>
                            </td>
                        @endif
                    </tr>
                    @if ($opname->status === 'in_progress')
                        <tr x-show="editing" x-cloak>
                            <td colspan="7" class="px-4 py-3 bg-indigo-50 dark:bg-indigo-900/20">
                                <form method="POST" action="{{ route('stock-opname.count', [$opname, $item]) }}" class="flex flex-wrap items-end gap-3">
                                    @csrf
                                    <div>
                                        <label class="block text-xs text-gray-600 dark:text-gray-400">Qty Fisik</label>
                                        <input type="number" name="physical_qty" min="0" step="1" value="{{ $item->physical_qty ?? $item->system_qty }}" required
                                            class="w-32 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div class="flex-1 min-w-[200px]">
                                        <label class="block text-xs text-gray-600 dark:text-gray-400">Catatan</label>
                                        <input type="text" name="notes" value="{{ $item->notes }}"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <button class="px-3 py-1.5 rounded-md text-xs text-white bg-emerald-600 hover:bg-emerald-700">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $items->links() }}</div>
    </div>
</div></div>
@endsection
