<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>Login Admin · StockUMKM</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body, html { margin: 0; padding: 0; }
            * { box-sizing: border-box; }
            body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        </style>
    @endif

    <style>
        .login-bg {
            background: #080510;
            background-image:
                radial-gradient(at 20% 30%, rgba(249,115,22,0.12) 0, transparent 50%),
                radial-gradient(at 80% 20%, rgba(168,85,247,0.08) 0, transparent 50%),
                radial-gradient(at 50% 80%, rgba(234,88,12,0.06) 0, transparent 50%);
        }
        .login-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 1.25rem;
            backdrop-filter: blur(24px);
        }
        .login-input {
            width: 100%; padding: 0.75rem 1rem; border-radius: 0.75rem;
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
            color: #fff; font-size: 0.875rem; transition: all 0.2s;
        }
        .login-input:focus {
            outline: none; border-color: rgba(249,115,22,0.5);
            box-shadow: 0 0 0 3px rgba(249,115,22,0.15);
        }
        .login-input::placeholder { color: rgba(255,255,255,0.3); }
        .login-btn {
            width: 100%; padding: 0.75rem 1.5rem; border-radius: 0.75rem;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff; font-size: 0.875rem; font-weight: 700;
            border: none; cursor: pointer; transition: all 0.25s;
            box-shadow: 0 4px 16px rgba(249,115,22,0.3);
        }
        .login-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(249,115,22,0.4);
        }
        .login-btn:active { transform: translateY(0); }
    </style>
</head>

<body class="login-bg font-sans antialiased min-h-screen flex items-center justify-center p-4" style="color: #e2e8f0;">

    {{-- Decorative grid --}}
    <div class="fixed inset-0 aj-dotgrid opacity-20 pointer-events-none"></div>

    <div class="relative w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center shadow-lg shadow-orange-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="text-left">
                    <h1 class="text-2xl font-extrabold text-white">Admin<span class="text-orange-400">Panel</span></h1>
                    <p class="text-[11px] font-mono tracking-wider text-gray-500">StockUMKM by asepjourney</p>
                </div>
            </div>
        </div>

        {{-- Login Card --}}
        <div class="login-card p-8 admin-glow-orange">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-white">Masuk ke Admin Panel</h2>
                <p class="text-sm text-gray-400 mt-1">Masukkan kredensial administrator</p>
            </div>

            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="login-input" placeholder="admin@stockumkm.dev">
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
                    <input id="password" type="password" name="password" required
                           class="login-input" placeholder="••••••••">
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember"
                           class="w-4 h-4 rounded border-gray-600 bg-gray-800 text-orange-500 focus:ring-orange-500/30">
                    <label for="remember" class="ml-2 text-sm text-gray-400">Ingat saya</label>
                </div>

                <button type="submit" class="login-btn">
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Masuk
                    </span>
                </button>
            </form>
        </div>

        {{-- Back link --}}
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-orange-400 transition inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke halaman utama
            </a>
        </div>
    </div>
</body>
</html>
