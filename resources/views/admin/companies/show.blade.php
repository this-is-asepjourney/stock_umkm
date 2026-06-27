@extends('admin.layouts.admin')

@section('title', 'Detail Klien')

@section('content')
<div class="space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.companies.index') }}" class="p-2 rounded-lg bg-white/5 text-gray-400 hover:text-white hover:bg-white/10 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-extrabold text-white">{{ $company->name }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">Detail klien dan data langganan</p>
        </div>
        <a href="{{ route('admin.companies.edit', $company->id) }}" class="admin-btn-primary">
            Edit Langganan
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 space-y-6">
            <div class="admin-card p-6">
                <h3 class="text-lg font-bold text-white mb-4">Informasi Klien</h3>
                <div class="space-y-4">
                    <div>
                        <div class="text-sm text-gray-500">Nama Perusahaan</div>
                        <div class="text-white font-medium">{{ $company->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Email</div>
                        <div class="text-white font-medium">{{ $company->email ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Telepon</div>
                        <div class="text-white font-medium">{{ $company->phone ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Alamat</div>
                        <div class="text-white font-medium">{{ $company->address ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="admin-card p-6">
                <h3 class="text-lg font-bold text-white mb-4">Informasi Langganan</h3>
                <div class="space-y-4">
                    <div>
                        <div class="text-sm text-gray-500">Plan Saat Ini</div>
                        <div class="text-white font-medium">{{ $company->plan ? $company->plan->name : 'Belum Ada' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Status Langganan</div>
                        <div class="mt-1">
                            @if($company->subscription_status == 'active')
                                <span class="admin-badge admin-badge-online">Aktif</span>
                            @elseif($company->subscription_status == 'trial')
                                <span class="admin-badge admin-badge-warning">Trial</span>
                            @elseif($company->subscription_status == 'inactive')
                                <span class="admin-badge admin-badge-offline">Tidak Aktif</span>
                            @else
                                <span class="admin-badge admin-badge-danger">Dibatalkan</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Berakhir Pada</div>
                        <div class="text-white font-medium">{{ $company->subscription_ends_at ? $company->subscription_ends_at->format('d F Y H:i') : '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-2 space-y-6">
            <div class="grid grid-cols-3 gap-4">
                <div class="admin-card p-4">
                    <div class="text-sm text-gray-500">Total User</div>
                    <div class="text-2xl font-bold text-white mt-1">{{ $company->users_count }}</div>
                </div>
                <div class="admin-card p-4">
                    <div class="text-sm text-gray-500">Total Produk</div>
                    <div class="text-2xl font-bold text-white mt-1">{{ collect($stats)->get('products', 0) }}</div>
                </div>
                <div class="admin-card p-4">
                    <div class="text-sm text-gray-500">Total Penjualan</div>
                    <div class="text-2xl font-bold text-white mt-1">{{ collect($stats)->get('sales', 0) }}</div>
                </div>
            </div>

            <div class="admin-card p-6">
                <h3 class="text-lg font-bold text-white mb-4">Anggota Perusahaan</h3>
                <div class="space-y-3">
                    @forelse($members as $u)
                        <div class="flex items-center gap-4 p-3 rounded-xl bg-white/5 border border-white/10">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('admin.users.show', $u->id) }}" class="text-sm font-semibold text-white hover:text-orange-400 transition">{{ $u->name }}</a>
                                <p class="text-[11px] text-gray-500 truncate">{{ $u->email }}</p>
                            </div>
                            <div>
                                @if(in_array($u->id, $onlineUserIds))
                                    <span class="admin-badge admin-badge-online">Online</span>
                                @else
                                    <span class="admin-badge admin-badge-offline">Offline</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Belum ada anggota di perusahaan ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
