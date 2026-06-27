@extends('admin.layouts.admin')

@section('title', 'Kelola Plan')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white">📦 Kelola Plan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftar semua paket langganan (SaaS)</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.plans.create') }}" class="admin-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Plan
            </a>
        </div>
    </div>

    {{-- Plans Table --}}
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full admin-table">
                <thead>
                    <tr>
                        <th class="text-left">Nama Plan</th>
                        <th class="text-left">Harga</th>
                        <th class="text-left">Durasi</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                        <tr>
                            <td>
                                <div class="font-semibold text-white">{{ $plan->name }}</div>
                                <div class="text-[11px] text-gray-500">Maks User: {{ $plan->max_users ?? 'Unlimited' }}, Maks Produk: {{ $plan->max_products ?? 'Unlimited' }}</div>
                            </td>
                            <td class="text-gray-300">
                                Rp {{ number_format($plan->price, 0, ',', '.') }}
                            </td>
                            <td class="text-gray-400">
                                {{ $plan->duration_in_days }} Hari
                            </td>
                            <td class="text-center">
                                @if($plan->is_active)
                                    <span class="admin-badge admin-badge-online">Aktif</span>
                                @else
                                    <span class="admin-badge admin-badge-offline">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.plans.edit', $plan->id) }}" class="p-2 rounded-lg hover:bg-white/5 text-gray-400 hover:text-orange-400 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="text-3xl mb-2">📦</div>
                                <p class="text-gray-500">Belum ada plan yang dibuat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
