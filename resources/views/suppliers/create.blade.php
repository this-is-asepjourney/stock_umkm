@extends('layouts.app')
@section('title', 'Tambah Supplier')
@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header title="Tambah Supplier" :back="route('suppliers.index')" />
        @include('suppliers._form')
    </div>
</div>
@endsection
