@extends('layouts.app')
@section('title', 'Satuan: ' . $unit->name)
@section('content')
<div class="py-6"><div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header :title="$unit->name . ' (' . $unit->symbol . ')'" :subtitle="$unit->description" :back="route('units.index')">
        <x-slot name="actions">
            <a href="{{ route('units.edit', $unit) }}" class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">Edit</a>
        </x-slot>
    </x-page-header>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white">Produk dengan satuan ini</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                <tr><th class="px-4 py-2 text-left">Produk</th><th class="px-4 py-2 text-right">Stok</th><th class="px-4 py-2 text-right">Harga Jual</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($products as $p)
                    <tr>
                        <td class="px-4 py-2"><a href="{{ route('products.show', $p) }}" class="text-indigo-600 hover:underline">{{ $p->name }}</a></td>
                        <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-200">{{ $p->current_stock }} {{ $unit->symbol }}</td>
                        <td class="px-4 py-2 text-right text-gray-900 dark:text-white">Rp {{ number_format((float) $p->selling_price, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Tidak ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($products->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $products->links() }}</div>
        @endif
    </div>
</div></div>
@endsection
