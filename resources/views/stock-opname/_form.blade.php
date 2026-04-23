@php
    $isEdit = isset($opname);
    $o = $isEdit ? $opname : null;
@endphp
<form method="POST" action="{{ $isEdit ? route('stock-opname.update', $o) : route('stock-opname.store') }}" class="space-y-6">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-field name="opname_date" label="Tanggal Opname" required>
                <x-form-input name="opname_date" type="date" :value="old('opname_date', $o?->opname_date?->format('Y-m-d')) ?: now()->format('Y-m-d')" required />
            </x-form-field>

            <x-form-field name="category_id" label="Kategori Produk" hint="Kosongkan untuk opname semua kategori">
                <select name="category_id"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">— Semua Kategori —</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('category_id', $o?->category_id) == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </x-form-field>

            <x-form-field name="location_id" label="Lokasi">
                <select name="location_id"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">— Semua Lokasi —</option>
                    @foreach ($locations as $l)
                        <option value="{{ $l->id }}" @selected(old('location_id', $o?->location_id) == $l->id)>{{ $l->name }}</option>
                    @endforeach
                </select>
            </x-form-field>
        </div>

        <x-form-field name="notes" label="Catatan">
            <textarea name="notes" rows="3"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes', $o?->notes) }}</textarea>
        </x-form-field>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('stock-opname.index') }}"
            class="px-4 py-2 rounded-md text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Batal</a>
        <button class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">
            {{ $isEdit ? 'Simpan Perubahan' : 'Buat Sesi Opname' }}
        </button>
    </div>
</form>
