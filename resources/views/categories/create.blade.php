@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header title="Tambah Kategori" :back="route('categories.index')" />

        <form action="{{ route('categories.store') }}" method="POST"
            class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
            @csrf

            <x-form-field name="name" label="Nama Kategori" required>
                <x-form-input name="name" required />
            </x-form-field>

            <x-form-field name="parent_id" label="Parent (opsional)">
                <select name="parent_id" id="parent_id"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">— Tidak ada —</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('parent_id') == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </x-form-field>

            <div class="grid grid-cols-2 gap-4">
                <x-form-field name="color" label="Warna">
                    <input type="color" name="color" id="color" value="{{ old('color', '#6366F1') }}"
                        class="w-full h-10 rounded-md border-gray-300 dark:border-gray-600">
                </x-form-field>
                <x-form-field name="icon" label="Icon (emoji / kelas)">
                    <x-form-input name="icon" placeholder="📦 atau fa-box" />
                </x-form-field>
            </div>

            <x-form-field name="description" label="Deskripsi">
                <textarea name="description" rows="3"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description') }}</textarea>
            </x-form-field>

            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1" checked class="rounded">
                <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Aktif</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('categories.index') }}"
                    class="px-4 py-2 rounded-md text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Batal</a>
                <button class="px-4 py-2 rounded-md text-sm text-white bg-indigo-600 hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
