@extends('layouts.app')

@section('title', 'Daftar Gratis')

@section('content')
<div class="relative min-h-[calc(100vh-4rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="absolute inset-0 aj-mesh opacity-60"></div>
    <div class="absolute inset-0 aj-dotgrid opacity-40"></div>

    <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 max-w-5xl w-full items-center">
        <!-- Left: Branding -->
        <div class="hidden lg:block">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full aj-glass text-xs font-semibold text-indigo-700 dark:text-indigo-200 mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Gratis selamanya · no credit card
            </div>
            <h1 class="text-4xl xl:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-[1.1]">
                Mulai perjalananmu<br>dengan <span class="aj-grad-text">StockUMKM</span>.
            </h1>
            <p class="mt-6 text-base text-gray-600 dark:text-gray-400 leading-relaxed max-w-md">
                Hanya butuh 30 detik untuk membuat akun. Tidak ada trial berbatas, tidak ada kartu kredit, langsung pakai.
            </p>
            <div class="mt-8 flex items-center gap-4 p-4 rounded-xl aj-card">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 flex items-center justify-center text-white font-extrabold shadow-lg">AJ</div>
                <div>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">asepjourney</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">"Dibangun dari nol untuk UMKM Indonesia. Saran & feedback selalu saya dengarkan."</p>
                </div>
            </div>
        </div>

        <!-- Right: Register form -->
        <div class="aj-card p-8 md:p-10 aj-ring-glow">
            <div class="text-center mb-8">
                <div class="relative inline-flex">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-500 flex items-center justify-center shadow-xl shadow-emerald-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="mt-5 text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Buat akun gratis</h2>
                <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">Cuma 30 detik, dan kamu siap mulai.</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1.5">Nama Lengkap</label>
                    <input id="name" name="name" type="text" required autofocus autocomplete="name"
                        class="aj-input px-4 py-3" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}">
                    @error('name') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" required autocomplete="email"
                        class="aj-input px-4 py-3" placeholder="nama@email.com" value="{{ old('email') }}">
                    @error('email') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1.5">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="aj-input px-4 py-3" placeholder="Min. 8 karakter">
                        @error('password') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1.5">Ulangi</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                            class="aj-input px-4 py-3" placeholder="••••••••">
                    </div>
                </div>

                <label class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300 select-none">
                    <input id="terms" name="terms" type="checkbox" required
                        class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span>Saya setuju dengan <a href="#" class="aj-link font-semibold">Syarat & Ketentuan</a> dan <a href="#" class="aj-link font-semibold">Privasi</a>.</span>
                </label>

                <button type="submit" class="aj-btn-success w-full py-3 text-base justify-center">
                    Buat Akun Sekarang
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>

                <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                    Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold aj-link">Masuk di sini</a>
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
