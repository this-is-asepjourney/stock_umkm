@extends('layouts.app')
@section('title', 'Buat Penjualan')
@section('content')
<div class="py-6"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header title="Buat Penjualan" :back="route('sales.index')" />
    @include('sales._form')
</div></div>
@endsection
