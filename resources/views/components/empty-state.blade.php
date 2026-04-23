@props([
    'title' => 'Belum ada data',
    'message' => 'Mulai dengan menambahkan data baru.',
    'action' => null,
    'actionLabel' => 'Tambah',
    'icon' => null,
])

<div class="px-6 py-16 text-center">
    <div class="relative mx-auto w-20 h-20">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 via-purple-500/20 to-pink-500/20 rounded-2xl blur-xl"></div>
        <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-purple-500/10 ring-1 ring-indigo-500/20 flex items-center justify-center text-indigo-500 dark:text-indigo-300">
            @if ($icon)
                {!! $icon !!}
            @else
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            @endif
        </div>
    </div>
    <h3 class="mt-5 text-base font-bold text-gray-900 dark:text-white">{{ $title }}</h3>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">{{ $message }}</p>
    @if ($action)
        <div class="mt-6">
            <a href="{{ $action }}" class="aj-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                {{ $actionLabel }}
            </a>
        </div>
    @endif
</div>
