@extends('layouts.app')
@section('title', 'Edit Penjualan ' . $sale->sale_number)
@section('content')
<div class="py-6"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header :title="'Edit: ' . $sale->sale_number" :back="route('sales.show', $sale)" />
    @include('sales._form', ['sale' => $sale])
</div></div>
@endsection
