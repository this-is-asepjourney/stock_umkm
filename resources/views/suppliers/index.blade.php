@extends('layouts.app')

@section('title', 'Supplier')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header title="Supplier" subtitle="Kelola daftar supplier Anda">
            <x-slot name="actions">
                <a href="{{ route('suppliers.create') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    + Tambah Supplier
                </a>
            </x-slot>
        </x-page-header>

        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg">
            @if ($suppliers->isEmpty())
                <x-empty-state title="Belum ada supplier" message="Tambahkan supplier untuk mulai memproses pembelian." :action="route('suppliers.create')" actionLabel="Tambah Supplier" />
            @else
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3 text-left">Kode</th>
                            <th class="px-6 py-3 text-left">Nama</th>
                            <th class="px-6 py-3 text-left">Kontak</th>
                            <th class="px-6 py-3 text-right">Produk</th>
                            <th class="px-6 py-3 text-right">PO</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($suppliers as $s)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                <td class="px-6 py-3 font-mono text-xs text-gray-600 dark:text-gray-300">{{ $s->code }}</td>
                                <td class="px-6 py-3">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $s->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $s->contact_person ?? '' }}</div>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    <div>{{ $s->phone ?? '—' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $s->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-3 text-right text-gray-700 dark:text-gray-200">{{ $s->products_count }}</td>
                                <td class="px-6 py-3 text-right text-gray-700 dark:text-gray-200">{{ $s->purchase_orders_count }}</td>
                                <td class="px-6 py-3">
                                    @if ($s->is_active)
                                        <x-status-badge color="emerald" label="Aktif" />
                                    @else
                                        <x-status-badge color="slate" label="Nonaktif" />
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('suppliers.show', $s) }}" class="text-indigo-600 hover:underline text-sm">Detail</a>
                                    <a href="{{ route('suppliers.edit', $s) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                    <x-delete-button :action="route('suppliers.destroy', $s)" confirm="Hapus supplier {{ $s->name }}?" class="text-sm" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
