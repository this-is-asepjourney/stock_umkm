@extends('layouts.app')
@section('title', 'Pengaturan')
@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header title="Pengaturan" subtitle="Konfigurasi aplikasi" />

        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Toko</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form-field name="store_name" label="Nama Toko">
                        <x-form-input name="store_name" :value="config('app.name', 'Stock UMKM')" />
                    </x-form-field>
                    <x-form-field name="store_phone" label="Telepon">
                        <x-form-input name="store_phone" />
                    </x-form-field>
                    <div class="md:col-span-2">
                        <x-form-field name="store_address" label="Alamat">
                            <textarea name="store_address" rows="2"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                        </x-form-field>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Konfigurasi Stok</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form-field name="default_min_stock" label="Min Stock Default" hint="Digunakan saat membuat produk baru">
                        <x-form-input name="default_min_stock" type="number" min="0" value="10" />
                    </x-form-field>
                    <x-form-field name="default_max_stock" label="Max Stock Default">
                        <x-form-input name="default_max_stock" type="number" min="0" value="100" />
                    </x-form-field>
                    <x-form-field name="currency" label="Mata Uang">
                        <x-form-input name="currency" value="IDR" />
                    </x-form-field>
                    <x-form-field name="tax_rate" label="Pajak Default (%)">
                        <x-form-input name="tax_rate" type="number" step="0.01" min="0" value="0" />
                    </x-form-field>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Notifikasi</h3>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="notify_low_stock" value="1" checked
                        class="rounded border-gray-300 text-indigo-600">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Notifikasi stok rendah</span>
                </label>
                <label class="flex items-center gap-2 mt-2">
                    <input type="checkbox" name="notify_out_of_stock" value="1" checked
                        class="rounded border-gray-300 text-indigo-600">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Notifikasi stok habis</span>
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <button class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">Simpan Pengaturan</button>
            </div>
        </form>

        <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Akun & Profile</h3>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm text-white bg-slate-600 hover:bg-slate-700">
                Edit Profile
            </a>
        </div>
    </div>
</div>
@endsection
