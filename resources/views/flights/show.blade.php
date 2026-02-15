@extends('layouts.app')

@section('title', $flight->flight_number)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-tv-text tracking-tight">{{ $flight->flight_number }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-sm font-bold text-tv-primary">{{ $flight->schedule->originAirport->city }}</span>
                    <svg class="w-4 h-4 text-tv-border" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    <span class="text-sm font-bold text-tv-accent">{{ $flight->schedule->destinationAirport->city }}</span>
                </div>
            </div>
            <span
                class="tv-badge {{ $flight->status === 'active' || $flight->status === 'scheduled' ? 'bg-emerald-50 text-emerald-600' : ($flight->status === 'delayed' ? 'bg-amber-50 text-amber-600' : ($flight->status === 'cancelled' ? 'bg-red-50 text-red-600' : 'bg-gray-100 text-[#687b8e]')) }}">
                ● {{ ucfirst($flight->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-8 space-y-6">
                {{-- Timeline --}}
                <div class="tv-card p-6 md:p-8">
                    <h2 class="font-bold text-tv-text mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Flight Schedule
                    </h2>

                    <div class="relative">
                        <div class="absolute left-5 top-8 bottom-8 w-0.5 bg-tv-border"></div>

                        {{-- Departure --}}
                        <div class="relative flex gap-6 mb-10">
                            <div
                                class="w-10 h-10 bg-white border-4 border-blue-50 rounded-full flex items-center justify-center z-10">
                                <div class="w-2.5 h-2.5 bg-tv-primary rounded-full"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-xl font-extrabold text-tv-text">
                                        {{ $flight->departure_datetime->format('H:i') }}</p>
                                    <p class="text-xs font-medium text-tv-muted">
                                        {{ $flight->departure_datetime->format('D, M j Y') }}</p>
                                </div>
                                <div class="mt-1.5">
                                    <p class="text-sm font-bold text-tv-primary">
                                        {{ $flight->schedule->originAirport->iata_code }}</p>
                                    <p class="text-xs text-tv-muted">{{ $flight->schedule->originAirport->name }}</p>
                                    @if($flight->terminal)
                                        <div class="flex gap-2 mt-2">
                                            <span class="tv-badge-gray text-[9px]">Terminal {{ $flight->terminal }}</span>
                                            @if($flight->gate)<span class="tv-badge-gray text-[9px]">Gate
                                            {{ $flight->gate }}</span>@endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Duration --}}
                        <div class="relative flex gap-6 mb-10 items-center">
                            <div class="w-10 flex justify-center z-10">
                                <svg class="w-5 h-5 text-[#a0aec0]" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" />
                                </svg>
                            </div>
                            <div class="tv-badge-blue text-xs">
                                {{ floor($flight->departure_datetime->diffInMinutes($flight->arrival_datetime) / 60) }}h
                                {{ $flight->departure_datetime->diffInMinutes($flight->arrival_datetime) % 60 }}m · <span
                                    class="text-emerald-600">Direct</span>
                            </div>
                        </div>

                        {{-- Arrival --}}
                        <div class="relative flex gap-6">
                            <div
                                class="w-10 h-10 bg-white border-4 border-orange-50 rounded-full flex items-center justify-center z-10">
                                <div class="w-2.5 h-2.5 bg-tv-accent rounded-full"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-xl font-extrabold text-tv-text">
                                        {{ $flight->arrival_datetime->format('H:i') }}</p>
                                    <p class="text-xs font-medium text-tv-muted">
                                        {{ $flight->arrival_datetime->format('D, M j Y') }}</p>
                                </div>
                                <div class="mt-1.5">
                                    <p class="text-sm font-bold text-tv-accent">
                                        {{ $flight->schedule->destinationAirport->iata_code }}</p>
                                    <p class="text-xs text-tv-muted">{{ $flight->schedule->destinationAirport->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Airline & Aircraft --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="tv-card p-5 flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-50 rounded-xl p-2 shrink-0">
                            @if($flight->schedule->airline->logo_url)
                                <img src="{{ $flight->schedule->airline->logo_url }}"
                                    alt="{{ $flight->schedule->airline->name }}" class="w-full h-full object-contain">
                            @endif
                        </div>
                        <div>
                            <p class="tv-label text-[9px] mb-0">Operated By</p>
                            <p class="font-bold text-tv-text text-sm">{{ $flight->schedule->airline->name }}</p>
                            <p class="text-xs text-tv-primary font-medium">{{ $flight->schedule->airline->iata_code }}
                                Airlines</p>
                        </div>
                    </div>
                    <div class="tv-card p-5 flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-tv-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                        </div>
                        <div>
                            <p class="tv-label text-[9px] mb-0">Aircraft</p>
                            <p class="font-bold text-tv-text text-sm">{{ $flight->schedule->aircraft->manufacturer->name }}
                                {{ $flight->schedule->aircraft->model }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <p class="text-[10px] text-emerald-600 font-medium">
                                    {{ $flight->schedule->aircraft->typical_seating_capacity }} passengers</p>
                                @if($flight->schedule->aircraft->legroom)
                                    <span class="text-tv-border text-[9px]">·</span>
                                    <p class="text-[10px] text-tv-primary font-bold">Pitch: {{ $flight->schedule->aircraft->legroom }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Amenities Section --}}
                @if($flight->schedule->aircraft->amenities && count($flight->schedule->aircraft->amenities) > 0)
                    <div class="tv-card p-6">
                        <h3 class="font-bold text-tv-text mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                            Fasilitas Penerbangan
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($flight->schedule->aircraft->amenities as $amenity)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                    @php
                                        $isWifi = str_contains(strtolower($amenity), 'wifi');
                                        $isPower = str_contains(strtolower($amenity), 'power') || str_contains(strtolower($amenity), 'usb');
                                        $isFood = str_contains(strtolower($amenity), 'food') || str_contains(strtolower($amenity), 'meal');
                                        $isEntertainment = str_contains(strtolower($amenity), 'entertainment') || str_contains(strtolower($amenity), 'video');
                                    @endphp
                                    @if($isWifi)
                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-4.08-7.071a9 9 0 0112.14 0M4.929 7.929a13 13 0 0118.142 0"/></svg>
                                    @elseif($isPower)
                                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    @elseif($isFood)
                                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    @else
                                        <svg class="w-5 h-5 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                    <span class="text-xs font-bold text-tv-text">{{ $amenity }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Fare Options --}}
                @if($flightSeatPrices->count() > 0)
                    <div>
                        <h3 class="font-bold text-tv-text mb-3">Fare Options</h3>
                        <div class="space-y-3">
                            @foreach($flightSeatPrices as $seatPrice)
                                <div class="tv-card-hover p-5">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-3">
                                            <div @class([
                                                'w-3 h-3 rounded-full',
                                                'bg-tv-primary' => $seatPrice->travelClass->name == 'Economy',
                                                'bg-purple-500' => $seatPrice->travelClass->name == 'Business',
                                                'bg-amber-500' => $seatPrice->travelClass->name == 'First Class',
                                                'bg-tv-muted' => !in_array($seatPrice->travelClass->name, ['Economy', 'Business', 'First Class'])
                                            ])></div>
                                            <div>
                                                <p class="font-bold text-tv-text">{{ $seatPrice->travelClass->name }}</p>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    <span
                                                        class="text-[10px] text-tv-muted font-medium">{{ $seatPrice->available_seats }}
                                                        left</span>
                                                    <span class="text-tv-border">·</span>
                                                    <span class="text-[10px] text-emerald-600 font-medium">20kg baggage</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xl font-extrabold text-tv-accent">
                                                ${{ number_format($seatPrice->price, 2) }}</p>
                                            <p class="text-[10px] text-[#a0aec0]">/person</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-4 space-y-5">
                <div class="tv-card sticky top-20">
                    <div class="bg-linear-to-r from-tv-primary to-tv-secondary p-5 text-white">
                        <h3 class="font-bold">Pricing Summary</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-medium text-tv-muted">Base Fare</span>
                            <span class="font-bold text-tv-text">${{ number_format($flight->current_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-medium text-tv-muted">Airport Tax</span>
                            <span class="tv-badge-green text-[9px]">Included</span>
                        </div>
                        <div class="border-t border-dashed border-tv-border pt-4 flex justify-between items-center">
                            <span class="text-sm font-bold text-tv-text">Total</span>
                            <span
                                class="text-2xl font-extrabold text-tv-accent">${{ number_format($flight->current_price, 2) }}</span>
                        </div>

                        @auth
                            @if($flight->status === 'scheduled' && $flight->available_seats > 0)
                                <a href="{{ route('booking.create', ['flight' => $flight->id]) }}"
                                    class="btn-tv-accent w-full block text-center py-3.5">Book Now</a>
                            @else
                                <button disabled
                                    class="w-full bg-gray-100 text-[#a0aec0] font-bold py-3.5 rounded-xl cursor-not-allowed">
                                    {{ $flight->available_seats === 0 ? 'Sold Out' : 'Unavailable' }}
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-tv-primary w-full block text-center py-3.5">Login to
                                Book</a>
                        @endauth

                        <p class="text-[9px] text-center text-[#a0aec0]">Secure transaction & guaranteed privacy</p>
                    </div>
                </div>

                {{-- Status Log --}}
                @if($flight->statusLogs->count() > 0)
                    <div class="tv-card p-5">
                        <h4 class="font-bold text-tv-text text-sm mb-4 flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-tv-primary"></div>
                            Status Updates
                        </h4>
                        <div class="space-y-3">
                            @foreach($flight->statusLogs->take(3) as $log)
                                <div class="relative pl-5 border-l-2 border-tv-border">
                                    <div class="absolute left-[-5px] top-1.5 w-2 h-2 rounded-full bg-tv-border"></div>
                                    <p class="text-xs font-medium text-tv-text">
                                        Status: <span class="text-tv-primary">{{ ucfirst($log->new_status) }}</span>
                                    </p>
                                    <p class="text-[10px] text-[#a0aec0] mt-0.5">{{ $log->changed_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection