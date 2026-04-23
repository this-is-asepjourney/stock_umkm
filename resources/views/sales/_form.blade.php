@php
    $isEdit = isset($sale);
    $s = $isEdit ? $sale : null;
    $existingItems = $isEdit ? $s->items->map(fn($i) => [
        'product_id' => $i->product_id,
        'quantity' => $i->quantity,
        'unit_price' => (float) $i->unit_price,
        'discount' => (float) $i->discount,
    ])->values()->toArray() : [];
    $productMap = $products->mapWithKeys(fn($p) => [$p->id => [
        'name' => $p->name,
        'code' => $p->code,
        'price' => (float) $p->selling_price,
        'stock' => (int) $p->current_stock,
        'unit' => $p->unit?->symbol,
    ]])->toArray();
@endphp

<form method="POST" action="{{ $isEdit ? route('sales.update', $s) : route('sales.store') }}"
    x-data='saleForm(@json($existingItems), @json($productMap))' class="space-y-6">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Info Penjualan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form-field name="sale_date" label="Tanggal" required>
                    <x-form-input name="sale_date" type="date" :value="old('sale_date', $s?->sale_date?->format('Y-m-d')) ?: now()->format('Y-m-d')" required />
                </x-form-field>
                <x-form-field name="location_id" label="Lokasi">
                    <select name="location_id"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">—</option>
                        @foreach ($locations as $l)
                            <option value="{{ $l->id }}" @selected(old('location_id', $s?->location_id) == $l->id)>{{ $l->name }}</option>
                        @endforeach
                    </select>
                </x-form-field>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form-field name="customer_name" label="Nama Customer">
                    <x-form-input name="customer_name" :value="$s?->customer_name" placeholder="Walk-in customer" />
                </x-form-field>
                <x-form-field name="customer_phone" label="Telepon">
                    <x-form-input name="customer_phone" :value="$s?->customer_phone" />
                </x-form-field>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form-field name="customer_email" label="Email">
                    <x-form-input name="customer_email" type="email" :value="$s?->customer_email" />
                </x-form-field>
                <x-form-field name="payment_method" label="Metode Bayar" required>
                    <select name="payment_method" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @foreach (['cash'=>'Cash','transfer'=>'Transfer','qris'=>'QRIS','other'=>'Lainnya'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('payment_method', $s?->payment_method ?? 'cash') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </x-form-field>
            </div>

            <x-form-field name="notes" label="Catatan">
                <textarea name="notes" rows="2"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes', $s?->notes) }}</textarea>
            </x-form-field>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-3">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Subtotal</dt>
                    <dd class="text-gray-900 dark:text-white font-semibold" x-text="fmt(subtotal)"></dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-gray-500">Diskon</dt>
                    <dd class="w-32">
                        <input type="number" name="discount" step="0.01" min="0" x-model.number="discount"
                            class="w-full text-right rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"
                            value="{{ old('discount', (float) ($s?->discount ?? 0)) }}">
                    </dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-gray-500">Pajak</dt>
                    <dd class="w-32">
                        <input type="number" name="tax" step="0.01" min="0" x-model.number="tax"
                            class="w-full text-right rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"
                            value="{{ old('tax', (float) ($s?->tax ?? 0)) }}">
                    </dd>
                </div>
                <div class="flex justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
                    <dt class="text-gray-900 dark:text-white font-semibold">Total</dt>
                    <dd class="text-lg font-bold text-emerald-600" x-text="fmt(total)"></dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Item</h3>
            <button type="button" @click="addItem()" class="px-3 py-1.5 text-sm rounded-md text-white bg-emerald-600 hover:bg-emerald-700">+ Tambah Item</button>
        </div>
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-3 py-2 text-left w-2/5">Produk</th>
                    <th class="px-3 py-2 text-right w-20">Stok</th>
                    <th class="px-3 py-2 text-right w-24">Qty</th>
                    <th class="px-3 py-2 text-right w-32">Harga</th>
                    <th class="px-3 py-2 text-right w-28">Diskon</th>
                    <th class="px-3 py-2 text-right w-32">Subtotal</th>
                    <th class="px-3 py-2 w-12"></th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(item, idx) in items" :key="idx">
                    <tr class="border-t border-gray-200 dark:border-gray-700">
                        <td class="px-3 py-2">
                            <select :name="`items[${idx}][product_id]`" x-model.number="item.product_id"
                                @change="onProductChange(idx)" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                <option value="">— Pilih —</option>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-3 py-2 text-right text-gray-700 dark:text-gray-300" x-text="stockOf(item)"></td>
                        <td class="px-3 py-2">
                            <input type="number" min="1" step="1" :name="`items[${idx}][quantity]`"
                                x-model.number="item.quantity" required
                                class="w-full text-right rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                        </td>
                        <td class="px-3 py-2">
                            <input type="number" min="0" step="0.01" :name="`items[${idx}][unit_price]`"
                                x-model.number="item.unit_price" required
                                class="w-full text-right rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                        </td>
                        <td class="px-3 py-2">
                            <input type="number" min="0" step="0.01" :name="`items[${idx}][discount]`"
                                x-model.number="item.discount"
                                class="w-full text-right rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                        </td>
                        <td class="px-3 py-2 text-right font-semibold text-gray-900 dark:text-white" x-text="fmt(itemSubtotal(item))"></td>
                        <td class="px-3 py-2 text-center">
                            <button type="button" @click="removeItem(idx)" class="text-rose-500 hover:text-rose-700">&times;</button>
                        </td>
                    </tr>
                </template>
                <tr x-show="items.length === 0">
                    <td colspan="7" class="px-3 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada item. Klik "+ Tambah Item".</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('sales.index') }}"
            class="px-4 py-2 rounded-md text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Batal</a>
        <button class="px-4 py-2 rounded-md text-sm text-white bg-emerald-600 hover:bg-emerald-700">
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Penjualan' }}
        </button>
    </div>
</form>

@push('scripts')
<script>
    function saleForm(existing, productMap) {
        return {
            items: existing.length ? existing : [],
            productMap,
            discount: {{ (float) old('discount', $s?->discount ?? 0) }},
            tax: {{ (float) old('tax', $s?->tax ?? 0) }},
            addItem() { this.items.push({ product_id: '', quantity: 1, unit_price: 0, discount: 0 }); },
            removeItem(i) { this.items.splice(i, 1); },
            onProductChange(i) {
                const p = this.productMap[this.items[i].product_id];
                if (p) { this.items[i].unit_price = p.price; }
            },
            stockOf(it) { const p = this.productMap[it.product_id]; return p ? `${p.stock} ${p.unit || ''}` : '—'; },
            itemSubtotal(it) { return (it.quantity || 0) * (it.unit_price || 0) - (it.discount || 0); },
            get subtotal() { return this.items.reduce((s, it) => s + this.itemSubtotal(it), 0); },
            get total() { return this.subtotal - (this.discount || 0) + (this.tax || 0); },
            fmt(n) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(n)); },
            init() { if (this.items.length === 0) this.addItem(); }
        };
    }
</script>
@endpush
