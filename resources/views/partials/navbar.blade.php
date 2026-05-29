@php
    $isMaster = request()->routeIs('products.*', 'categories.*', 'suppliers.*', 'units.*', 'locations.*');
    $isTrx = request()->routeIs('purchases.*', 'sales.*');
    $isRep = request()->routeIs('reports.*', 'movements.*');
    $linkBase = 'relative inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200';
    $linkClass = fn($active) => $linkBase . ' ' . ($active
        ? 'text-indigo-600 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-500/10'
        : 'text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-300 hover:bg-gray-100/70 dark:hover:bg-gray-800/60');
    $dropItem = 'flex items-center gap-2.5 px-3.5 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-300 rounded-lg transition';
@endphp

<nav x-data="{ mobileMenuOpen: false }"
    class="aj-glass sticky top-0 z-40 border-b border-gray-200/60 dark:border-white/5 shadow-sm shadow-black/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="relative">
                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-shadow">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span class="absolute -bottom-1 -right-1 w-3 h-3 bg-emerald-500 rounded-full border-2 border-white dark:border-gray-900 aj-pulse"></span>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-[17px] font-extrabold text-gray-900 dark:text-white">
                            Stock<span class="aj-grad-text">UMKM</span>
                        </span>
                        <span class="text-[10px] font-mono tracking-wider text-gray-500 dark:text-gray-400 mt-0.5">
                            by <span class="text-indigo-600 dark:text-indigo-400 font-semibold">asepjourney</span>
                        </span>
                    </div>
                </a>

                @auth
                <div class="hidden lg:flex lg:ml-8 lg:space-x-1">
                    <a href="{{ route('dashboard') }}" class="{{ $linkClass(request()->routeIs('dashboard')) }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button type="button" class="{{ $linkClass($isMaster) }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Master Data
                            <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.150ms x-cloak
                            class="absolute left-0 mt-1 w-60 p-1.5 rounded-xl aj-glass shadow-xl shadow-black/10">
                            <a href="{{ route('products.index') }}" class="{{ $dropItem }}"><span class="text-lg">📦</span> Produk</a>
                            <a href="{{ route('categories.index') }}" class="{{ $dropItem }}"><span class="text-lg">🏷️</span> Kategori</a>
                            <a href="{{ route('suppliers.index') }}" class="{{ $dropItem }}"><span class="text-lg">🏪</span> Supplier</a>
                            <a href="{{ route('units.index') }}" class="{{ $dropItem }}"><span class="text-lg">📏</span> Satuan</a>
                            <a href="{{ route('locations.index') }}" class="{{ $dropItem }}"><span class="text-lg">📍</span> Lokasi / Gudang</a>
                        </div>
                    </div>

                    <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button type="button" class="{{ $linkClass($isTrx) }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Transaksi
                            <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.150ms x-cloak
                            class="absolute left-0 mt-1 w-60 p-1.5 rounded-xl aj-glass shadow-xl shadow-black/10">
                            <a href="{{ route('purchases.index') }}" class="{{ $dropItem }}"><span class="text-emerald-500 text-lg">⬇️</span> Pembelian (PO)</a>
                            <a href="{{ route('sales.index') }}" class="{{ $dropItem }}"><span class="text-blue-500 text-lg">⬆️</span> Penjualan (POS)</a>
                        </div>
                    </div>

                    <a href="{{ route('stock-opname.index') }}" class="{{ $linkClass(request()->routeIs('stock-opname.*')) }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Opname
                    </a>

                    <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button type="button" class="{{ $linkClass($isRep) }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Laporan
                            <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.150ms x-cloak
                            class="absolute left-0 mt-1 w-64 p-1.5 rounded-xl aj-glass shadow-xl shadow-black/10">
                            <a href="{{ route('reports.stock') }}" class="{{ $dropItem }}"><span class="text-lg">📊</span> Laporan Stok</a>
                            <a href="{{ route('movements.index') }}" class="{{ $dropItem }}"><span class="text-lg">🔄</span> Kartu Stok / Mutasi</a>
                            <a href="{{ route('reports.movement') }}" class="{{ $dropItem }}"><span class="text-lg">📜</span> Laporan Mutasi</a>
                            <a href="{{ route('reports.valuation') }}" class="{{ $dropItem }}"><span class="text-lg">💰</span> Nilai Stok</a>
                        </div>
                    </div>
                </div>
                @endauth

                @guest
                <div class="hidden md:ml-8 md:flex md:space-x-1">
                    <a href="{{ route('home') }}" class="{{ $linkClass(request()->routeIs('home')) }}">Beranda</a>
                    <a href="{{ route('pricing') }}" class="{{ $linkClass(request()->routeIs('pricing')) }}">Harga</a>
                    <a href="{{ route('contact') }}" class="{{ $linkClass(request()->routeIs('contact')) }}">Kontak</a>
                </div>
                @endguest
            </div>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('sales.create') }}"
                        class="hidden md:inline-flex aj-btn-success text-xs px-3 py-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Jual Cepat
                    </a>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button"
                            class="flex items-center gap-2.5 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            <div class="relative">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white dark:border-gray-900"></span>
                            </div>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition x-cloak
                            class="absolute right-0 mt-2 w-64 rounded-xl p-1.5 aj-glass shadow-xl shadow-black/10 z-50">
                            <div class="px-3 py-3 border-b border-gray-200/60 dark:border-white/5">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" class="{{ $dropItem }}"><span>👤</span> Profil Saya</a>
                                <a href="{{ route('company.index') }}" class="{{ $dropItem }}"><span>🏢</span> Manage Company</a>
                                <a href="{{ route('settings.index') }}" class="{{ $dropItem }}"><span>⚙️</span> Pengaturan</a>
                            </div>
                            <div class="border-t border-gray-200/60 dark:border-white/5 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left flex items-center gap-2.5 px-3.5 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition">
                                        <span>🚪</span> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="hidden md:flex md:items-center md:gap-2">
                        <a href="{{ route('login') }}" class="aj-btn-ghost">Masuk</a>
                        <a href="{{ route('register') }}" class="aj-btn-primary">Daftar Gratis</a>
                    </div>
                @endauth

                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                    class="lg:hidden ml-1 p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-transition x-cloak
        class="lg:hidden border-t border-gray-200/60 dark:border-white/5 aj-glass">
        <div class="px-2 py-3 space-y-0.5">
            @auth
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">🏠 Dashboard</a>

                <p class="px-3 pt-3 pb-1 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Master Data</p>
                <a href="{{ route('products.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">📦 Produk</a>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">🏷️ Kategori</a>
                <a href="{{ route('suppliers.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">🏪 Supplier</a>
                <a href="{{ route('units.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">📏 Satuan</a>
                <a href="{{ route('locations.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">📍 Lokasi</a>

                <p class="px-3 pt-3 pb-1 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Transaksi</p>
                <a href="{{ route('purchases.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">⬇️ Pembelian</a>
                <a href="{{ route('sales.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">⬆️ Penjualan</a>
                <a href="{{ route('stock-opname.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">📋 Stock Opname</a>

                <p class="px-3 pt-3 pb-1 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Laporan</p>
                <a href="{{ route('reports.stock') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">📊 Laporan Stok</a>
                <a href="{{ route('movements.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">🔄 Kartu Stok</a>
                <a href="{{ route('reports.movement') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">📜 Laporan Mutasi</a>
                <a href="{{ route('reports.valuation') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">💰 Nilai Stok</a>

                <div class="aj-divider my-2"></div>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">👤 Profil</a>
                <a href="{{ route('company.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">🏢 Manage Company</a>
                <a href="{{ route('settings.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">⚙️ Pengaturan</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10">🚪 Keluar</button>
                </form>
            @else
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Beranda</a>
                <a href="{{ route('pricing') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Harga</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Kontak</a>
                <div class="aj-divider my-2"></div>
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Masuk</a>
                <a href="{{ route('register') }}" class="block mt-1 aj-btn-primary w-full justify-center">Daftar Gratis</a>
            @endauth
        </div>
    </div>
</nav>

@if (session('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4500)" x-show="show" x-transition.duration.300ms x-cloak
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="flex items-start gap-3 p-4 rounded-xl aj-glass border-l-4 border-emerald-500 text-emerald-800 dark:text-emerald-200 shadow-lg shadow-emerald-500/10">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <div class="flex-1 text-sm font-medium">{{ session('success') }}</div>
            <button @click="show = false" class="text-emerald-600 dark:text-emerald-300 hover:opacity-75 text-xl leading-none">&times;</button>
        </div>
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5500)" x-show="show" x-transition.duration.300ms x-cloak
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="flex items-start gap-3 p-4 rounded-xl aj-glass border-l-4 border-rose-500 text-rose-800 dark:text-rose-200 shadow-lg shadow-rose-500/10">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>
            <div class="flex-1 text-sm font-medium">{{ session('error') }}</div>
            <button @click="show = false" class="text-rose-600 dark:text-rose-300 hover:opacity-75 text-xl leading-none">&times;</button>
        </div>
    </div>
@endif

@if (session('info'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.300ms x-cloak
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="flex items-start gap-3 p-4 rounded-xl aj-glass border-l-4 border-indigo-500 text-indigo-800 dark:text-indigo-200 shadow-lg shadow-indigo-500/10">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1 text-sm font-medium">{{ session('info') }}</div>
            <button @click="show = false" class="text-indigo-600 dark:text-indigo-300 hover:opacity-75 text-xl leading-none">&times;</button>
        </div>
    </div>
@endif
