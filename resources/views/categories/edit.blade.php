@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Kategori</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $category->name }}</p>
                </div>

                <form action="{{ route('categories.update', $category) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Nama Kategori -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Kategori <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Category -->
                        <div>
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kategori Induk
                            </label>
                            <select name="parent_id" id="parent_id"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Tidak Ada (Kategori Utama)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Warna -->
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Warna
                            </label>
                            <div class="flex items-center space-x-3">
                                <input type="color" name="color" id="color" value="{{ old('color', $category->color) }}"
                                    class="h-10 w-20 rounded border-gray-300 dark:border-gray-600">
                            </div>
                        </div>

                        <!-- Icon -->
                        <div>
                            <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Icon (Emoji)
                            </label>
                            <input type="text" name="icon" id="icon" value="{{ old('icon', $category->icon) }}"
                                placeholder="📦"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $category->description) }}</textarea>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Aktifkan Kategori</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <a href="{{ route('categories.index') }}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700">
                            Update Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection