@extends('layouts.app')
@section('title', 'Lokasi / Gudang')
@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header title="Lokasi / Gudang" subtitle="Kelola hirarki gudang, rak, dan bin">
            <x-slot name="actions">
                <a href="{{ route('locations.create') }}" class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">+ Tambah Lokasi</a>
            </x-slot>
        </x-page-header>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            @if ($locations->isEmpty())
                <x-empty-state title="Belum ada lokasi" message="Tambahkan gudang / rak / bin untuk mengorganisir stok." :action="route('locations.create')" />
            @else
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3 text-left">Nama</th>
                            <th class="px-6 py-3 text-left">Kode</th>
                            <th class="px-6 py-3 text-left">Tipe</th>
                            <th class="px-6 py-3 text-left">Parent</th>
                            <th class="px-6 py-3 text-right">Sub-lokasi</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($locations as $loc)
                            <tr>
                                <td class="px-6 py-3 text-gray-900 dark:text-white font-medium">{{ $loc->name }}</td>
                                <td class="px-6 py-3 font-mono text-xs text-gray-600 dark:text-gray-300">{{ $loc->code }}</td>
                                <td class="px-6 py-3">
                                    <x-status-badge :color="['warehouse'=>'indigo','rack'=>'emerald','bin'=>'amber'][$loc->type] ?? 'slate'" :label="$loc->type_label" />
                                </td>
                                <td class="px-6 py-3 text-gray-700 dark:text-gray-300">{{ $loc->parent?->name ?? '—' }}</td>
                                <td class="px-6 py-3 text-right text-gray-700 dark:text-gray-200">{{ $loc->children_count }}</td>
                                <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('locations.show', $loc) }}" class="text-indigo-600 hover:underline text-sm">Detail</a>
                                    <a href="{{ route('locations.edit', $loc) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                    <x-delete-button :action="route('locations.destroy', $loc)" confirm="Hapus lokasi {{ $loc->name }}?" class="text-sm" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $locations->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
