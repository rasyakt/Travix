@extends('layouts.app')

@section('title', 'Booking Penerbangan Partner')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-tv-secondary">Booking Penerbangan</h1>
                    <p class="text-tv-muted font-medium">Lengkapi data penumpang untuk melanjutkan</p>
                </div>
            </div>
        </div>

        {{-- Flight Information Card --}}
        <div class="bg-white rounded-2xl shadow-xl border border-tv-border p-8 mb-8">
            <h2 class="text-xl font-black text-tv-secondary mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-tv-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                </svg>
                Detail Penerbangan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="flex items-center gap-4">
                    @if(isset($flightData['airline_logo']))
                        <img src="{{ $flightData['airline_logo'] }}" alt="{{ $flightData['airline'] }}" class="h-12 w-12 rounded-lg object-contain bg-gray-50 p-2">
                    @else
                        <div class="h-12 w-12 rounded-lg bg-blue-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-tv-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <p class="font-black text-tv-text text-lg">{{ $flightData['airline'] ?? 'Unknown Airline' }}</p>
                        <p class="text-sm text-tv-muted font-mono">{{ $flightData['flight_number'] ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <div class="text-right">
                        <p class="text-sm text-tv-muted font-bold mb-1">Harga per orang</p>
                        <p class="text-3xl font-black text-tv-accent">Rp {{ number_format($flightData['price'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 items-center py-6 border-t border-b border-tv-border">
                <div>
                    <p class="text-3xl font-black text-tv-text mb-1">{{ $flightData['departure_time'] ?? 'N/A' }}</p>
                    <p class="text-sm font-bold text-tv-muted">{{ $flightData['origin'] ?? '' }}</p>
                    <p class="text-xs text-tv-muted">{{ $flightData['origin_name'] ?? '' }}</p>
                </div>

                <div class="text-center">
                    <div class="flex items-center gap-2 justify-center mb-2">
                        <div class="w-2 h-2 rounded-full bg-tv-primary/30"></div>
                        <div class="h-px bg-tv-border flex-1 max-w-[60px]"></div>
                        <svg class="w-5 h-5 text-tv-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                        </svg>
                        <div class="h-px bg-tv-border flex-1 max-w-[60px]"></div>
                        <div class="w-2 h-2 rounded-full bg-tv-accent/30"></div>
                    </div>
                    @if(isset($flightData['duration']))
                        <p class="text-xs font-bold text-tv-muted">{{ floor($flightData['duration'] / 60) }}j {{ $flightData['duration'] % 60 }}m</p>
                    @endif
                    <p class="text-xs text-tv-primary font-bold">Langsung</p>
                </div>

                <div class="text-right">
                    <p class="text-3xl font-black text-tv-text mb-1">{{ $flightData['arrival_time'] ?? 'N/A' }}</p>
                    <p class="text-sm font-bold text-tv-muted">{{ $flightData['destination'] ?? '' }}</p>
                    <p class="text-xs text-tv-muted">{{ $flightData['destination_name'] ?? '' }}</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-tv-muted font-bold mb-1">Tanggal</p>
                    <p class="text-sm font-black text-tv-text">{{ \Carbon\Carbon::parse($searchParams['departure_date'])->format('d M Y') }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-tv-muted font-bold mb-1">Kelas</p>
                    <p class="text-sm font-black text-tv-text">{{ $searchParams['seat_class'] ?? 'Economy' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-tv-muted font-bold mb-1">Penumpang</p>
                    <p class="text-sm font-black text-tv-text">{{ $passengers }} Orang</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-tv-muted font-bold mb-1">Pesawat</p>
                    <p class="text-sm font-black text-tv-text">{{ $flightData['aircraft'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Booking Form Component --}}
        @livewire('api-flight-booking-form')
    </div>
@endsection
