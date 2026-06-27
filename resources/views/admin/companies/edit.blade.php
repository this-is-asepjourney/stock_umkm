@extends('admin.layouts.admin')

@section('title', 'Edit Langganan Klien')

@section('content')
<div class="space-y-6 max-w-3xl">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.companies.show', $company->id) }}" class="p-2 rounded-lg bg-white/5 text-gray-400 hover:text-white hover:bg-white/10 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-white">Edit Langganan Klien</h1>
            <p class="text-sm text-gray-500 mt-0.5">Ubah status dan paket langganan untuk {{ $company->name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.companies.update', $company) }}" method="POST" class="admin-card p-6 space-y-6">
        @csrf @method('PUT')
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="space-y-1.5 sm:col-span-2">
                <label class="text-sm font-medium text-gray-300">Pilih Plan</label>
                <select name="plan_id" class="admin-input w-full" style="appearance: auto;">
                    <option value="">-- Tidak Ada Plan --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id', $company->plan_id) == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} (Rp {{ number_format($plan->price, 0, ',', '.') }} / {{ $plan->duration_in_days }} Hari)
                        </option>
                    @endforeach
                </select>
                @error('plan_id') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-300">Status Langganan</label>
                <select name="subscription_status" class="admin-input w-full" style="appearance: auto;" required>
                    <option value="trial" {{ old('subscription_status', $company->subscription_status) == 'trial' ? 'selected' : '' }}>Trial</option>
                    <option value="active" {{ old('subscription_status', $company->subscription_status) == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('subscription_status', $company->subscription_status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    <option value="cancelled" {{ old('subscription_status', $company->subscription_status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                @error('subscription_status') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-300">Berakhir Pada</label>
                <input type="datetime-local" name="subscription_ends_at" value="{{ old('subscription_ends_at', $company->subscription_ends_at ? $company->subscription_ends_at->format('Y-m-d\TH:i') : '') }}" class="admin-input w-full">
                @error('subscription_ends_at') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
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
