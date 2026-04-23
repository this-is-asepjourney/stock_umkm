@php $isEdit = isset($unit); $u = $isEdit ? $unit : null; @endphp

<form action="{{ $isEdit ? route('units.update', $u) : route('units.store') }}" method="POST"
    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-form-field name="name" label="Nama Satuan" required>
            <x-form-input name="name" :value="$u?->name" required placeholder="Pieces" />
        </x-form-field>
        <x-form-field name="symbol" label="Simbol" required>
            <x-form-input name="symbol" :value="$u?->symbol" required placeholder="pcs" />
        </x-form-field>
    </div>

    <x-form-field name="description" label="Deskripsi">
        <textarea name="description" rows="2"
            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description', $u?->description) }}</textarea>
    </x-form-field>

    <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ route('units.index') }}"
            class="px-4 py-2 rounded-md text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Batal</a>
        <button class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
        </button>
    </div>
</form>
