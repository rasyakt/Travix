<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- ═══ Welcome Header ═══ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-tv-text tracking-tight">My Bookings</h1>
            <p class="text-sm text-tv-muted mt-0.5">Manage your trips and travel history</p>
        </div>
        <a href="{{ route('flights.index') }}" class="btn-tv-primary gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Book a Flight
        </a>
    </div>

    {{-- ═══ Stats Cards ═══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="tv-card p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-tv-text">{{ $bookings->count() }}</p>
                <p class="text-xs font-medium text-tv-muted">Total Bookings</p>
            </div>
        </div>
        <div class="tv-card p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-emerald-600">{{ $upcomingFlights->count() }}</p>
                <p class="text-xs font-medium text-tv-muted">Upcoming</p>
            </div>
        </div>
        <div class="tv-card p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-gray-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-tv-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-tv-muted">{{ $pastFlights->count() }}</p>
                <p class="text-xs font-medium text-tv-muted">Completed</p>
            </div>
        </div>
    </div>

    {{-- ═══ Upcoming Flights ═══ --}}
    @if($upcomingFlights->count() > 0)
        <div>
            <h2 class="tv-section-title text-lg mb-4">Upcoming Flights</h2>
            <div class="space-y-3">
                @foreach($upcomingFlights as $booking)
                    @php $flight = $booking->flights->first(); @endphp
                    <div class="tv-card-hover p-5">
                        <div class="flex flex-col md:flex-row gap-5">
                            {{-- Booking Info --}}
                            <div class="flex items-center gap-4 md:w-48 shrink-0">
                                @if($flight->schedule->airline->logo_url)
                                    <img src="{{ $flight->schedule->airline->logo_url }}"
                                        alt="{{ $flight->schedule->airline->name }}"
                                        class="h-9 w-9 rounded-lg object-contain bg-gray-50 p-1">
                                @else
                                    <div class="h-9 w-9 rounded-lg bg-blue-50 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-tv-primary" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-mono font-bold text-tv-primary text-sm">{{ $booking->display_booking_code }}</p>
                                    <p class="text-xs text-tv-muted">{{ $flight->schedule->airline->name }}</p>
                                </div>
                            </div>

                            {{-- Route & Date --}}
                            <div class="flex-1 flex items-center gap-6">
                                <div>
                                    <p class="text-lg font-extrabold text-tv-text">
                                        {{ $flight->schedule->originAirport->iata_code }}</p>
                                    <p class="text-[10px] text-[#a0aec0] font-medium">
                                        {{ $flight->schedule->originAirport->city }}</p>
                                </div>
                                <div class="flex-1 flex items-center justify-center">
                                    <div class="w-2 h-2 rounded-full border-2 border-tv-primary"></div>
                                    <div class="h-px bg-tv-border flex-1"></div>
                                    <svg class="w-4 h-4 text-tv-primary mx-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                    </svg>
                                    <div class="h-px bg-tv-border flex-1"></div>
                                    <div class="w-2 h-2 rounded-full border-2 border-tv-accent"></div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-extrabold text-tv-text">
                                        {{ $flight->schedule->destinationAirport->iata_code }}</p>
                                    <p class="text-[10px] text-[#a0aec0] font-medium">
                                        {{ $flight->schedule->destinationAirport->city }}</p>
                                </div>
                            </div>

                            {{-- Date & Status --}}
                            <div class="flex items-center gap-4 md:w-56 shrink-0 md:justify-end">
                                <div class="md:text-right">
                                    <p class="text-sm font-bold text-tv-text">
                                        {{ $flight->departure_datetime->format('M d, Y') }}</p>
                                    <p class="text-xs text-tv-muted">{{ $flight->departure_datetime->format('H:i') }}</p>
                                </div>
                                <span
                                    class="tv-badge {{ $booking->status === 'confirmed' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-tv-border">
                            <a href="{{ route('booking.show', $booking->id) }}" class="btn-tv-outline text-xs py-2 px-4">View
                                Details</a>
                            @if($booking->canCheckIn() && $booking->passengers->contains(fn($passenger) => !$passenger->checkIn && $passenger->seatAssignment))
                                <a href="{{ route('booking.checkin', $booking->id) }}"
                                    class="btn-tv-accent text-xs py-2 px-4">Check-in Now</a>
                            @endif
                            @if($booking->status === 'pending' && $flight->departure_datetime->diffInHours(now()) >= 24)
                                <button wire:click="cancelBooking({{ $booking->id }})"
                                    class="btn-tv-ghost text-xs py-2 px-4 text-red-500 hover:text-red-600 hover:bg-red-50">Cancel</button>
                            @endif
                            @if($booking->status === 'confirmed' && $booking->payment && $booking->payment->status === 'success' && $booking->is_refundable)
                                <form method="POST" action="{{ route('booking.refund', $booking->id) }}" class="inline"
                                    onsubmit="return confirm('Lanjutkan refund tiket ini? Booking akan dibatalkan.')">
                                    @csrf
                                    <button type="submit"
                                        class="btn-tv-ghost text-xs py-2 px-4 text-red-500 hover:text-red-600 hover:bg-red-50">Refund</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="tv-card p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-tv-text mb-2">No upcoming flights</h3>
            <p class="text-sm text-tv-muted mb-6 max-w-sm mx-auto">Start planning your next trip! Search for flights and
                book your next adventure.</p>
            <a href="{{ route('flights.index') }}" class="btn-tv-primary gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Search Flights
            </a>
        </div>
    @endif

    {{-- ═══ Past Flights ═══ --}}
    @if($pastFlights->count() > 0)
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="tv-section-title text-lg">Travel History</h2>
                <span class="tv-badge-gray">{{ $pastFlights->count() }} trips</span>
            </div>
            <div class="space-y-2">
                @foreach($pastFlights->take(5) as $booking)
                    @php $flight = $booking->flights->first(); @endphp
                    <div class="tv-card p-4 flex items-center gap-4">
                        @if($flight->schedule->airline->logo_url)
                            <img src="{{ $flight->schedule->airline->logo_url }}" alt="{{ $flight->schedule->airline->name }}"
                                class="h-8 w-8 rounded-lg object-contain bg-gray-50 p-1 shrink-0">
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-tv-text text-sm truncate">
                                {{ $flight->schedule->originAirport->city }} → {{ $flight->schedule->destinationAirport->city }}
                            </p>
                            <p class="text-xs text-[#a0aec0]">{{ $flight->departure_datetime->format('M d, Y') }}</p>
                        </div>
                        <p class="font-mono text-xs text-tv-muted hidden sm:block">{{ $booking->display_booking_code }}</p>
                        <a href="{{ route('booking.show', $booking->id) }}"
                            class="text-sm font-semibold text-tv-primary hover:text-[#0560c7] transition-colors shrink-0">View</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>