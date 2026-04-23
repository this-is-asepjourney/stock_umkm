@extends('layouts.app')
@section('title', 'Buat Stock Opname')
@section('content')
<div class="py-6"><div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header title="Buat Stock Opname" subtitle="Sistem akan otomatis generate daftar produk sesuai filter" :back="route('stock-opname.index')" />
    @include('stock-opname._form')
</div></div>
@endsection
