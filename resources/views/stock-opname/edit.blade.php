@extends('layouts.app')
@section('title', 'Edit Stock Opname ' . $opname->opname_number)
@section('content')
<div class="py-6"><div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header :title="'Edit: ' . $opname->opname_number" :back="route('stock-opname.show', $opname)" />
    @include('stock-opname._form', ['opname' => $opname])
</div></div>
@endsection
