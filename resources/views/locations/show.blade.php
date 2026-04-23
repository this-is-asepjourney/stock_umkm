@extends('layouts.app')
@section('title', 'Lokasi: ' . $location->name)
@section('content')
<div class="py-6"><div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header :title="$location->name" :subtitle="$location->full_path" :back="route('locations.index')">
        <x-slot name="actions">
            <a href="{{ route('locations.edit', $location) }}" class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">Edit</a>
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-3">
            <h3 class="font-semibold text-gray-900 dark:text-white">Info Lokasi</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-gray-500">Kode</dt><dd class="font-mono text-gray-900 dark:text-white">{{ $location->code }}</dd></div>
                <div><dt class="text-gray-500">Tipe</dt><dd><x-status-badge :color="['warehouse'=>'indigo','rack'=>'emerald','bin'=>'amber'][$location->type] ?? 'slate'" :label="$location->type_label" /></dd></div>
                <div><dt class="text-gray-500">Parent</dt><dd class="text-gray-900 dark:text-white">{{ $location->parent?->name ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Deskripsi</dt><dd class="text-gray-900 dark:text-white">{{ $location->description ?? '—' }}</dd></div>
            </dl>

            @if ($location->children->isNotEmpty())
                <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Sub-lokasi</h4>
                    <ul class="space-y-1 text-sm">
                        @foreach ($location->children as $c)
                            <li><a href="{{ route('locations.show', $c) }}" class="text-indigo-600 hover:underline">{{ $c->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Mutasi Stok Terbaru</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr><th class="px-4 py-2 text-left">Tanggal</th><th class="px-4 py-2 text-left">Produk</th><th class="px-4 py-2 text-left">Tipe</th><th class="px-4 py-2 text-right">Qty</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($stockMovements as $m)
                        <tr>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $m->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-2"><a href="{{ route('products.show', $m->product) }}" class="text-indigo-600 hover:underline">{{ $m->product?->name }}</a></td>
                            <td class="px-4 py-2"><x-status-badge :color="['IN'=>'emerald','OUT'=>'rose','ADJUSTMENT'=>'amber','OPNAME'=>'indigo'][$m->type] ?? 'slate'" :label="$m->type" /></td>
                            <td class="px-4 py-2 text-right font-semibold text-gray-900 dark:text-white">{{ $m->quantity }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Belum ada mutasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div></div>
@endsection
