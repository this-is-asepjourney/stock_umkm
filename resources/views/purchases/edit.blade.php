@extends('layouts.app')
@section('title', 'Edit PO ' . $purchase->po_number)
@section('content')
<div class="py-6"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-page-header :title="'Edit: ' . $purchase->po_number" :back="route('purchases.show', $purchase)" />
    @include('purchases._form', ['purchase' => $purchase])
</div></div>
@endsection
