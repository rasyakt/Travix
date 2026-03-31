@extends('layouts.app')

@section('title', 'Create Booking')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @include('bookings.partials.flow-steps', ['currentStep' => 2])

        <div class="mb-6">
            <h1 class="tv-section-title">Complete Your Booking</h1>
            <p class="tv-section-subtitle">Enter passenger details and choose your class</p>
        </div>

        @livewire('booking-form', ['flightId' => $flightId, 'passengerCount' => $passengers])
    </div>
@endsection