@extends('layouts.app')
@section('title', 'Tambah Satuan')
@section('content')
<div class="py-6"><div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header title="Tambah Satuan" :back="route('units.index')" />
    @include('units._form')
</div></div>
@endsection
