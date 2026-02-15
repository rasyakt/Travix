@extends('layouts.app')

@section('title', 'Select Seats')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h1 class="tv-section-title">Select Your Seats</h1>
            <p class="tv-section-subtitle">Choose your preferred seating for booking <span
                    class="text-tv-primary font-mono font-bold">{{ $booking->booking_code }}</span></p>
        </div>

        @livewire('seat-selection', ['bookingId' => $booking->id])
    </div>
@endsection