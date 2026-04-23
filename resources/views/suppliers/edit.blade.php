@extends('layouts.app')
@section('title', 'Edit Supplier')
@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-page-header :title="'Edit: ' . $supplier->name" :back="route('suppliers.index')" />
        @include('suppliers._form', ['supplier' => $supplier])
    </div>
</div>
@endsection
