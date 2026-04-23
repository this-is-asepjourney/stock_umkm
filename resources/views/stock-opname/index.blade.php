@extends('layouts.app')
@section('title', 'Stock Opname')
@section('content')
@php
    $statusColors = ['draft'=>'slate','in_progress'=>'amber','completed'=>'emerald','cancelled'=>'rose'];
@endphp
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header title="Stock Opname" subtitle="Sesi penghitungan fisik stok & penyesuaian">
            <x-slot name="actions">
                <a href="{{ route('stock-opname.create') }}" class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">+ Buat Opname</a>
            </x-slot>
        </x-page-header>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            @if ($opnames->isEmpty())
                <x-empty-state title="Belum ada sesi opname" message="Mulai sesi stock opname pertama." :action="route('stock-opname.create')" actionLabel="Buat Opname" />
            @else
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3 text-left">Nomor</th>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-left">Kategori</th>
                            <th class="px-6 py-3 text-left">Lokasi</th>
                            <th class="px-6 py-3 text-right">Item</th>
                            <th class="px-6 py-3 text-right">Selisih</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($opnames as $opn)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                <td class="px-6 py-3 font-mono text-sm text-gray-900 dark:text-white">{{ $opn->opname_number }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300">{{ optional($opn->opname_date)->format('d M Y') }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $opn->category?->name ?? 'Semua' }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $opn->location?->name ?? '—' }}</td>
                                <td class="px-6 py-3 text-right text-sm text-gray-900 dark:text-white">{{ $opn->total_items }}</td>
                                <td class="px-6 py-3 text-right text-sm {{ $opn->total_discrepancy == 0 ? 'text-gray-500' : ($opn->total_discrepancy > 0 ? 'text-emerald-600' : 'text-rose-600') }}">
                                    {{ $opn->total_discrepancy > 0 ? '+' : '' }}{{ $opn->total_discrepancy }}
                                </td>
                                <td class="px-6 py-3 text-center"><x-status-badge :color="$statusColors[$opn->status] ?? 'slate'" :label="ucfirst(str_replace('_',' ',$opn->status))" /></td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('stock-opname.show', $opn) }}" class="text-indigo-600 hover:underline text-sm">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $opnames->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
