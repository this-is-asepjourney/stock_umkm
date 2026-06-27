<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('title', 'Dashboard') · Admin Panel — StockUMKM</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|jetbrains-mono:400,500,600&display=swap" rel="stylesheet" />

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
        .admin-sidebar {
            background: linear-gradient(180deg, #0f0a1e 0%, #1a1035 40%, #0c0d1f 100%);
        }
        .admin-sidebar-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.625rem 1rem; border-radius: 0.625rem;
            font-size: 0.875rem; font-weight: 500;
            color: rgba(255,255,255,0.6);
            transition: all 0.2s;
        }
        .admin-sidebar-link:hover {
            color: #fff; background: rgba(255,255,255,0.06);
        }
        .admin-sidebar-link.active {
            color: #fff; background: linear-gradient(135deg, rgba(249,115,22,0.25), rgba(234,88,12,0.15));
            box-shadow: inset 0 0 0 1px rgba(249,115,22,0.3);
        }
        .admin-stat-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 1rem;
            padding: 1.25rem;
            transition: all 0.3s;
        }
        .admin-stat-card:hover {
            background: rgba(255,255,255,0.07);
            border-color: rgba(249,115,22,0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        }
        .admin-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 1rem;
            backdrop-filter: blur(12px);
        }
        .admin-table th {
            font-size: 0.7rem; letter-spacing: 0.05em;
            text-transform: uppercase; color: rgba(255,255,255,0.4);
            font-weight: 600; padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .admin-table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 0.875rem;
        }
        .admin-table tr:hover td {
            background: rgba(255,255,255,0.02);
        }
        .admin-badge {
            display: inline-flex; align-items: center; gap: 0.375rem;
            padding: 0.25rem 0.625rem; border-radius: 9999px;
            font-size: 0.7rem; font-weight: 600;
        }
        .admin-badge-online { background: rgba(16,185,129,0.15); color: #34d399; }
        .admin-badge-offline { background: rgba(100,116,139,0.15); color: #94a3b8; }
        .admin-input {
            width: 100%; padding: 0.625rem 1rem; border-radius: 0.625rem;
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
            color: #fff; font-size: 0.875rem; transition: all 0.2s;
        }
        .admin-input:focus {
            outline: none; border-color: rgba(249,115,22,0.5);
            box-shadow: 0 0 0 3px rgba(249,115,22,0.15);
        }
        .admin-input::placeholder { color: rgba(255,255,255,0.3); }
        .admin-btn-primary {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.625rem 1.25rem; border-radius: 0.625rem;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff; font-size: 0.875rem; font-weight: 600;
            border: none; cursor: pointer; transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(249,115,22,0.3);
        }
        .admin-btn-primary:hover {
            transform: translateY(-1px); box-shadow: 0 4px 16px rgba(249,115,22,0.4);
        }
        .admin-btn-danger {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.625rem 1.25rem; border-radius: 0.625rem;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff; font-size: 0.875rem; font-weight: 600;
            border: none; cursor: pointer; transition: all 0.2s;
        }
        .admin-btn-ghost {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.625rem 1.25rem; border-radius: 0.625rem;
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.7); font-size: 0.875rem; font-weight: 500;
            cursor: pointer; transition: all 0.2s;
        }
        .admin-btn-ghost:hover {
            background: rgba(255,255,255,0.1); color: #fff;
        }
        .admin-glow-orange {
            box-shadow: 0 0 32px rgba(249,115,22,0.15);
        }
    </style>

    @stack('head')
</head>

<body class="font-sans antialiased" style="background: #080510; color: #e2e8f0;">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">

        {{-- Sidebar --}}
        <aside class="admin-sidebar fixed inset-y-0 left-0 z-50 w-64 flex flex-col transition-transform duration-300 lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

            {{-- Logo --}}
            <div class="px-5 py-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center shadow-lg shadow-orange-500/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="text-base font-extrabold text-white">Admin<span class="text-orange-400">Panel</span></span>
                    <span class="text-[10px] font-mono tracking-wider text-gray-500 mt-0.5">StockUMKM</span>
                </div>
            </div>

            <div class="h-px bg-gradient-to-r from-transparent via-white/10 to-transparent mx-4"></div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <p class="px-3 pt-2 pb-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Menu</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="admin-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="admin-sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Kelola User
                </a>

                <a href="{{ route('admin.plans.index') }}"
                   class="admin-sidebar-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Paket Langganan
                </a>

                <a href="{{ route('admin.companies.index') }}"
                   class="admin-sidebar-link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Klien / Perusahaan
                </a>
            </nav>

            {{-- Admin Profile --}}
            <div class="px-3 py-4 border-t border-white/5">
                <div class="flex items-center gap-3 px-3 py-2">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                        {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                        <p class="text-[11px] text-gray-500 truncate">{{ Auth::guard('admin')->user()->email ?? '' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full admin-sidebar-link hover:!text-red-400 hover:!bg-red-500/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
             class="fixed inset-0 z-40 bg-black/60 lg:hidden" x-cloak></div>

        {{-- Main Content --}}
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">

            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16"
                    style="background: rgba(8,5,16,0.85); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(255,255,255,0.05);">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-lg font-bold text-white">@yield('title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold" style="background: rgba(249,115,22,0.1); color: #fb923c;">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                        Admin Mode
                    </span>
                    <a href="{{ route('home') }}" target="_blank" class="admin-btn-ghost text-xs px-3 py-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Lihat Site
                    </a>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4500)" x-show="show" x-transition.duration.300ms x-cloak
                     class="mx-4 sm:mx-6 lg:mx-8 mt-4">
                    <div class="flex items-start gap-3 p-4 rounded-xl border-l-4 border-emerald-500 text-emerald-200 shadow-lg" style="background: rgba(16,185,129,0.08);">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <div class="flex-1 text-sm font-medium">{{ session('success') }}</div>
                        <button @click="show = false" class="text-emerald-400 hover:opacity-75 text-xl leading-none">&times;</button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5500)" x-show="show" x-transition.duration.300ms x-cloak
                     class="mx-4 sm:mx-6 lg:mx-8 mt-4">
                    <div class="flex items-start gap-3 p-4 rounded-xl border-l-4 border-rose-500 text-rose-200 shadow-lg" style="background: rgba(244,63,94,0.08);">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-rose-400" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                        <div class="flex-1 text-sm font-medium">{{ session('error') }}</div>
                        <button @click="show = false" class="text-rose-400 hover:opacity-75 text-xl leading-none">&times;</button>
                    </div>
                </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="px-4 sm:px-6 lg:px-8 py-4 border-t border-white/5">
                <div class="flex items-center justify-between text-xs text-gray-600">
                    <span>Admin Panel · StockUMKM</span>
                    <span class="font-mono">crafted by <span class="text-orange-400 font-semibold">asepjourney</span></span>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
