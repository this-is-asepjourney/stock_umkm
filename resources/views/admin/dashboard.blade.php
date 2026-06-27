@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
@php
    $fmt = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
@endphp

<div class="space-y-6">

    {{-- Welcome Hero --}}
    <div class="relative overflow-hidden rounded-2xl p-6 md:p-8" style="background: linear-gradient(135deg, rgba(249,115,22,0.1), rgba(234,88,12,0.05), rgba(168,85,247,0.05)); border: 1px solid rgba(249,115,22,0.15);">
        <div class="absolute inset-0 aj-dotgrid opacity-20"></div>
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold" style="background: rgba(249,115,22,0.15); color: #fb923c;">
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                    Admin Panel · {{ now()->translatedFormat('l, d F Y') }}
                </span>
                <h1 class="mt-3 text-2xl md:text-3xl font-extrabold text-white tracking-tight">
                    Selamat datang, <span class="text-orange-400">{{ explode(' ', Auth::guard('admin')->user()->name)[0] }}</span> 👑
                </h1>
                <p class="mt-1 text-sm text-gray-400 max-w-xl">
                    Monitor seluruh tenant, user, dan aktivitas dari satu tempat. Semua data realtime dan lintas perusahaan.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="admin-stat-card text-center px-6">
                    <div class="text-2xl font-extrabold text-emerald-400">{{ $onlineUsers }}</div>
                    <div class="text-[11px] text-gray-500 mt-0.5">Online Sekarang</div>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="admin-stat-card">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(168,85,247,0.15);">🏢</div>
            </div>
            <div class="text-2xl font-extrabold text-white">{{ number_format($stats['total_companies']) }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Total Perusahaan</div>
        </div>

        <div class="admin-stat-card">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(59,130,246,0.15);">👥</div>
            </div>
            <div class="text-2xl font-extrabold text-white">{{ number_format($stats['total_users']) }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Total User Terdaftar</div>
            <div class="text-[11px] text-emerald-400 mt-1">+{{ $stats['monthly_new_users'] }} bulan ini</div>
        </div>

        <div class="admin-stat-card">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(249,115,22,0.15);">📦</div>
            </div>
            <div class="text-2xl font-extrabold text-white">{{ number_format($stats['total_products']) }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Total Produk</div>
        </div>

        <div class="admin-stat-card">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(16,185,129,0.15);">💰</div>
            </div>
            <div class="text-2xl font-extrabold text-white">{{ $fmt($stats['total_sales_value']) }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Total Nilai Penjualan</div>
            <div class="text-[11px] text-gray-500 mt-1">{{ number_format($stats['total_sales']) }} transaksi selesai</div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="admin-card p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-white">📈 Pertumbuhan User</h3>
                    <p class="text-xs text-gray-500">12 bulan terakhir</p>
                </div>
            </div>
            <canvas id="userTrendChart" height="120"></canvas>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-white">💹 Transaksi Global</h3>
                    <p class="text-xs text-gray-500">7 hari terakhir</p>
                </div>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold" style="background: rgba(16,185,129,0.15); color: #34d399;">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live
                </span>
            </div>
            <canvas id="salesChart" height="120"></canvas>
        </div>
    </div>

    {{-- Recent Users + Top Companies --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Users --}}
        <div class="admin-card p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-white">🕐 User Terbaru</h3>
                    <p class="text-xs text-gray-500">Registrasi terbaru</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-orange-400 hover:text-orange-300 transition">
                    Lihat Semua →
                </a>
            </div>
            <div class="space-y-1">
                @forelse($recentUsers as $u)
                    <a href="{{ route('admin.users.show', $u->id) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/5 transition">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ $u->name }}</p>
                            <p class="text-[11px] text-gray-500 truncate">{{ $u->company?->name ?? '-' }} · {{ $u->created_at?->diffForHumans() }}</p>
                        </div>
                        <span class="text-[11px] text-gray-600">{{ $u->email }}</span>
                    </a>
                @empty
                    <p class="text-sm text-gray-500 text-center py-8">Belum ada user.</p>
                @endforelse
            </div>
        </div>

        {{-- Top Companies --}}
        <div class="admin-card p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-white">🏆 Top Perusahaan</h3>
                    <p class="text-xs text-gray-500">Berdasarkan jumlah member</p>
                </div>
                <a href="{{ route('admin.companies.index') }}" class="text-xs font-semibold text-orange-400 hover:text-orange-300 transition">
                    Lihat Semua →
                </a>
            </div>
            <div class="space-y-1">
                @forelse($topCompanies as $i => $c)
                    @php
                        $rankColors = ['from-amber-500 to-yellow-500', 'from-slate-400 to-slate-500', 'from-orange-500 to-amber-600', 'from-indigo-400 to-indigo-500', 'from-indigo-400 to-indigo-500'];
                        $rankColor = $rankColors[$i] ?? 'from-indigo-400 to-indigo-500';
                    @endphp
                    <a href="{{ route('admin.companies.show', $c) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/5 transition">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br {{ $rankColor }} text-white flex items-center justify-center text-sm font-extrabold shadow-sm flex-shrink-0">
                            {{ $i + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ $c->name }}</p>
                            <p class="text-[11px] text-gray-500">Terdaftar {{ $c->created_at?->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-orange-400">{{ $c->users_count }}</div>
                            <div class="text-[10px] text-gray-500">member</div>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-gray-500 text-center py-8">Belum ada perusahaan.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    // User Trend Chart
    const utCtx = document.getElementById('userTrendChart');
    if (utCtx) {
        const gradient = utCtx.getContext('2d').createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(249, 115, 22, 0.3)');
        gradient.addColorStop(1, 'rgba(249, 115, 22, 0)');
        new Chart(utCtx, {
            type: 'bar',
            data: {
                labels: @json($userTrend->pluck('month')),
                datasets: [{
                    label: 'User Baru',
                    data: @json($userTrend->pluck('count')),
                    backgroundColor: 'rgba(249, 115, 22, 0.5)',
                    borderColor: '#f97316',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#fff', bodyColor: '#e5e7eb',
                        padding: 12, cornerRadius: 8, displayColors: false,
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#4b5563', font: { size: 10 } } },
                    y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#4b5563', font: { size: 10 }, stepSize: 1 } }
                }
            }
        });
    }

    // Sales Chart
    const scCtx = document.getElementById('salesChart');
    if (scCtx) {
        const gradient2 = scCtx.getContext('2d').createLinearGradient(0, 0, 0, 260);
        gradient2.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
        gradient2.addColorStop(1, 'rgba(16, 185, 129, 0)');
        new Chart(scCtx, {
            type: 'line',
            data: {
                labels: @json($salesChart->pluck('date')),
                datasets: [{
                    label: 'Total Transaksi',
                    data: @json($salesChart->pluck('total')),
                    borderColor: '#10b981',
                    backgroundColor: gradient2,
                    borderWidth: 2.5, tension: 0.4, fill: true,
                    pointRadius: 0, pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#10b981',
                    pointHoverBorderColor: '#fff', pointHoverBorderWidth: 2,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#fff', bodyColor: '#e5e7eb',
                        padding: 12, cornerRadius: 8, displayColors: false,
                        callbacks: { label: (c) => 'Rp ' + new Intl.NumberFormat('id-ID').format(c.parsed.y) }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#4b5563', font: { size: 10 } } },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255,255,255,0.04)' },
                        ticks: {
                            color: '#4b5563', font: { size: 10 },
                            callback: v => 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(v)
                        }
                    }
                }
            }
        });
    }
})();
</script>
@endpush
@endsection
