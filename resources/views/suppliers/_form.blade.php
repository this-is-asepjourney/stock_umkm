@php
    $isEdit = isset($supplier);
    $s = $isEdit ? $supplier : null;
@endphp

<form action="{{ $isEdit ? route('suppliers.update', $s) : route('suppliers.store') }}"
    method="POST" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-form-field name="name" label="Nama Supplier" required>
            <x-form-input name="name" :value="$s?->name" required />
        </x-form-field>
        <x-form-field name="contact_person" label="Nama Kontak">
            <x-form-input name="contact_person" :value="$s?->contact_person" />
        </x-form-field>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-form-field name="phone" label="Telepon">
            <x-form-input name="phone" :value="$s?->phone" />
        </x-form-field>
        <x-form-field name="email" label="Email">
            <x-form-input name="email" type="email" :value="$s?->email" />
        </x-form-field>
    </div>

    <x-form-field name="address" label="Alamat">
        <textarea name="address" rows="2"
            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('address', $s?->address) }}</textarea>
    </x-form-field>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-form-field name="payment_term" label="Termin Pembayaran" hint="Contoh: 'NET 30' atau 'Cash'">
            <x-form-input name="payment_term" :value="$s?->payment_term" />
        </x-form-field>
        <div class="flex items-center gap-2 pt-6">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" id="is_active" name="is_active" value="1"
                @checked(old('is_active', $s?->is_active ?? true)) class="rounded">
            <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Aktif</label>
        </div>
    </div>

    <x-form-field name="notes" label="Catatan">
        <textarea name="notes" rows="2"
            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes', $s?->notes) }}</textarea>
    </x-form-field>

    <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ route('suppliers.index') }}"
            class="px-4 py-2 rounded-md text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Batal</a>
        <button class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Supplier' }}
        </button>
    </div>
</form>
