@extends('layouts.app')
@section('title', 'Tambah Lokasi')
@section('content')
<div class="py-6"><div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header title="Tambah Lokasi" :back="route('locations.index')" />
    @include('locations._form')
</div></div>
@endsection
