@props([
    'color' => 'slate',
    'label' => '',
    'dot' => false,
])

@php
    $map = [
        'emerald' => ['bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300 ring-1 ring-emerald-500/20', 'bg-emerald-500'],
        'green' => ['bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-300 ring-1 ring-green-500/20', 'bg-green-500'],
        'amber' => ['bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300 ring-1 ring-amber-500/20', 'bg-amber-500'],
        'yellow' => ['bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-300 ring-1 ring-yellow-500/20', 'bg-yellow-500'],
        'rose' => ['bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300 ring-1 ring-rose-500/20', 'bg-rose-500'],
        'red' => ['bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-300 ring-1 ring-red-500/20', 'bg-red-500'],
        'indigo' => ['bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300 ring-1 ring-indigo-500/20', 'bg-indigo-500'],
        'blue' => ['bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300 ring-1 ring-blue-500/20', 'bg-blue-500'],
        'violet' => ['bg-violet-100 text-violet-700 dark:bg-violet-500/15 dark:text-violet-300 ring-1 ring-violet-500/20', 'bg-violet-500'],
        'slate' => ['bg-slate-100 text-slate-700 dark:bg-slate-700/60 dark:text-slate-200 ring-1 ring-slate-500/20', 'bg-slate-500'],
    ];
    [$chip, $dotClr] = $map[$color] ?? $map['slate'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold {$chip}"]) }}>
    @if ($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotClr }}"></span>
    @endif
    {{ $label ?: $slot }}
</span>
