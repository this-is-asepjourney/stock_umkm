@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header title="Tambah Produk" subtitle="Tambahkan produk baru ke katalog" :back="route('products.index')" />
        @include('products._form')
    </div>
</div>
@endsection
