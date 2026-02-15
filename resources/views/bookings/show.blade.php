@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session()->has('success'))
            <div class="mb-5 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Status Card --}}
        <div class="tv-card p-8 text-center mb-8 relative overflow-hidden">
            @php
                $statusColor = match ($booking->status) {
                    'confirmed' => 'emerald',
                    'pending' => 'amber',
                    'cancelled' => 'red',
                    'completed' => 'blue',
                    default => 'gray'
                };
            @endphp
            <div class="absolute top-0 left-0 w-1.5 h-full bg-{{ $statusColor }}-500"></div>

            <div class="w-16 h-16 bg-{{ $statusColor }}-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                @if($booking->status === 'confirmed')
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                @elseif($booking->status === 'pending')
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @elseif($booking->status === 'cancelled')
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                @else
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @endif
            </div>

            <h1 class="text-2xl font-extrabold text-tv-text mb-1">
                @if($booking->status === 'confirmed')
                    Booking Confirmed!
                @elseif($booking->status === 'pending')
                    Payment Pending
                @elseif($booking->status === 'cancelled')
                    Booking Cancelled
                @else
                    Trip Completed
                @endif
            </h1>
            <p class="text-sm text-tv-muted mb-6">Booking Details & E-Ticket</p>

            <div class="inline-flex flex-col items-center bg-tv-bg px-8 py-4 rounded-xl border border-tv-border">
                <span class="tv-label text-[9px] mb-0.5">Booking Code</span>
                <span
                    class="font-mono font-extrabold text-tv-primary text-2xl tracking-tight">{{ $booking->booking_code }}</span>
            </div>
        </div>

        {{-- E-Ticket --}}
        <div class="tv-card mb-8">
            {{-- Ticket Header --}}
            <div class="bg-linear-to-r from-tv-primary to-tv-secondary p-6 text-white">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl p-1.5 flex items-center justify-center">
                            @if($booking->flights->first()->schedule->airline->logo_url)
                                <img src="{{ $booking->flights->first()->schedule->airline->logo_url }}"
                                    alt="{{ $booking->flights->first()->schedule->airline->name }}"
                                    class="w-full h-full object-contain">
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-sm">{{ $booking->flights->first()->schedule->airline->name }}</p>
                            <p class="text-xs text-white/60">{{ $booking->flights->first()->flight_number }}</p>
                        </div>
                    </div>
                    <span
                        class="tv-badge bg-white/15 text-white text-[10px] border border-white/20">{{ ucfirst($booking->status) }}</span>
                </div>
            </div>

            {{-- Ticket Body --}}
            <div class="p-6">
                {{-- Route --}}
                <div class="grid grid-cols-3 gap-4 items-center mb-8 py-4">
                    <div>
                        <p class="tv-label text-[9px] mb-0.5">Departure</p>
                        <p class="text-2xl font-extrabold text-tv-text tracking-tight">
                            {{ $booking->flights->first()->departure_datetime->format('H:i') }}</p>
                        <p class="text-sm font-bold text-tv-primary">
                            {{ $booking->flights->first()->schedule->originAirport->iata_code }}</p>
                        <p class="text-[10px] text-[#a0aec0]">
                            {{ $booking->flights->first()->departure_datetime->format('D, M j Y') }}</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="flex items-center justify-center w-full">
                            <div class="w-2 h-2 rounded-full border-2 border-tv-primary"></div>
                            <div class="h-px bg-tv-border flex-1"></div>
                            <svg class="w-4 h-4 text-tv-primary mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                            <div class="h-px bg-tv-border flex-1"></div>
                            <div class="w-2 h-2 rounded-full border-2 border-tv-accent"></div>
                        </div>
                        <p class="text-[9px] text-[#a0aec0] mt-1">Direct</p>
                    </div>
                    <div class="text-right">
                        <p class="tv-label text-[9px] mb-0.5">Arrival</p>
                        <p class="text-2xl font-extrabold text-tv-text tracking-tight">
                            {{ $booking->flights->first()->arrival_datetime->format('H:i') }}</p>
                        <p class="text-sm font-bold text-tv-accent">
                            {{ $booking->flights->first()->schedule->destinationAirport->iata_code }}</p>
                        <p class="text-[10px] text-[#a0aec0]">
                            {{ $booking->flights->first()->arrival_datetime->format('D, M j Y') }}</p>
                    </div>
                </div>

                <div class="tv-divider"></div>

                {{-- Passengers & Contact --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="tv-label text-[9px] mb-3">Passengers</h3>
                        <div class="space-y-2.5">
                            @foreach($booking->passengers as $passenger)
                                <div
                                    class="flex items-center justify-between bg-tv-bg p-3.5 rounded-xl border border-tv-border">
                                    <div>
                                        <p class="text-sm font-bold text-tv-text">{{ $passenger->first_name }}
                                            {{ $passenger->last_name }}</p>
                                        <p class="text-[10px] text-tv-primary font-medium">
                                            {{ $passenger->travelClass->name ?? 'Economy' }}</p>
                                    </div>
                                    @if($passenger->seatAssignment)
                                        <span
                                            class="tv-badge-blue text-[10px]">{{ $passenger->seatAssignment->seatMap->seat_number }}</span>
                                    @else
                                        <span class="text-[9px] text-amber-500 font-medium">Unassigned</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3 class="tv-label text-[9px] mb-3">Contact Information</h3>
                        <div class="bg-tv-bg p-4 rounded-xl border border-tv-border space-y-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-white rounded-lg flex items-center justify-center border border-tv-border">
                                    <svg class="w-4 h-4 text-tv-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="tv-label text-[8px] mb-0">Name</p>
                                    <p class="text-sm font-medium text-tv-text">{{ $booking->contact_name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-white rounded-lg flex items-center justify-center border border-tv-border">
                                    <svg class="w-4 h-4 text-tv-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="tv-label text-[8px] mb-0">Email</p>
                                    <p class="text-sm font-medium text-tv-text">{{ $booking->contact_email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-white rounded-lg flex items-center justify-center border border-tv-border">
                                    <svg class="w-4 h-4 text-tv-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="tv-label text-[8px] mb-0">Phone</p>
                                    <p class="text-sm font-medium text-tv-text">{{ $booking->contact_phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ticket Footer --}}
            <div class="bg-tv-bg px-6 py-4 border-t border-tv-border flex justify-between items-center">
                <div>
                    <p class="tv-label text-[9px] mb-0">Total Paid</p>
                    <p class="text-2xl font-extrabold text-tv-accent">Rp
                        {{ number_format($booking->total_amount, 0, ',', '.') }}</p>
                </div>
                <p class="text-[9px] text-[#a0aec0] max-w-[180px] text-right">E-ticket issued electronically. No paper
                    required.</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8">
            @if($booking->status === 'pending')
                <a href="{{ route('booking.payment', $booking->id) }}" class="btn-tv-accent py-3.5 text-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Pay Now
                </a>
            @elseif($booking->status === 'confirmed')
                @if(!$booking->passengers->first()->seatAssignment)
                    <a href="{{ route('booking.seats', $booking->id) }}" class="btn-tv-accent py-3.5 text-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Choose Seats
                    </a>
                @elseif($booking->flights->first()->departure_datetime->diffInHours(now()) <= 24 && $booking->flights->first()->departure_datetime->isFuture())
                    <a href="{{ route('booking.checkIn', $booking->id) }}" class="btn-tv-accent py-3.5 text-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Check-in Online
                    </a>
                @endif
            @endif

            <a href="{{ route('dashboard') }}" class="btn-tv-outline py-3.5 text-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        {{-- Reminders --}}
        <div class="tv-card bg-blue-50/60 border-blue-100 p-6">
            <h3 class="font-bold text-tv-primary text-sm mb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                Travel Reminders
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @php
                    $tips = [
                        'Check in at least 2 hours before departure',
                        'Confirmation sent to ' . $booking->contact_email,
                        'Online check-in opens 24 hours before departure',
                        'Bring a valid ID or passport for verification',
                    ];
                @endphp
                @foreach($tips as $i => $tip)
                    <div class="flex items-start gap-3">
                        <div
                            class="w-6 h-6 rounded-lg bg-white flex items-center justify-center text-tv-primary text-xs font-bold shrink-0 border border-blue-100">
                            {{ $i + 1 }}</div>
                        <p class="text-xs text-tv-muted leading-relaxed">{{ $tip }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection