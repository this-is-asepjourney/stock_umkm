@extends('admin.layouts.admin')

@section('title', 'Edit Plan')

@section('content')
<div class="space-y-6 max-w-3xl">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.plans.index') }}" class="p-2 rounded-lg bg-white/5 text-gray-400 hover:text-white hover:bg-white/10 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-extrabold text-white">Edit Plan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Ubah detail paket langganan {{ $plan->name }}</p>
        </div>
        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus plan ini?');">
            @csrf @method('DELETE')
            <button type="submit" class="admin-btn-ghost text-red-400 hover:text-red-300 hover:bg-red-500/10">
                Hapus Plan
            </button>
        </form>
    </div>

    <form action="{{ route('admin.plans.update', $plan) }}" method="POST" class="admin-card p-6 space-y-6">
        @csrf @method('PUT')
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="space-y-1.5 sm:col-span-2">
                <label class="text-sm font-medium text-gray-300">Nama Plan <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $plan->name) }}" class="admin-input w-full" required>
                @error('name') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-300">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="price" value="{{ old('price', (int)$plan->price) }}" class="admin-input w-full" required min="0">
                @error('price') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-300">Durasi (Hari) <span class="text-red-500">*</span></label>
                <input type="number" name="duration_in_days" value="{{ old('duration_in_days', $plan->duration_in_days) }}" class="admin-input w-full" required min="1">
                @error('duration_in_days') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-300">Maksimal User</label>
                <input type="number" name="max_users" value="{{ old('max_users', $plan->max_users) }}" class="admin-input w-full" placeholder="Kosongkan untuk unlimited" min="1">
                @error('max_users') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-300">Maksimal Produk</label>
                <input type="number" name="max_products" value="{{ old('max_products', $plan->max_products) }}" class="admin-input w-full" placeholder="Kosongkan untuk unlimited" min="1">
                @error('max_products') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
            </div>
            
            <div class="space-y-1.5 sm:col-span-2 flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-white/10 bg-white/5 text-orange-500 focus:ring-orange-500/50">
                <label for="is_active" class="text-sm font-medium text-gray-300 cursor-pointer">Plan Aktif</label>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-white/5">
            <button type="submit" class="admin-btn-primary">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
