@extends('layouts.app')

@section('title', 'Manajemen Produk')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                        Manajemen Produk
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kelola semua produk, stok, dan harga
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('products.create') }}"
                        class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Produk
                    </a>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="col-span-1 md:col-span-2">
                            <input type="text" placeholder="Cari produk berdasarkan nama, SKU, atau barcode..."
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <select
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Semua Kategori</option>
                                @foreach(App\Models\Category::where('is_active', true)->get() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Status Stok</option>
                                <option value="normal">Normal</option>
                                <option value="low">Stok Rendah</option>
                                <option value="out_of_stock">Habis</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Produk
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                SKU/Barcode
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Harga
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Stok
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($products ?? [] as $product)
                            @php /** @var \App\Models\Product $product */ @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($product->image)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                    src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold">
                                                    {{ substr($product->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $product->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $product->supplier->name ?? 'No Supplier' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $product->code }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $product->barcode ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->category)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            style="background-color: {{ $product->category->color }}20; color: {{ $product->category->color }}">
                                            {{ $product->category->name }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        Rp {{ number_format((float) $product->selling_price, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Modal: Rp {{ number_format((float) $product->cost_price, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $product->current_stock }} {{ $product->unit->symbol }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Min: {{ $product->min_stock }} | Max: {{ $product->max_stock }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $status = $product->stock_status;
                                        $statusColors = [
                                            'normal' => 'green',
                                            'low' => 'yellow',
                                            'out_of_stock' => 'red'
                                        ];
                                        $statusLabels = [
                                            'normal' => 'Normal',
                                            'low' => 'Stok Rendah',
                                            'out_of_stock' => 'Habis'
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $statusColors[$status] }}-100 text-{{ $statusColors[$status] }}-800 dark:bg-{{ $statusColors[$status] }}-900 dark:text-{{ $statusColors[$status] }}-200">
                                        {{ $statusLabels[$status] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('products.show', $product) }}"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                        Detail
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                        Edit
                                    </a>
                                    <button onclick="confirmDelete({{ $product->id }})"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada produk</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan produk
                                        baru.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('products.create') }}"
                                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Tambah Produk
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if(isset($products) && method_exists($products, 'links'))
                    <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(id) {
                if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/products/${id}`;
                    form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    @endpush
@endsection