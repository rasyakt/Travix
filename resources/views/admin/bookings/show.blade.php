@extends('layouts.app')

@section('title', 'Booking Details - Admin')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.bookings.index') }}" class="p-2 hover:bg-gray-100 rounded-xl transition-colors">
                <svg class="w-5 h-5 text-tv-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-extrabold text-tv-text tracking-tight">Booking Details</h1>
                <p class="text-xs text-tv-muted">Referral Code: <span
                        class="font-mono font-bold text-tv-primary">{{ $booking->booking_code }}</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Details --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Reservation Info --}}
                <div class="tv-card p-6">
                    <h2 class="font-bold text-tv-text mb-5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Reservation Information
                    </h2>

                    <div class="grid grid-cols-2 gap-y-4">
                        <div>
                            <p class="tv-label text-[10px] mb-0.5">Customer</p>
                            <p class="text-sm font-bold text-tv-text">{{ $booking->user->name }}</p>
                            <p class="text-[10px] text-tv-muted">{{ $booking->user->email }}</p>
                        </div>
                        <div>
                            <p class="tv-label text-[10px] mb-0.5">Booking Date</p>
                            <p class="text-sm font-bold text-tv-text">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="tv-label text-[10px] mb-0.5">Contact Method</p>
                            <p class="text-sm font-bold text-tv-text">{{ $booking->contact_phone }}</p>
                            <p class="text-[10px] text-tv-muted">{{ $booking->contact_email }}</p>
                        </div>
                        <div>
                            <p class="tv-label text-[10px] mb-0.5">Status</p>
                            <span class="tv-badge {{ 
                                    match ($booking->status) {
            'confirmed' => 'bg-emerald-50 text-emerald-600',
            'pending' => 'bg-amber-50 text-amber-600',
            'cancelled' => 'bg-red-50 text-red-600',
            default => 'bg-gray-100 text-gray-600'
        }
                                }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Flights --}}
                <div class="tv-card p-6">
                    <h2 class="font-bold text-tv-text mb-5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-tv-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                        </svg>
                        Flight Content
                    </h2>

                    @foreach($booking->flights as $flight)
                        <div class="bg-tv-bg p-4 rounded-xl border border-tv-border mb-3 last:mb-0">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-white rounded-lg p-1 border border-tv-border flex items-center justify-center">
                                        @if($flight->schedule->airline->logo_url)
                                            <img src="{{ $flight->schedule->airline->logo_url }}"
                                                class="w-full h-full object-contain">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-tv-text">{{ $flight->flight_number }}</p>
                                        <p class="text-[10px] text-tv-muted">{{ $flight->schedule->airline->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-tv-primary">
                                        {{ $flight->departure_datetime->format('M d, H:i') }}</p>
                                    <p class="text-[10px] text-tv-muted">Departure</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-2 px-1">
                                <div class="text-center">
                                    <p class="text-lg font-extrabold text-tv-text">
                                        {{ $flight->schedule->originAirport->iata_code }}</p>
                                    <p class="text-[9px] text-tv-muted">{{ $flight->schedule->originAirport->city }}</p>
                                </div>
                                <div class="flex-1 px-4">
                                    <div class="h-px bg-tv-border relative">
                                        <div class="absolute inset-0 flex items-center justify-center -top-2">
                                            <svg class="w-4 h-4 text-tv-primary bg-tv-bg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p class="text-lg font-extrabold text-tv-text">
                                        {{ $flight->schedule->destinationAirport->iata_code }}</p>
                                    <p class="text-[9px] text-tv-muted">{{ $flight->schedule->destinationAirport->city }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Passengers --}}
                <div class="tv-card p-6">
                    <h2 class="font-bold text-tv-text mb-5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Passengers ({{ $booking->passengers->count() }})
                    </h2>

                    <div class="space-y-3">
                        @foreach($booking->passengers as $passenger)
                            <div class="flex items-center justify-between p-4 bg-tv-bg rounded-xl border border-tv-border">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center border border-tv-border text-tv-primary font-bold">
                                        {{ substr($passenger->first_name, 0, 1) }}{{ substr($passenger->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-tv-text">{{ $passenger->first_name }}
                                            {{ $passenger->last_name }}</p>
                                        <p class="text-[10px] text-tv-muted">{{ $passenger->travelClass->name ?? 'Economy' }} ·
                                            {{ $passenger->nationality }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($passenger->seatAssignment)
                                        <span class="tv-badge-blue text-[10px]">Seat:
                                            {{ $passenger->seatAssignment->seatMap->seat_number }}</span>
                                    @else
                                        <span class="text-[9px] text-amber-500 font-medium italic underline">No Seat</span>
                                    @endif

                                    @if($passenger->boardingPass)
                                        <div class="mt-1">
                                            <span class="text-[9px] text-emerald-500 font-bold uppercase tracking-tight">✓ Checked
                                                In</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right Column: Stats & Actions --}}
            <div class="space-y-6">
                {{-- Payment Card --}}
                <div class="tv-card p-6 bg-linear-to-br from-tv-primary to-tv-secondary text-white">
                    <h3 class="font-bold text-sm mb-4">Financial Summary</h3>
                    <div class="space-y-3 pb-4 mb-4 border-b border-white/10">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-white/60">Base Fare</span>
                            <span class="font-bold">Rp {{ number_format($booking->base_fare, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-white/60">Taxes & Fees</span>
                            <span class="font-bold">Rp {{ number_format($booking->taxes_fees, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-white/60">Extras</span>
                            <span class="font-bold">Rp
                                {{ number_format($booking->baggage_fee + $booking->seat_fee, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="text-white/60 text-xs uppercase tracking-widest font-bold">Total Amount</span>
                        <span class="text-2xl font-extrabold">Rp
                            {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                    </div>

                    @if($booking->payment)
                        <div class="mt-5 p-3 rounded-xl bg-white/10 border border-white/20">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[10px] text-white/50">Payment Status</span>
                                <span class="text-[10px] font-bold uppercase">{{ $booking->payment->status }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] text-white/50">Method</span>
                                <span
                                    class="text-[10px] font-bold capitalize">{{ str_replace('_', ' ', $booking->payment->payment_method ?? 'Not Selected') }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Quick Actions --}}
                <div class="tv-card p-6">
                    <h3 class="font-bold text-tv-text text-sm mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        @if($booking->status === 'pending')
                            <button class="w-full btn-tv-accent py-2.5 text-xs">Send Reminder</button>
                        @endif
                        <button
                            class="w-full bg-white border border-tv-border text-tv-text font-bold py-2.5 rounded-xl text-xs hover:bg-gray-50 transition-colors">Export
                            as PDF</button>
                        <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this booking?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full border border-red-100 text-red-500 font-bold py-2.5 rounded-xl text-xs hover:bg-red-50 transition-colors mt-2">Delete
                                Booking</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection