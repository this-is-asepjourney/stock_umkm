@extends('admin.layouts.admin')

@section('title', 'Kelola User')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white">👥 Kelola User</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftar semua user dari seluruh perusahaan</p>
        </div>
        <div class="text-sm text-gray-400">
            Total: <span class="font-bold text-white">{{ $users->total() }}</span> user
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="admin-card p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="admin-input">
            </div>
            <div class="w-full sm:w-56">
                <select name="company_id" class="admin-input" style="appearance: auto;">
                    <option value="">Semua Perusahaan</option>
                    @foreach($companies as $c)
                        <option value="{{ $c->id }}" {{ request('company_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="admin-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Cari
            </button>
            @if(request('search') || request('company_id'))
                <a href="{{ route('admin.users.index') }}" class="admin-btn-ghost">Reset</a>
            @endif
        </form>
    </div>

    {{-- Users Table --}}
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full admin-table">
                <thead>
                    <tr>
                        <th class="text-left">User</th>
                        <th class="text-left">Perusahaan</th>
                        <th class="text-center">Status</th>
                        <th class="text-left">Tanggal Daftar</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.users.show', $u->id) }}" class="text-sm font-semibold text-white hover:text-orange-400 transition">{{ $u->name }}</a>
                                        <p class="text-[11px] text-gray-500 truncate">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($u->company)
                                    <a href="{{ route('admin.companies.show', $u->company) }}" class="text-sm text-gray-300 hover:text-orange-400 transition">
                                        {{ $u->company->name }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-600">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(in_array($u->id, $onlineUserIds))
                                    <span class="admin-badge admin-badge-online">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Online
                                    </span>
                                @else
                                    <span class="admin-badge admin-badge-offline">Offline</span>
                                @endif
                            </td>
                            <td class="text-sm text-gray-400">
                                {{ $u->created_at?->format('d M Y') }}
                                <div class="text-[11px] text-gray-600">{{ $u->created_at?->diffForHumans() }}</div>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $u->id) }}" class="p-2 rounded-lg hover:bg-white/5 text-gray-400 hover:text-white transition" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $u->id) }}" class="p-2 rounded-lg hover:bg-white/5 text-gray-400 hover:text-orange-400 transition" title="Edit">
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
                                <div class="text-3xl mb-2">🔍</div>
                                <p class="text-gray-500">Tidak ada user ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="flex justify-center">
            <div class="[&_.pagination]:flex [&_.pagination]:gap-1 [&_a]:px-3 [&_a]:py-1.5 [&_a]:rounded-lg [&_a]:text-sm [&_a]:text-gray-400 [&_a]:bg-white/5 [&_a:hover]:bg-orange-500/20 [&_a:hover]:text-orange-400 [&_.active_span]:bg-orange-500/20 [&_.active_span]:text-orange-400 [&_span]:px-3 [&_span]:py-1.5 [&_span]:rounded-lg [&_span]:text-sm [&_span]:text-gray-600">
                {{ $users->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
