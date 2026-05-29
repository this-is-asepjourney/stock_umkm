@extends('layouts.app')
@section('title', 'Manage Company')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manage Company</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola informasi perusahaan dan anggota tim Anda</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Company Information Card --}}
        <div class="lg:col-span-1">
            <div class="aj-card p-6">
                <div class="flex items-center gap-2 mb-6">
                    <span class="text-lg">🏢</span>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Perusahaan</h2>
                </div>

                <form method="POST" action="{{ route('company.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Perusahaan</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $company->name ?? '') }}" required
                            class="w-full aj-input" placeholder="Nama perusahaan Anda">
                        @error('name') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Perusahaan</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $company->email ?? '') }}"
                            class="w-full aj-input" placeholder="company@example.com">
                        @error('email') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Telepon</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $company->phone ?? '') }}"
                            class="w-full aj-input" placeholder="08xx-xxxx-xxxx">
                        @error('phone') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                        <textarea id="address" name="address" rows="3"
                            class="w-full aj-input" placeholder="Alamat perusahaan">{{ old('address', $company->address ?? '') }}</textarea>
                        @error('address') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full aj-btn-primary justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        {{-- Team Members Card --}}
        <div class="lg:col-span-2">
            <div class="aj-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">👥</span>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Anggota Tim</h2>
                        <span class="ml-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300">
                            {{ $members->count() }} orang
                        </span>
                    </div>
                    <button type="button" onclick="document.getElementById('add-member-modal').classList.remove('hidden')"
                        class="aj-btn-primary text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Anggota
                    </button>
                </div>

                {{-- Member List --}}
                <div class="space-y-3">
                    @forelse ($members as $member)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50/80 dark:bg-white/5 border border-gray-200/60 dark:border-white/5 hover:border-indigo-300 dark:hover:border-indigo-500/30 transition-all duration-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $member->name }}</p>
                                        @if ($member->id === $user->id)
                                            <span class="px-1.5 py-0.5 text-[10px] font-bold rounded bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300">ANDA</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $member->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                    Bergabung {{ $member->created_at->diffForHumans() }}
                                </span>
                                @if ($member->id !== $user->id)
                                    <form method="POST" action="{{ route('company.remove-member', $member) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus {{ $member->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition" title="Hapus anggota">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum ada anggota tim</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Klik "Tambah Anggota" untuk menambah tim Anda</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Member Modal --}}
<div id="add-member-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
     onclick="if(event.target===this) this.classList.add('hidden')">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="relative w-full max-w-md aj-card p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <span class="text-lg">➕</span>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Anggota Tim</h3>
            </div>
            <button type="button" onclick="document.getElementById('add-member-modal').classList.add('hidden')"
                class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('company.add-member') }}" class="space-y-4">
            @csrf

            <div>
                <label for="member_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                <input type="text" id="member_name" name="name" required
                    class="w-full aj-input" placeholder="Nama anggota baru">
                @error('name') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="member_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input type="email" id="member_email" name="email" required
                    class="w-full aj-input" placeholder="email@anggota.com">
                @error('email') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="member_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <input type="password" id="member_password" name="password" required
                    class="w-full aj-input" placeholder="Minimal 8 karakter">
                @error('password') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="member_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password</label>
                <input type="password" id="member_password_confirmation" name="password_confirmation" required
                    class="w-full aj-input" placeholder="Ulangi password">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('add-member-modal').classList.add('hidden')"
                    class="flex-1 aj-btn-ghost justify-center">
                    Batal
                </button>
                <button type="submit" class="flex-1 aj-btn-primary justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Tambahkan
                </button>
            </div>
        </form>
    </div>
</div>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('add-member-modal').classList.remove('hidden');
    });
</script>
@endif
@endsection
