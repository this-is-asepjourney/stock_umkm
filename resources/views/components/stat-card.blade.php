@props([
    'label' => '',
    'value' => '',
    'icon' => null,
    'color' => 'indigo',
    'href' => null,
    'trend' => null,
])

@php
    $palette = [
        'indigo' => ['from-indigo-500 to-indigo-600', 'bg-indigo-500/10', 'text-indigo-600 dark:text-indigo-300', 'ring-indigo-500/20'],
        'emerald' => ['from-emerald-500 to-teal-600', 'bg-emerald-500/10', 'text-emerald-600 dark:text-emerald-300', 'ring-emerald-500/20'],
        'amber' => ['from-amber-500 to-orange-600', 'bg-amber-500/10', 'text-amber-600 dark:text-amber-300', 'ring-amber-500/20'],
        'rose' => ['from-rose-500 to-pink-600', 'bg-rose-500/10', 'text-rose-600 dark:text-rose-300', 'ring-rose-500/20'],
        'blue' => ['from-blue-500 to-cyan-600', 'bg-blue-500/10', 'text-blue-600 dark:text-blue-300', 'ring-blue-500/20'],
        'violet' => ['from-violet-500 to-purple-600', 'bg-violet-500/10', 'text-violet-600 dark:text-violet-300', 'ring-violet-500/20'],
        'slate' => ['from-slate-500 to-slate-600', 'bg-slate-500/10', 'text-slate-600 dark:text-slate-300', 'ring-slate-500/20'],
    ];
    [$grad, $iconBg, $iconText, $ringColor] = $palette[$color] ?? $palette['slate'];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif
    class="group relative overflow-hidden aj-card aj-card-hover p-5 {{ $href ? 'cursor-pointer' : '' }}">
    <div class="absolute -top-8 -right-8 w-28 h-28 bg-gradient-to-br {{ $grad }} opacity-10 rounded-full blur-2xl group-hover:opacity-20 transition-opacity duration-500"></div>

    <div class="relative flex items-start justify-between gap-3">
        <div class="flex-1 min-w-0">
            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $label }}</p>
            <p class="mt-1.5 text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight truncate">{{ $value }}</p>
            @isset($hint)
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $hint }}</p>
            @endisset
            @if ($trend !== null)
                @php $trendUp = (float) $trend >= 0; @endphp
                <div class="mt-2 inline-flex items-center gap-1 text-xs font-semibold {{ $trendUp ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $trendUp ? 'M7 17l5-5 5 5' : 'M17 7l-5 5-5-5' }}" />
                    </svg>
                    {{ $trendUp ? '+' : '' }}{{ $trend }}%
                </div>
            @endif
        </div>
        <div class="flex-shrink-0 w-12 h-12 rounded-xl {{ $iconBg }} ring-1 {{ $ringColor }} flex items-center justify-center text-xl {{ $iconText }} group-hover:scale-110 transition-transform duration-300">
            {!! $icon !!}
        </div>
    </div>
</{{ $tag }}>
