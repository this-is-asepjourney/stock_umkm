@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $fmt = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $greeting = now()->hour < 11 ? 'Selamat pagi' : (now()->hour < 15 ? 'Selamat siang' : (now()->hour < 18 ? 'Selamat sore' : 'Selamat malam'));
@endphp

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Welcome Hero -->
        <div class="relative overflow-hidden rounded-2xl aj-mesh ring-1 ring-white/10 dark:ring-white/5 p-6 md:p-8">
            <div class="absolute inset-0 aj-dotgrid opacity-40"></div>
            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/70 dark:bg-white/10 text-[11px] font-semibold text-indigo-700 dark:text-indigo-200 backdrop-blur">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Online · {{ now()->translatedFormat('l, d F Y') }}
                    </span>
                    <h1 class="mt-3 text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        {{ $greeting }}, <span class="aj-grad-text">{{ explode(' ', Auth::user()->name)[0] }}</span> 👋
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 max-w-xl">
                        Pantau kondisi stok, penjualan, dan performa tokomu dalam satu layar. Semua rapi, realtime, dan siap membuat keputusan yang tepat.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('sales.create') }}" class="aj-btn-success">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Transaksi Baru
                    </a>
                    <a href="{{ route('stock-opname.create') }}" class="aj-btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Stock Opname
                    </a>
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card label="Total Produk" :value="number_format($stats['total_products'])"
                color="indigo" icon="📦" href="{{ route('products.index') }}">
                <x-slot name="hint">{{ $stats['active_products'] }} aktif</x-slot>
            </x-stat-card>

            <x-stat-card label="Nilai Stok" :value="$fmt($stats['total_stock_value'])"
                color="violet" icon="💰" href="{{ route('reports.valuation') }}" />

            <x-stat-card label="Stok Menipis" :value="number_format($stats['low_stock_products'])"
                color="amber" icon="⚠️" href="{{ route('reports.stock') }}?stock_status=low" />

            <x-stat-card label="Stok Habis" :value="number_format($stats['out_of_stock'])"
                color="rose" icon="🚫" href="{{ route('reports.stock') }}?stock_status=out" />

            <x-stat-card label="Penjualan Bulan Ini" :value="$fmt($stats['monthly_sales'])"
                color="emerald" icon="📈" href="{{ route('sales.index') }}">
                <x-slot name="hint">{{ $stats['monthly_sales_count'] }} transaksi</x-slot>
            </x-stat-card>

            <x-stat-card label="PO Berjalan" :value="number_format($stats['pending_po'])"
                color="blue" icon="🧾" href="{{ route('purchases.index') }}" />

            <x-stat-card label="Opname Aktif" :value="number_format($stats['active_opname'])"
                color="indigo" icon="📋" href="{{ route('stock-opname.index') }}" />

            <x-stat-card label="Kartu Stok" :value="'Lihat'" color="slate" icon="🔄" href="{{ route('movements.index') }}">
                <x-slot name="hint">Riwayat mutasi</x-slot>
            </x-stat-card>
        </div>

        <!-- Chart + Low stock -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 aj-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Tren Penjualan</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">7 hari terakhir · update realtime</p>
                    </div>
                    <span class="aj-chip bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 1116 0 8 8 0 01-16 0z"/></svg>
                        Live
                    </span>
                </div>
                <canvas id="salesChart" height="100"></canvas>
            </div>

            <div class="aj-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Stok Menipis</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Perlu segera di-restock</p>
                    </div>
                    <a href="{{ route('reports.stock') }}?stock_status=low" class="aj-link text-xs font-semibold">Semua</a>
                </div>
                <ul class="space-y-1">
                    @forelse($lowStockList as $p)
                        <li>
                            <a href="{{ route('products.show', $p) }}" class="flex items-center justify-between gap-2 -mx-2 px-2 py-2.5 rounded-lg hover:bg-gray-100/70 dark:hover:bg-gray-800/70 transition">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-300 flex items-center justify-center text-base flex-shrink-0">📦</div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $p->name }}</p>
                                        <p class="text-[11px] text-gray-500 dark:text-gray-400">Min: {{ $p->min_stock }} {{ $p->unit?->symbol }}</p>
                                    </div>
                                </div>
                                <x-status-badge color="amber" dot :label="$p->current_stock . ' ' . ($p->unit?->symbol ?? '')" />
                            </a>
                        </li>
                    @empty
                        <li class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            <div class="text-3xl mb-2">🎉</div>
                            Semua stok aman!
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Top products + Recent movements -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="aj-card p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">🏆 Top 5 Produk</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Bulan {{ now()->translatedFormat('F Y') }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($topProducts as $i => $p)
                        @php
                            $rankColors = ['from-amber-500 to-yellow-500', 'from-slate-400 to-slate-500', 'from-orange-500 to-amber-600', 'from-indigo-400 to-indigo-500', 'from-indigo-400 to-indigo-500'];
                            $rankColor = $rankColors[$i] ?? 'from-indigo-400 to-indigo-500';
                        @endphp
                        <a href="{{ route('products.show', $p) }}" class="flex items-center gap-3 -mx-2 px-2 py-2 rounded-lg hover:bg-gray-100/70 dark:hover:bg-gray-800/70 transition">
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br {{ $rankColor }} text-white flex items-center justify-center text-sm font-extrabold shadow-sm flex-shrink-0">
                                {{ $i + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $p->name }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">Terjual {{ (int) $p->total_sold }} unit</p>
                            </div>
                            <div class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $fmt($p->selling_price) }}</div>
                        </a>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">Belum ada penjualan bulan ini.</p>
                    @endforelse
                </div>
            </div>

            <div class="aj-card p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">🕑 Aktivitas Terakhir</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Mutasi stok realtime</p>
                    </div>
                    <a href="{{ route('movements.index') }}" class="aj-link text-xs font-semibold">Semua</a>
                </div>
                <ul class="space-y-1">
                    @forelse($recentActivities as $m)
                        @php
                            $signMap = ['IN' => '+', 'OUT' => '-', 'ADJUSTMENT' => '', 'OPNAME' => ''];
                            $iconMap = ['IN' => '⬇️', 'OUT' => '⬆️', 'ADJUSTMENT' => '⚖️', 'OPNAME' => '📋'];
                        @endphp
                        <li class="flex items-center gap-3 -mx-2 px-2 py-2 rounded-lg hover:bg-gray-100/70 dark:hover:bg-gray-800/70 transition">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-base flex-shrink-0">
                                {{ $iconMap[$m->type] ?? '•' }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $m->product?->name ?? 'Produk dihapus' }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ $m->user?->name ?? 'System' }} · {{ $m->created_at?->diffForHumans() }}</p>
                            </div>
                            <div class="text-sm font-bold {{ $m->type === 'IN' ? 'text-emerald-600 dark:text-emerald-400' : ($m->type === 'OUT' ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400') }}">
                                {{ $signMap[$m->type] ?? '' }}{{ $m->quantity }}
                            </div>
                        </li>
                    @empty
                        <li class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada aktivitas.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Signature strip -->
        <div class="aj-signature rounded-xl px-6 py-4 border border-gray-200/60 dark:border-white/5 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                <span class="text-lg">✨</span>
                <span>Aplikasi ini dirancang untuk pengusaha UMKM Indonesia —
                    <span class="hidden sm:inline">elegan, ringan, dan bebas ribet.</span>
                </span>
            </div>
            <span class="text-[11px] font-mono text-gray-500 dark:text-gray-400">
                crafted by <a href="https://github.com/asepjourney" target="_blank" rel="noopener" class="aj-grad-text font-bold">asepjourney</a>
            </span>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const ctx = document.getElementById('salesChart');
        if (!ctx) return;
        const labels = @json($salesChart->pluck('date'));
        const data = @json($salesChart->pluck('total'));

        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.35)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Total Penjualan',
                    data,
                    borderColor: '#6366F1',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#6366F1',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#e5e7eb',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: (c) => 'Rp ' + new Intl.NumberFormat('id-ID').format(c.parsed.y),
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#9CA3AF', font: { size: 11 } } },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.1)' },
                        ticks: {
                            color: '#9CA3AF',
                            font: { size: 11 },
                            callback: v => 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(v)
                        }
                    }
                }
            }
        });
    })();
</script>
@endpush
@endsection
