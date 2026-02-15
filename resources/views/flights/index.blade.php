@extends('layouts.app')

@section('title', 'Search Flights')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Search Flights</h1>
        <p class="mt-2 text-gray-600">Find the perfect flight for your journey</p>
    </div>

    @livewire('flight-search')
</div>
@endsection
