@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header :title="'Edit: ' . $product->name" subtitle="Ubah informasi produk" :back="route('products.index')" />
        @include('products._form', ['product' => $product])
    </div>
</div>
@endsection
