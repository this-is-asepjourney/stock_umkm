@extends('layouts.app')

@section('title', 'Stock UMKM — Inventory modern untuk UMKM Indonesia')

@section('content')
    <!-- Hero -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 aj-mesh"></div>
        <div class="absolute inset-0 aj-dotgrid opacity-40"></div>
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-indigo-500/20 rounded-full blur-3xl aj-float"></div>
        <div class="absolute top-40 right-0 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl aj-float" style="animation-delay:-2s"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-28">
            <div class="text-center max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full aj-glass ring-1 ring-indigo-500/20 text-xs font-semibold text-indigo-700 dark:text-indigo-200 shadow-sm mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    v1.0 · Crafted by <span class="aj-grad-text font-bold">asepjourney</span>
                </div>
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold text-gray-900 dark:text-white mb-6 tracking-tighter leading-[1.05]">
                    Manajemen Stok
                    <br>
                    <span class="aj-grad-text">Senyaman</span> <span class="text-indigo-600 dark:text-indigo-400">WhatsApp</span>.
                </h1>
                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Sistem inventaris modern untuk UMKM Indonesia. Kelola produk, pembelian, penjualan, dan stock opname
                    dari satu layar yang <span class="font-semibold text-gray-900 dark:text-white">elegan, cepat, dan anti ribet</span>.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('register') }}" class="aj-btn-primary text-base px-6 py-3 shadow-xl shadow-indigo-500/30">
                        Mulai Gratis Sekarang
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="{{ route('login') }}" class="aj-btn-outline text-base px-6 py-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Masuk ke Akun
                    </a>
                </div>

                <div class="mt-10 flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-xs font-medium text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-1.5"><span class="text-emerald-500">✓</span> Gratis selamanya</div>
                    <div class="flex items-center gap-1.5"><span class="text-emerald-500">✓</span> Dark mode native</div>
                    <div class="flex items-center gap-1.5"><span class="text-emerald-500">✓</span> Tanpa kartu kredit</div>
                    <div class="flex items-center gap-1.5"><span class="text-emerald-500">✓</span> Buatan lokal 🇮🇩</div>
                </div>
            </div>

            <!-- Preview Frame -->
            <div class="mt-20 relative max-w-5xl mx-auto">
                <div class="absolute -inset-4 bg-gradient-to-r from-indigo-500/30 via-purple-500/30 to-pink-500/30 rounded-3xl blur-2xl opacity-50"></div>
                <div class="relative aj-card p-2 rounded-2xl overflow-hidden">
                    <div class="bg-gray-100 dark:bg-gray-900 rounded-xl overflow-hidden">
                        <div class="flex items-center gap-1.5 px-4 py-2.5 border-b border-gray-200 dark:border-gray-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                            <span class="ml-3 text-[11px] font-mono text-gray-500 dark:text-gray-400">stockumkm.app/dashboard</span>
                        </div>
                        <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach ([
                                ['📦','Total Produk','248','indigo'],
                                ['💰','Nilai Stok','Rp 84.5M','violet'],
                                ['📈','Penjualan','Rp 12.3M','emerald'],
                                ['⚠️','Menipis','7','amber'],
                            ] as [$ic,$lb,$vl,$cl])
                                <div class="aj-card p-4">
                                    <div class="flex items-center justify-between">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $lb }}</p>
                                        <span class="text-xl">{{ $ic }}</span>
                                    </div>
                                    <p class="mt-2 text-xl font-extrabold text-gray-900 dark:text-white">{{ $vl }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="px-6 pb-6">
                            <div class="aj-card p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Tren Penjualan 7 Hari</p>
                                    <span class="aj-chip bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">Live</span>
                                </div>
                                <svg viewBox="0 0 400 100" class="w-full h-24">
                                    <defs>
                                        <linearGradient id="g1" x1="0" x2="0" y1="0" y2="1">
                                            <stop offset="0" stop-color="#6366F1" stop-opacity=".35"/>
                                            <stop offset="1" stop-color="#6366F1" stop-opacity="0"/>
                                        </linearGradient>
                                    </defs>
                                    <path d="M0,80 C50,60 80,30 120,45 S200,70 240,40 320,20 400,35 L400,100 L0,100 Z" fill="url(#g1)" />
                                    <path d="M0,80 C50,60 80,30 120,45 S200,70 240,40 320,20 400,35" fill="none" stroke="#6366F1" stroke-width="2.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="relative py-24 border-t border-gray-200/60 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 max-w-2xl mx-auto">
                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-3">Fitur Unggulan</p>
                <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4 tracking-tight">
                    Semua yang kamu butuhkan
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Tools profesional yang dirancang dari nol untuk UMKM — bukan hanya versi "lite" dari ERP mahal.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ([
                    ['📦','Manajemen Produk','Produk, kategori, satuan, supplier, lokasi gudang — semua terintegrasi. Support multi-unit dan barcode.','from-indigo-500 to-purple-600'],
                    ['🛒','POS & Pembelian','Kasir cepat dengan stok auto-potong. PO pembelian dengan penerimaan barang parsial.','from-emerald-500 to-teal-600'],
                    ['📋','Stock Opname','Sesi opname dengan progress tracking. Selisih & penyesuaian otomatis tercatat di kartu stok.','from-amber-500 to-orange-600'],
                    ['📊','Laporan Real-time','Laporan stok, valuasi, mutasi, dan penjualan. Filter fleksibel, siap di-export.','from-rose-500 to-pink-600'],
                    ['🔄','Kartu Stok','Audit trail lengkap setiap pergerakan. Transparansi penuh untuk bisnismu.','from-blue-500 to-cyan-600'],
                    ['🎨','Dark Mode Native','UI elegan dengan dark mode yang nyaman di mata. Responsif di semua perangkat.','from-violet-500 to-indigo-600'],
                ] as [$icon, $title, $desc, $grad])
                    <div class="group aj-card aj-card-hover p-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $grad }} text-white flex items-center justify-center text-2xl shadow-lg shadow-black/10 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                            {{ $icon }}
                        </div>
                        <h3 class="mt-5 text-lg font-bold text-gray-900 dark:text-white">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-400">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="relative py-20 border-t border-gray-200/60 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="aj-card p-10 text-center bg-gradient-to-br from-indigo-500/10 via-purple-500/10 to-pink-500/10">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach ([
                        ['100%','UMKM Indonesia'],
                        ['24/7','Selalu tersedia'],
                        ['∞','Produk & Transaksi'],
                        ['0 Rp','Biaya bulanan'],
                    ] as [$num, $lbl])
                        <div>
                            <p class="text-3xl md:text-5xl font-extrabold aj-grad-text tracking-tight">{{ $num }}</p>
                            <p class="mt-1 text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">{{ $lbl }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="relative py-24 border-t border-gray-200/60 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 max-w-2xl mx-auto">
                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-3">Testimoni</p>
                <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Dipercaya UMKM di seluruh nusantara
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach ([
                    ['Budi Santoso','Toko Kelontong Jaya','B','indigo','Sekarang stok tokoku rapi. Penjualan & pembelian tercatat otomatis. Proses tutup bulan jauh lebih cepat.'],
                    ['Siti Aminah','Minimarket Berkah','S','emerald','Fitur stock opname-nya cerdas. Selisih langsung kelihatan dan bisa di-adjust dengan satu klik. Ngirit waktu banget.'],
                    ['Ahmad Hidayat','Distributor Snack Nusantara','A','rose','UI-nya bersih, dark mode nyaman dipakai malam hari. Yang bikin salut, developernya responsif di chat.'],
                ] as [$name, $biz, $initial, $color, $quote])
                    <div class="aj-card aj-card-hover p-6">
                        <div class="flex text-amber-400 text-lg">★★★★★</div>
                        <p class="mt-4 text-sm leading-relaxed text-gray-700 dark:text-gray-300">"{{ $quote }}"</p>
                        <div class="mt-5 flex items-center gap-3 pt-4 border-t border-gray-200/60 dark:border-white/5">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-{{ $color }}-400 to-{{ $color }}-600 flex items-center justify-center text-white font-bold">{{ $initial }}</div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $biz }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Developer Spotlight -->
    <section class="relative py-24 border-t border-gray-200/60 dark:border-white/5">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl aj-card p-8 md:p-12">
                <div class="absolute -top-20 -right-20 w-72 h-72 bg-gradient-to-br from-indigo-500 to-pink-500 opacity-20 rounded-full blur-3xl aj-float"></div>
                <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-gradient-to-br from-emerald-500 to-cyan-500 opacity-15 rounded-full blur-3xl aj-float" style="animation-delay:-3s"></div>

                <div class="relative grid md:grid-cols-[auto_1fr] gap-8 items-center">
                    <div class="flex justify-center md:justify-start">
                        <div class="relative">
                            <div class="absolute -inset-2 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-full blur-lg opacity-60"></div>
                            <div class="relative w-32 h-32 rounded-full bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 flex items-center justify-center text-white font-extrabold text-5xl shadow-2xl">AJ</div>
                            <span class="absolute -bottom-1 -right-1 px-2 py-0.5 rounded-full bg-emerald-500 text-white text-[10px] font-bold shadow-lg ring-4 ring-white dark:ring-gray-900">DEV</span>
                        </div>
                    </div>
                    <div class="text-center md:text-left">
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-2">Dibuat oleh</p>
                        <h3 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            <span class="aj-grad-text">asepjourney</span>
                        </h3>
                        <p class="mt-1 text-sm font-mono tracking-wider text-gray-500 dark:text-gray-400">
                            · fullstack dev · indie hacker · Indonesia ·
                        </p>
                        <p class="mt-4 text-sm md:text-base text-gray-600 dark:text-gray-300 leading-relaxed max-w-2xl">
                            Developer keren yang obsesif dengan detail, performa, dan pengalaman pengguna.
                            Stock UMKM lahir dari keresahan pribadi melihat UMKM Indonesia kesulitan mengelola stok —
                            dan keinginan untuk membuat <em>tools</em> yang kelasnya setara produk SaaS global,
                            tapi tetap <em>nyeni</em> dan bersahabat.
                        </p>
                        <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-2">
                            <a href="https://github.com/asepjourney" target="_blank" rel="noopener" class="aj-btn-outline text-xs">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .5C5.73.5.7 5.53.7 11.79c0 4.99 3.23 9.22 7.72 10.72.56.1.77-.24.77-.54v-2.1c-3.14.68-3.8-1.33-3.8-1.33-.52-1.31-1.27-1.66-1.27-1.66-1.04-.71.08-.7.08-.7 1.15.08 1.75 1.19 1.75 1.19 1.02 1.75 2.68 1.25 3.33.95.1-.74.4-1.25.73-1.54-2.51-.28-5.15-1.25-5.15-5.56 0-1.23.44-2.23 1.16-3.02-.12-.28-.5-1.43.11-2.98 0 0 .95-.3 3.12 1.16.91-.25 1.88-.38 2.85-.38.97 0 1.94.13 2.85.38 2.17-1.47 3.12-1.16 3.12-1.16.62 1.55.23 2.7.11 2.98.72.79 1.16 1.79 1.16 3.02 0 4.33-2.65 5.27-5.17 5.55.41.35.77 1.05.77 2.12v3.14c0 .3.2.66.78.54 4.49-1.5 7.71-5.73 7.71-10.72C23.3 5.53 18.27.5 12 .5z"/></svg>
                                GitHub
                            </a>
                            <a href="https://x.com/asepjourney" target="_blank" rel="noopener" class="aj-btn-outline text-xs">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2H21.5l-7.44 8.5L22.5 22h-6.844l-5.357-6.953L4.32 22H1.06l7.96-9.09L1.5 2h7.031l4.84 6.4L18.244 2z"/></svg>
                                @asepjourney
                            </a>
                            <a href="mailto:hi@asepjourney.dev" class="aj-btn-outline text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="relative py-24 border-t border-gray-200/60 dark:border-white/5">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl p-10 md:p-16 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 text-center shadow-2xl">
                <div class="absolute inset-0 aj-dotgrid opacity-10"></div>
                <div class="absolute -top-10 -right-10 w-60 h-60 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-60 h-60 bg-white/10 rounded-full blur-3xl"></div>

                <h2 class="relative text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                    Siap mengubah cara kamu<br>mengelola stok?
                </h2>
                <p class="relative text-lg text-indigo-100 mb-8 max-w-2xl mx-auto">
                    Gabung sekarang juga. Gratis selamanya, tanpa kartu kredit, langsung pakai.
                </p>
                <div class="relative flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl text-base font-bold text-indigo-600 bg-white hover:bg-gray-50 shadow-xl transform hover:scale-105 transition">
                        Buat Akun Gratis
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl text-base font-bold text-white ring-2 ring-white/40 hover:ring-white hover:bg-white/10 transition">
                        Sudah Punya Akun
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
