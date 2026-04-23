@extends('layouts.app')
@section('title', 'Buat PO')
@section('content')
<div class="py-6"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header title="Buat Purchase Order" :back="route('purchases.index')" />
    @include('purchases._form')
</div></div>
@endsection
