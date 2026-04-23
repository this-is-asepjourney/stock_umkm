@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Manajemen Kategori
                    </h2>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('categories.create') }}"
                        class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Tambah Kategori
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Parent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Jumlah Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($categories as $category)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 rounded mr-3" style="background-color: {{ $category->color }}">
                                        </div>
                                        <span class="text-gray-900 dark:text-white">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                    {{ $category->parent->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                    {{ $category->products_count }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('categories.show', $category) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                                    <a href="{{ route('categories.edit', $category) }}"
                                        class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada kategori
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($categories->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection