@extends('layouts.app')
@section('title', 'Edit Lokasi')
@section('content')
<div class="py-6"><div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header :title="'Edit: ' . $location->name" :back="route('locations.index')" />
    @include('locations._form', ['location' => $location])
</div></div>
@endsection
