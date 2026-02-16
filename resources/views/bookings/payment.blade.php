@extends('layouts.app')

@section('title', 'Payment')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="tv-section-title">Complete Payment</h1>
            <p class="tv-section-subtitle">Booking <span
                    class="font-mono font-bold text-tv-primary">{{ $booking->booking_code }}</span></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-5">
                {{-- Booking Summary --}}
                <div class="tv-card p-5">
                    <h2 class="font-bold text-tv-text mb-4">Booking Summary</h2>

                    {{-- Flight --}}
                    <div class="pb-4 mb-4 border-b border-tv-border">
                        <div class="flex items-center gap-3 mb-3">
                            @if($booking->flight->schedule->airline->logo_url)
                                <img src="{{ $booking->flight->schedule->airline->logo_url }}" alt=""
                                    class="h-8 w-8 rounded-lg object-contain bg-gray-50 p-1">
                            @endif
                            <div>
                                <p class="font-bold text-tv-text text-sm">{{ $booking->flight->flight_number }}</p>
                                <p class="text-xs text-tv-muted">{{ $booking->flight->schedule->airline->name }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3 items-center">
                            <div>
                                <p class="text-lg font-extrabold text-tv-text">
                                    {{ $booking->flight->schedule->originAirport->iata_code }}</p>
                                <p class="text-[10px] text-[#a0aec0]">
                                    {{ $booking->flight->departure_datetime->format('M d, H:i') }}</p>
                            </div>
                            <div class="flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full border-2 border-tv-primary"></div>
                                <div class="h-px bg-tv-border flex-1"></div>
                                <svg class="w-3 h-3 text-tv-primary mx-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                </svg>
                                <div class="h-px bg-tv-border flex-1"></div>
                                <div class="w-2 h-2 rounded-full border-2 border-tv-accent"></div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-extrabold text-tv-text">
                                    {{ $booking->flight->schedule->destinationAirport->iata_code }}</p>
                                <p class="text-[10px] text-[#a0aec0]">
                                    {{ $booking->flight->arrival_datetime->format('M d, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Passengers --}}
                    <div class="pb-4 mb-4 border-b border-tv-border">
                        <h3 class="tv-label text-[9px] mb-2.5">Passengers ({{ $booking->passengers->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($booking->passengers as $passenger)
                                <div
                                    class="flex justify-between items-center bg-tv-bg p-3 rounded-xl border border-tv-border">
                                    <div>
                                        <p class="text-sm font-bold text-tv-text">{{ $passenger->first_name }}
                                            {{ $passenger->last_name }}</p>
                                        <p class="text-[10px] text-tv-muted">
                                            {{ $passenger->travelClass->name ?? 'Economy' }} ·
                                            Seat: @if($passenger->seatAssignment)
                                            {{ $passenger->seatAssignment->seatMap->seat_number }} @else <span
                                            class="text-amber-500">—</span> @endif
                                        </p>
                                    </div>
                                    <span class="text-sm font-bold text-tv-text">Rp
                                        {{ number_format($passenger->ticket_price, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Baggage --}}
                    @if($booking->baggage->count() > 0)
                        <div>
                            <h3 class="tv-label text-[9px] mb-2.5">Additional Baggage</h3>
                            <div class="space-y-2">
                                @foreach($booking->baggage as $baggage)
                                    <div
                                        class="flex justify-between items-center bg-tv-bg p-3 rounded-xl border border-tv-border">
                                        <p class="text-sm text-tv-text font-medium">{{ $baggage->weight_kg }}kg</p>
                                        <span class="text-sm font-bold text-tv-text">Rp
                                            {{ number_format($baggage->fee, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Payment Methods --}}
                <form method="POST" action="{{ route('booking.payment.process', $booking->id) }}" class="tv-card p-5">
                    @csrf
                    <h2 class="font-bold text-tv-text mb-4">Payment Method</h2>

                    <div class="space-y-2.5">
                        @foreach([
                            ['value' => 'credit_card', 'label' => 'Credit/Debit Card', 'desc' => 'Visa, Mastercard, Amex', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'checked' => true],
                            ['value' => 'bank_transfer', 'label' => 'Bank Transfer', 'desc' => 'All major banks', 'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z', 'checked' => false],
                            ['value' => 'e_wallet', 'label' => 'E-Wallet', 'desc' => 'GoPay, OVO, Dana', 'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', 'checked' => false],
                        ] as $method)
                            <label class="flex cursor-pointer rounded-xl border-2 border-tv-border p-4 transition-all
                                hover:border-tv-primary/30
                                has-checked:border-tv-primary has-checked:bg-blue-50/50">

                                <input type="radio" name="payment_method" value="{{ $method['value'] }}" class="sr-only" {{ $method['checked'] ? 'checked' : '' }}>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $method['icon'] }}"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-tv-text text-sm">{{ $method['label'] }}</p>
                                        <p class="text-xs text-tv-muted">{{ $method['desc'] }}</p>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @auth
                        <button type="submit" class="w-full btn-tv-accent py-3.5 mt-5 text-sm">Proceed to Payment</button>
                    @else
                        <div class="mt-5 p-4 bg-amber-50 rounded-xl border border-amber-200">
                            <p class="text-amber-800 text-xs font-bold mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Login required to complete payment
                            </p>
                            <a href="{{ route('login') }}" class="w-full btn-tv-primary py-3.5 text-sm inline-block text-center">Login with Google</a>
                        </div>
                    @endauth
                </form>
            </div>

            {{-- Price Sidebar --}}
            <div class="lg:col-span-1">
                <div class="tv-card bg-linear-to-br from-tv-primary to-tv-secondary text-white sticky top-20">
                    <div class="p-5">
                        <h3 class="font-bold text-sm mb-4">Price Summary</h3>
                        <div class="space-y-2.5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-white/60">Tickets ({{ $booking->passengers->count() }} pax)</span>
                                <span class="font-bold">Rp {{ number_format($booking->passengers->sum('ticket_price'), 0, ',', '.') }}</span>
                            </div>

                            @if($booking->baggage->count() > 0)
                                <div class="flex justify-between">
                                    <span class="text-white/60">Baggage</span>
                                    <span class="font-bold">Rp {{ number_format($booking->baggage->sum('fee'), 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-white/60">Service Fee</span>
                                <span class="font-bold">Rp 10.000</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-white/60">Tax (10%)</span>
                                <span class="font-bold">Rp {{ number_format(($booking->passengers->sum('ticket_price') + $booking->baggage->sum('fee')) * 0.1, 0, ',', '.') }}</span>
                            </div>

                            <div class="border-t border-white/20 pt-3 mt-3">
                                <div class="flex justify-between items-end">
                                    <span class="text-white/60 text-xs">Total</span>
                                    <span class="text-xl font-extrabold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 bg-white/10 rounded-xl p-3 text-xs">
                            <p class="font-bold text-white/80 mb-0.5">✓ Secure Payment</p>
                            <p class="text-white/50 text-[10px]">256-bit SSL encryption protects your data</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection