@extends('layouts.app')
@section('title', 'Satuan')
@section('content')
<div class="py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header title="Satuan" subtitle="Unit satuan barang (pcs, kg, liter, dst.)">
            <x-slot name="actions">
                <a href="{{ route('units.create') }}"
                    class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">+ Tambah Satuan</a>
            </x-slot>
        </x-page-header>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            @if ($units->isEmpty())
                <x-empty-state title="Belum ada satuan" message="Tambahkan satuan untuk mulai mengelola produk." :action="route('units.create')" />
            @else
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3 text-left">Nama</th>
                            <th class="px-6 py-3 text-left">Simbol</th>
                            <th class="px-6 py-3 text-right">Produk</th>
                            <th class="px-6 py-3 text-left">Deskripsi</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($units as $u)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                <td class="px-6 py-3 text-gray-900 dark:text-white font-medium">{{ $u->name }}</td>
                                <td class="px-6 py-3">
                                    <x-status-badge color="indigo" :label="$u->symbol" />
                                </td>
                                <td class="px-6 py-3 text-right text-gray-700 dark:text-gray-200">{{ $u->products_count }}</td>
                                <td class="px-6 py-3 text-gray-500 dark:text-gray-400 text-sm">{{ $u->description ?? '—' }}</td>
                                <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('units.show', $u) }}" class="text-indigo-600 hover:underline text-sm">Detail</a>
                                    <a href="{{ route('units.edit', $u) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                    <x-delete-button :action="route('units.destroy', $u)" confirm="Hapus satuan {{ $u->name }}?" class="text-sm" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $units->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
