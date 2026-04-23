@props([
    'name' => '',
    'label' => '',
    'required' => false,
    'hint' => null,
])

<div {{ $attributes->only('class') }}>
    @if ($label)
        <label for="{{ $name }}" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if ($hint)
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>
