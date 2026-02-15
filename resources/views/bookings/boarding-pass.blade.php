@extends('layouts.app')

@section('title', 'Boarding Pass')

@section('content')
    <div class="max-w-xl mx-auto px-4 py-8">
        {{-- Boarding Pass Card --}}
        <div class="tv-card bg-white shadow-xl overflow-hidden print:shadow-none print:border print:rounded-none">

            {{-- Ticket Notch Decoration --}}
            <div
                class="hidden md:block absolute left-[-15px] top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-tv-bg border-r border-tv-border z-10">
            </div>
            <div
                class="hidden md:block absolute right-[-15px] top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-tv-bg border-l border-tv-border z-10">
            </div>

            {{-- Header --}}
            <div class="p-6 md:p-8 text-center border-b border-tv-border border-dashed relative">
                <h1 class="text-xl font-extrabold text-tv-text uppercase tracking-tight">Boarding Pass</h1>
                <p class="text-[10px] font-bold text-tv-muted uppercase tracking-widest mt-1">Passenger Copy ·
                    {{ $booking->booking_code }}</p>
            </div>

            {{-- Flight Info --}}
            <div class="p-6 md:p-8 space-y-8">
                {{-- Airline & Flight --}}
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        @if($booking->flight->schedule->airline->logo_url)
                            <img src="{{ $booking->flight->schedule->airline->logo_url }}" alt=""
                                class="h-10 w-10 object-contain">
                        @endif
                        <div>
                            <p class="font-extrabold text-tv-text">{{ $booking->flight->schedule->airline->name }}</p>
                            <p class="text-xs font-bold text-tv-primary">{{ $booking->flight->flight_number }}</p>
                        </div>
                    </div>
                    <div
                        class="bg-linear-to-r from-tv-primary to-tv-secondary px-4 py-1.5 rounded-lg text-white font-bold text-xs uppercase">
                        {{ $booking->flight->departure_datetime->format('M d, Y') }}
                    </div>
                </div>

                {{-- Route --}}
                <div class="grid grid-cols-3 gap-4 items-center relative py-4">
                    <div class="text-center md:text-left">
                        <p class="tv-label text-[9px] mb-0.5">FROM</p>
                        <p class="text-3xl font-black text-tv-text tracking-tighter">
                            {{ $booking->flight->schedule->originAirport->iata_code }}</p>
                        <p class="text-[10px] font-bold text-tv-primary uppercase truncate">
                            {{ $booking->flight->schedule->originAirport->city }}</p>
                        <div class="mt-4 md:mt-6">
                            <p class="tv-label text-[9px] mb-0.5">TIME</p>
                            <p class="text-xl font-extrabold text-tv-text">
                                {{ $booking->flight->departure_datetime->format('H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center">
                        <div class="flex items-center justify-center w-full mb-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-tv-primary"></div>
                            <div class="h-px bg-tv-border flex-1 mx-1"></div>
                            <svg class="w-5 h-5 text-tv-primary rotate-90" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                            <div class="h-px bg-tv-border flex-1 mx-1"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-tv-accent"></div>
                        </div>
                    </div>

                    <div class="text-center md:text-right">
                        <p class="tv-label text-[9px] mb-0.5">TO</p>
                        <p class="text-3xl font-black text-tv-text tracking-tighter">
                            {{ $booking->flight->schedule->destinationAirport->iata_code }}</p>
                        <p class="text-[10px] font-bold text-tv-accent uppercase truncate">
                            {{ $booking->flight->schedule->destinationAirport->city }}</p>
                        <div class="mt-4 md:mt-6">
                            <p class="tv-label text-[9px] mb-0.5">ARRIVE</p>
                            <p class="text-xl font-extrabold text-tv-text">
                                {{ $booking->flight->arrival_datetime->format('H:i') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Passenger Info Table-like grid --}}
                <div class="grid grid-cols-2 gap-y-6 gap-x-12 border-t border-tv-border pt-8">
                    <div>
                        <p class="tv-label text-[9px] mb-0.5">PASSENGER</p>
                        <p class="text-sm font-extrabold text-tv-text uppercase">
                            {{ $booking->passengers->first()->first_name }} {{ $booking->passengers->first()->last_name }}
                        </p>
                    </div>
                    <div>
                        <p class="tv-label text-[9px] mb-0.5">CLASS</p>
                        <p class="text-sm font-extrabold text-tv-primary uppercase">
                            {{ $booking->passengers->first()->travelClass->name ?? 'Economy' }}</p>
                    </div>
                    <div>
                        <p class="tv-label text-[9px] mb-0.5">GATE</p>
                        <p class="text-sm font-extrabold text-tv-text uppercase">{{ $booking->flight->gate ?? 'TBA' }}</p>
                    </div>
                    <div>
                        <p class="tv-label text-[9px] mb-0.5">BOARDING</p>
                        <p class="text-sm font-extrabold text-tv-accent uppercase">
                            {{ $booking->flight->departure_datetime->subMinutes(45)->format('H:i') }}</p>
                    </div>
                    <div>
                        <p class="tv-label text-[9px] mb-0.5">SEAT</p>
                        <p class="text-3xl font-black text-tv-primary uppercase">
                            @if($booking->passengers->first()->seatAssignment)
                                {{ $booking->passengers->first()->seatAssignment->seatMap->seat_number }}
                            @else
                                <span class="text-lg">TBA</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- QR Code Section --}}
            <div class="bg-tv-bg p-8 flex flex-col items-center border-t border-tv-border border-dashed">
                <div class="bg-white p-4 rounded-2xl border border-tv-border shadow-sm mb-4">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($booking->booking_code) !!}
                </div>
                <p class="text-[10px] font-bold text-tv-muted text-center max-w-[200px] leading-relaxed">
                    SCAN THIS QR AT THE BOARDING GATE OR SCAN FOR SELF-SERVICE KIOSK
                </p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-8 flex flex-col sm:flex-row gap-3 no-print">
            <button onclick="window.print()" class="btn-tv-primary w-full gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2H7a2 2 0 00-2 2v4m14 0h-2" />
                </svg>
                Print Boarding Pass
            </button>
            <a href="{{ route('dashboard') }}" class="btn-tv-ghost w-full">Back to Dashboard</a>
        </div>

        {{-- Tips --}}
        <div class="mt-8 p-6 bg-blue-50/50 rounded-2xl border border-blue-100/50 no-print">
            <h3 class="text-xs font-bold text-tv-primary uppercase mb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                Boarding Instructions
            </h3>
            <ul class="space-y-3">
                <li class="flex gap-3">
                    <span
                        class="w-5 h-5 rounded-lg bg-white flex items-center justify-center text-tv-primary text-[10px] font-bold border border-blue-100 shrink-0">1</span>
                    <p class="text-[11px] text-tv-muted leading-relaxed">Boarding gate closes 20 minutes before departure
                        time.</p>
                </li>
                <li class="flex gap-3">
                    <span
                        class="w-5 h-5 rounded-lg bg-white flex items-center justify-center text-tv-primary text-[10px] font-bold border border-blue-100 shrink-0">2</span>
                    <p class="text-[11px] text-tv-muted leading-relaxed">Please have your ID card or Passport ready for
                        verification at the gate.</p>
                </li>
            </ul>
        </div>
    </div>
@endsection