<footer class="relative mt-16 border-t border-gray-200/60 dark:border-white/5 bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm">
    <div class="absolute inset-0 aj-dotgrid opacity-30 pointer-events-none"></div>
    <div class="relative max-w-7xl mx-auto py-14 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-10">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-extrabold text-gray-900 dark:text-white leading-tight">
                            Stock<span class="aj-grad-text">UMKM</span>
                        </p>
                        <p class="text-[11px] font-mono tracking-wider text-gray-500 dark:text-gray-400">
                            inventory · crafted by asepjourney
                        </p>
                    </div>
                </div>
                <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-400 max-w-md">
                    Sistem manajemen inventaris modern untuk UMKM Indonesia. Kelola produk, pembelian, penjualan, dan stock opname
                    dalam satu aplikasi yang ringan, cepat, dan elegan.
                </p>

                <div class="mt-5 flex flex-wrap gap-2">
                    <span class="aj-chip bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">Laravel 13</span>
                    <span class="aj-chip bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-300">Livewire</span>
                    <span class="aj-chip bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">Tailwind CSS</span>
                    <span class="aj-chip bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">Alpine.js</span>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Modul</h3>
                <ul class="space-y-2.5 text-sm">
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition">Dashboard</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition">Produk</a></li>
                        <li><a href="{{ route('sales.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition">Penjualan</a></li>
                        <li><a href="{{ route('stock-opname.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition">Stock Opname</a></li>
                        <li><a href="{{ route('reports.stock') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition">Laporan</a></li>
                    @else
                        <li><a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition">Beranda</a></li>
                        <li><a href="{{ route('pricing') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition">Harga</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition">Kontak</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Sumber Daya</h3>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition">Dokumentasi</a></li>
                    <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition">Changelog</a></li>
                    <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition">Roadmap</a></li>
                    <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition">Panduan Singkat</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Developer</h3>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/30">AJ</div>
                    <div class="leading-tight">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">asepjourney</p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-mono">fullstack · indie hacker</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="https://github.com/asepjourney" target="_blank" rel="noopener" title="GitHub"
                        class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-gray-900 hover:text-white dark:hover:bg-white dark:hover:text-gray-900 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .5C5.73.5.7 5.53.7 11.79c0 4.99 3.23 9.22 7.72 10.72.56.1.77-.24.77-.54v-2.1c-3.14.68-3.8-1.33-3.8-1.33-.52-1.31-1.27-1.66-1.27-1.66-1.04-.71.08-.7.08-.7 1.15.08 1.75 1.19 1.75 1.19 1.02 1.75 2.68 1.25 3.33.95.1-.74.4-1.25.73-1.54-2.51-.28-5.15-1.25-5.15-5.56 0-1.23.44-2.23 1.16-3.02-.12-.28-.5-1.43.11-2.98 0 0 .95-.3 3.12 1.16.91-.25 1.88-.38 2.85-.38.97 0 1.94.13 2.85.38 2.17-1.47 3.12-1.16 3.12-1.16.62 1.55.23 2.7.11 2.98.72.79 1.16 1.79 1.16 3.02 0 4.33-2.65 5.27-5.17 5.55.41.35.77 1.05.77 2.12v3.14c0 .3.2.66.78.54 4.49-1.5 7.71-5.73 7.71-10.72C23.3 5.53 18.27.5 12 .5z"/></svg>
                    </a>
                    <a href="https://x.com/asepjourney" target="_blank" rel="noopener" title="X / Twitter"
                        class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-gray-900 hover:text-white dark:hover:bg-white dark:hover:text-gray-900 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2H21.5l-7.44 8.5L22.5 22h-6.844l-5.357-6.953L4.32 22H1.06l7.96-9.09L1.5 2h7.031l4.84 6.4L18.244 2zm-1.2 18h1.898L7.06 4h-2.03l12.014 16z"/></svg>
                    </a>
                    <a href="mailto:hi@asepjourney.dev" title="Email"
                        class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-indigo-600 hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-10 pt-6 border-t border-gray-200/60 dark:border-white/5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} StockUMKM. Dibuat untuk UMKM Indonesia.
            </p>
            <div class="flex items-center gap-2 aj-signature rounded-full px-4 py-1.5 border border-gray-200/60 dark:border-white/5">
                <svg class="w-3.5 h-3.5 text-rose-500 aj-pulse" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                <span class="text-[11px] font-mono text-gray-600 dark:text-gray-300">
                    crafted with obsession by
                    <a href="https://github.com/asepjourney" target="_blank" rel="noopener" class="font-bold aj-grad-text">asepjourney</a>
                </span>
            </div>
        </div>
    </div>
</footer>
