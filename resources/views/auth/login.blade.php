@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="relative min-h-[calc(100vh-4rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="absolute inset-0 aj-mesh opacity-60"></div>
    <div class="absolute inset-0 aj-dotgrid opacity-40"></div>

    <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 max-w-5xl w-full items-center">
        <!-- Left: Branding -->
        <div class="hidden lg:block">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full aj-glass text-xs font-semibold text-indigo-700 dark:text-indigo-200 mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                crafted by <span class="aj-grad-text font-bold">asepjourney</span>
            </div>
            <h1 class="text-4xl xl:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-[1.1]">
                Sistem Inventaris<br><span class="aj-grad-text">Profesional</span><br>untuk UMKM.
            </h1>
            <p class="mt-6 text-base text-gray-600 dark:text-gray-400 leading-relaxed max-w-md">
                Kelola produk, transaksi, dan stock opname di satu tempat. Ringan, cepat, dan dirancang agar kamu bisa fokus ke hal yang paling penting — mengembangkan bisnis.
            </p>
            <ul class="mt-8 space-y-3 text-sm text-gray-700 dark:text-gray-300">
                <li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-bold">✓</span> Dashboard realtime dengan KPI lengkap</li>
                <li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-bold">✓</span> Stock opname dengan audit trail</li>
                <li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-bold">✓</span> POS ringan untuk transaksi harian</li>
            </ul>
        </div>

        <!-- Right: Login form -->
        <div class="aj-card p-8 md:p-10 aj-ring-glow">
            <div class="text-center mb-8">
                <div class="relative inline-flex">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 flex items-center justify-center shadow-xl shadow-indigo-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <h2 class="mt-5 text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Selamat datang kembali 👋
                </h2>
                <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">
                    Masuk untuk lanjut mengelola stok tokomu.
                </p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" required autofocus autocomplete="email"
                        class="aj-input px-4 py-3"
                        placeholder="nama@email.com" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-medium aj-link">Lupa password?</a>
                        @endif
                    </div>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                        class="aj-input px-4 py-3"
                        placeholder="••••••••">
                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 select-none">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    Ingat saya di perangkat ini
                </label>

                <button type="submit" class="aj-btn-primary w-full py-3 text-base justify-center">
                    Masuk ke Akun
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>

                <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold aj-link">Daftar gratis</a>
                </p>
            </form>

            <div class="aj-divider my-6"></div>
            <p class="text-center text-[11px] font-mono text-gray-500 dark:text-gray-400">
                crafted with obsession by <a href="https://github.com/asepjourney" target="_blank" rel="noopener" class="aj-grad-text font-bold">asepjourney</a>
            </p>
        </div>
    </div>
</div>
@endsection
