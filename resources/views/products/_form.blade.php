@php
    $isEdit = isset($product);
    $p = $isEdit ? $product : null;
@endphp

<form action="{{ $isEdit ? route('products.update', $p) : route('products.store') }}"
    method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Produk</h3>

            <x-form-field name="name" label="Nama Produk" required>
                <x-form-input name="name" :value="$p?->name" required />
            </x-form-field>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form-field name="barcode" label="Barcode">
                    <x-form-input name="barcode" :value="$p?->barcode" placeholder="Scan atau input manual" />
                </x-form-field>
                <x-form-field name="code" label="Kode Produk" hint="Otomatis jika dikosongkan">
                    <x-form-input name="code" :value="$p?->code" :disabled="$isEdit" />
                </x-form-field>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-form-field name="category_id" label="Kategori">
                    <select name="category_id" id="category_id"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">— Pilih —</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id', $p?->category_id) == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </x-form-field>

                <x-form-field name="unit_id" label="Satuan" required>
                    <select name="unit_id" id="unit_id" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">— Pilih —</option>
                        @foreach ($units as $u)
                            <option value="{{ $u->id }}" @selected(old('unit_id', $p?->unit_id) == $u->id)>{{ $u->name }} ({{ $u->symbol }})</option>
                        @endforeach
                    </select>
                </x-form-field>

                <x-form-field name="supplier_id" label="Supplier">
                    <select name="supplier_id" id="supplier_id"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">— Pilih —</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}" @selected(old('supplier_id', $p?->supplier_id) == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </x-form-field>
            </div>

            <x-form-field name="description" label="Deskripsi">
                <textarea name="description" id="description" rows="3"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description', $p?->description) }}</textarea>
            </x-form-field>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Harga & Stok</h3>

                <x-form-field name="cost_price" label="Harga Modal (Rp)" required>
                    <x-form-input name="cost_price" type="number" step="0.01" :value="$p?->cost_price ?? 0" required />
                </x-form-field>

                <x-form-field name="selling_price" label="Harga Jual (Rp)" required>
                    <x-form-input name="selling_price" type="number" step="0.01" :value="$p?->selling_price ?? 0" required />
                </x-form-field>

                <div class="grid grid-cols-2 gap-2">
                    <x-form-field name="min_stock" label="Stok Min" required>
                        <x-form-input name="min_stock" type="number" :value="$p?->min_stock ?? 0" required />
                    </x-form-field>
                    <x-form-field name="max_stock" label="Stok Maks" required>
                        <x-form-input name="max_stock" type="number" :value="$p?->max_stock ?? 0" required />
                    </x-form-field>
                </div>

                @if (! $isEdit)
                    <x-form-field name="current_stock" label="Stok Awal" hint="Stok akan otomatis terupdate dari transaksi">
                        <x-form-input name="current_stock" type="number" value="0" />
                    </x-form-field>
                @endif

                <div class="flex items-center gap-2 pt-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                        @checked(old('is_active', $p?->is_active ?? true)) class="rounded">
                    <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Produk aktif</label>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Gambar</h3>
                @if ($isEdit && $p->image)
                    <img src="{{ asset('storage/' . $p->image) }}" alt="preview"
                        class="w-32 h-32 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                @endif
                <x-form-field name="image" label="Upload Gambar">
                    <input type="file" name="image" id="image" accept="image/*"
                        class="block w-full text-sm text-gray-600 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </x-form-field>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ route('products.index') }}"
            class="px-4 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
            Batal
        </a>
        <button type="submit"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Produk' }}
        </button>
    </div>
</form>
