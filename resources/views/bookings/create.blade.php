@extends('layouts.app')

@section('title', 'Create Booking')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Complete Your Booking</h1>
        <p class="mt-2 text-gray-600">Enter passenger details to continue</p>
    </div>

    @livewire('booking-form', ['flightId' => $flightId, 'passengers' => $passengers])
</div>
@endsection
