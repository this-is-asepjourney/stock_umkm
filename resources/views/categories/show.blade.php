@extends('layouts.app')

@section('title', 'Detail Kategori')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        @if($category->icon)
                            <span class="text-3xl mr-3">{{ $category->icon }}</span>
                        @endif
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $category->name }}
                            </h2>
                            <div class="mt-1 flex items-center">
                                <span
                                    class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                @if($category->parent)
                                    <span class="ml-3 text-sm text-gray-500 dark:text-gray-400">
                                        Sub dari: {{ $category->parent->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('categories.edit', $category) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Edit
                    </a>
                    <a href="{{ route('categories.index') }}"
                        class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 dark:bg-indigo-900 rounded-lg p-3">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Produk</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $products->total() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Produk</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    Produk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    SKU</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    Stok</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    Harga</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $product->code }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $product->current_stock }}
                                            {{ $product->unit->symbol }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">Rp
                                            {{ number_format($product->selling_price, 0, ',', '.') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada produk dalam kategori ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection