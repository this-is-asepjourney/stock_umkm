@props([
    'name' => '',
    'type' => 'text',
    'value' => null,
])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $name }}"
    value="{{ old($name, $value) }}"
    {{ $attributes->merge([
        'class' => 'w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800/80 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/30 focus:ring-opacity-50 transition',
    ]) }}
/>
