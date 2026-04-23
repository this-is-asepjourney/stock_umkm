@props([
    'action' => '',
    'confirm' => 'Yakin ingin menghapus data ini?',
    'label' => 'Hapus',
])

<form action="{{ $action }}" method="POST" class="inline-block"
    onsubmit="return confirm('{{ $confirm }}');">
    @csrf
    @method('DELETE')
    <button type="submit"
        {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 font-medium text-sm']) }}>
        {{ $label }}
    </button>
</form>
