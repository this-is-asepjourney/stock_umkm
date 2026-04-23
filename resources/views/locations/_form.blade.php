@php $isEdit = isset($location); $l = $isEdit ? $location : null; @endphp

<form action="{{ $isEdit ? route('locations.update', $l) : route('locations.store') }}" method="POST"
    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <x-form-field name="name" label="Nama Lokasi" required>
        <x-form-input name="name" :value="$l?->name" required />
    </x-form-field>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-form-field name="type" label="Tipe" required>
            <select name="type" required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @foreach (['warehouse' => 'Gudang', 'rack' => 'Rak', 'bin' => 'Bin'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('type', $l?->type) === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </x-form-field>
        <x-form-field name="parent_id" label="Parent (opsional)">
            <select name="parent_id"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">— Tidak ada —</option>
                @foreach ($locations as $loc)
                    <option value="{{ $loc->id }}" @selected(old('parent_id', $l?->parent_id) == $loc->id)>{{ $loc->name }}</option>
                @endforeach
            </select>
        </x-form-field>
    </div>

    <x-form-field name="description" label="Deskripsi">
        <textarea name="description" rows="2"
            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description', $l?->description) }}</textarea>
    </x-form-field>

    <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ route('locations.index') }}"
            class="px-4 py-2 rounded-md text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Batal</a>
        <button class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
        </button>
    </div>
</form>
