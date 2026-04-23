@extends('layouts.app')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
@php
    $fmt = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $statusColorMap = ['normal' => 'emerald', 'low' => 'amber', 'out_of_stock' => 'rose'];
    $statusLabelMap = ['normal' => 'Normal', 'low' => 'Stok Rendah', 'out_of_stock' => 'Habis'];
    $status = $product->stock_status;
@endphp

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header :title="$product->name" :subtitle="'Kode: ' . $product->code" :back="route('products.index')">
            <x-slot name="actions">
                <a href="{{ route('products.edit', $product) }}"
                    class="px-4 py-2 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Edit</a>
                <x-delete-button :action="route('products.destroy', $product)"
                    confirm="Hapus produk ini? Aksi ini tidak bisa dibatalkan."
                    class="px-4 py-2 rounded-md bg-rose-600 text-white hover:bg-rose-700" label="Hapus" />
            </x-slot>
        </x-page-header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="flex items-start gap-6">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-32 h-32 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                        @else
                            <div class="w-32 h-32 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold">
                                {{ strtoupper(substr($product->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <x-status-badge :color="$statusColorMap[$status] ?? 'slate'" :label="$statusLabelMap[$status] ?? ucfirst($status)" />
                                @if ($product->is_active)
                                    <x-status-badge color="emerald" label="Aktif" />
                                @else
                                    <x-status-badge color="slate" label="Nonaktif" />
                                @endif
                                @if ($product->category)
                                    <x-status-badge color="indigo" :label="$product->category->name" />
                                @endif
                            </div>
                            <h3 class="mt-3 text-xl font-semibold text-gray-900 dark:text-white">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Barcode: {{ $product->barcode ?? '—' }}</p>
                            @if ($product->description)
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $product->description }}</p>
                            @endif
                        </div>
                    </div>

                    <dl class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <dt class="text-xs text-gray-500 dark:text-gray-400">Harga Modal</dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-white">{{ $fmt($product->cost_price) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 dark:text-gray-400">Harga Jual</dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-white">{{ $fmt($product->selling_price) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 dark:text-gray-400">Margin</dt>
                            <dd class="text-sm font-semibold text-emerald-600">
                                {{ $fmt($product->selling_price - $product->cost_price) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 dark:text-gray-400">Supplier</dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-white">{{ $product->supplier?->name ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-white">🔄 Riwayat Mutasi Stok</h3>
                        <a href="{{ route('movements.index') }}?product_id={{ $product->id }}"
                            class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Semua mutasi</a>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Tipe</th>
                                <th class="px-4 py-2 text-right">Qty</th>
                                <th class="px-4 py-2 text-right">Saldo</th>
                                <th class="px-4 py-2 text-left">User</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $colorMap = ['IN' => 'emerald', 'OUT' => 'rose', 'ADJUSTMENT' => 'amber', 'OPNAME' => 'indigo'];
                            @endphp
                            @forelse($movements as $m)
                                <tr>
                                    <td class="px-4 py-2 text-gray-700 dark:text-gray-200">{{ $m->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-2">
                                        <x-status-badge :color="$colorMap[$m->type] ?? 'slate'" :label="$m->type_label ?? $m->type" />
                                    </td>
                                    <td class="px-4 py-2 text-right font-semibold {{ $m->type === 'OUT' ? 'text-rose-600' : 'text-emerald-600' }}">
                                        {{ $m->type === 'OUT' ? '-' : '+' }}{{ $m->quantity }}
                                    </td>
                                    <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-200">{{ $m->quantity_after }}</td>
                                    <td class="px-4 py-2 text-gray-700 dark:text-gray-200">{{ $m->user?->name ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada mutasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">📊 Stok</h3>
                    <div class="text-center py-4">
                        <p class="text-5xl font-bold text-gray-900 dark:text-white">{{ $product->current_stock }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->unit?->name }}</p>
                    </div>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Stok Minimum</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $product->min_stock }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Stok Maksimum</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $product->max_stock }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Nilai Stok</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $fmt($product->current_stock * $product->cost_price) }}</dd>
                        </div>
                    </dl>
                </div>

                @if ($product->barcode)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">📇 Barcode</h3>
                        <div class="text-center">
                            @php
                                try {
                                    $generator = new \Milon\Barcode\DNS1D();
                                    $generator->setStorPath(storage_path('framework/cache/barcode/'));
                                    $barcodeHtml = $generator->getBarcodeHTML($product->barcode, 'C128', 2, 50);
                                } catch (\Throwable $e) {
                                    $barcodeHtml = '<div class="text-sm text-gray-500">Gagal render barcode</div>';
                                }
                            @endphp
                            {!! $barcodeHtml !!}
                            <p class="mt-2 text-sm font-mono text-gray-700 dark:text-gray-300">{{ $product->barcode }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
