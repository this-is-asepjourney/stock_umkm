@props([
    'title' => '',
    'subtitle' => null,
    'back' => null,
    'icon' => null,
])

<div class="relative mb-6">
    @if ($back)
        <a href="{{ $back }}"
            class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 mb-2 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
    @endif

    <div class="md:flex md:items-end md:justify-between gap-4">
        <div class="flex-1 min-w-0 flex items-center gap-3">
            @if ($icon)
                <div class="hidden sm:flex w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white items-center justify-center shadow-lg shadow-indigo-500/30 text-xl">
                    {!! $icon !!}
                </div>
            @endif
            <div class="min-w-0">
                <h2 class="text-2xl sm:text-3xl font-extrabold leading-tight text-gray-900 dark:text-white tracking-tight">
                    {{ $title }}
                </h2>
                @if ($subtitle)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        @isset($actions)
            <div class="mt-4 flex flex-wrap items-center gap-2 md:mt-0">
                {{ $actions }}
            </div>
        @endisset
    </div>

    <div class="mt-4 aj-divider"></div>
</div>
