@extends('admin.layouts.admin')

@section('title', 'Kelola Client (Perusahaan)')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white">🏢 Kelola Klien</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftar semua klien (perusahaan) yang terdaftar</p>
        </div>
    </div>

    {{-- Companies Table --}}
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full admin-table">
                <thead>
                    <tr>
                        <th class="text-left">Klien</th>
                        <th class="text-left">Kontak</th>
                        <th class="text-left">Plan</th>
                        <th class="text-center">Status Berlangganan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td>
                                <div class="font-semibold text-white">{{ $company->name }}</div>
                                <div class="text-[11px] text-gray-500">{{ $company->users_count }} Pengguna</div>
                            </td>
                            <td>
                                <div class="text-sm text-gray-300">{{ $company->email ?? '-' }}</div>
                                <div class="text-[11px] text-gray-500">{{ $company->phone ?? '-' }}</div>
                            </td>
                            <td class="text-sm text-gray-400">
                                {{ $company->plan ? $company->plan->name : 'Tidak Ada Plan' }}
                            </td>
                            <td class="text-center">
                                @if($company->subscription_status == 'active')
                                    <span class="admin-badge admin-badge-online">Aktif</span>
                                @elseif($company->subscription_status == 'trial')
                                    <span class="admin-badge admin-badge-warning">Trial</span>
                                @elseif($company->subscription_status == 'inactive')
                                    <span class="admin-badge admin-badge-offline">Tidak Aktif</span>
                                @else
                                    <span class="admin-badge admin-badge-danger">Dibatalkan</span>
                                @endif
                                <div class="text-[11px] mt-1 text-gray-500">
                                    {{ $company->subscription_ends_at ? $company->subscription_ends_at->format('d M Y') : '-' }}
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.companies.show', $company->id) }}" class="p-2 rounded-lg hover:bg-white/5 text-gray-400 hover:text-white transition" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.companies.edit', $company->id) }}" class="p-2 rounded-lg hover:bg-white/5 text-gray-400 hover:text-orange-400 transition" title="Edit Langganan">
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
                                <div class="text-3xl mb-2">🏢</div>
                                <p class="text-gray-500">Belum ada klien (perusahaan).</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($companies->hasPages())
        <div class="flex justify-center">
            <div class="[&_.pagination]:flex [&_.pagination]:gap-1 [&_a]:px-3 [&_a]:py-1.5 [&_a]:rounded-lg [&_a]:text-sm [&_a]:text-gray-400 [&_a]:bg-white/5 [&_a:hover]:bg-orange-500/20 [&_a:hover]:text-orange-400 [&_.active_span]:bg-orange-500/20 [&_.active_span]:text-orange-400 [&_span]:px-3 [&_span]:py-1.5 [&_span]:rounded-lg [&_span]:text-sm [&_span]:text-gray-600">
                {{ $companies->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
