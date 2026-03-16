<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-tv-text tracking-tight">Online Check-In</h1>
                <p class="text-sm text-tv-muted">Booking <span
                        class="font-mono font-bold text-tv-primary">{{ $booking->display_booking_code }}</span></p>
            </div>
        </div>
    </div>

    {{-- Unavailable Alert --}}
    @if(!$canCheckIn)
        <div
            class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-700 px-5 py-4 rounded-xl text-sm font-medium mb-6">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <span><strong>Not available yet.</strong> Online check-in opens 24 hours before departure.</span>
        </div>
    @endif

    {{-- Flight Summary --}}
    @php $flight = $booking->flights->first(); @endphp
    <div class="tv-card p-5 mb-5">
        <div class="flex items-center gap-4 mb-4">
            @if($flight->schedule->airline->logo_url)
                <img src="{{ $flight->schedule->airline->logo_url }}" alt="{{ $flight->schedule->airline->name }}"
                    class="h-9 w-9 rounded-lg object-contain bg-gray-50 p-1">
            @endif
            <div>
                <p class="font-bold text-tv-text text-sm">{{ $flight->schedule->airline->name }}</p>
                <p class="text-xs text-tv-muted">{{ $flight->flight_number }}</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4 items-center">
            <div>
                <p class="text-xl font-extrabold text-tv-text">{{ $flight->schedule->originAirport->iata_code }}</p>
                <p class="text-[10px] text-[#a0aec0] font-medium">{{ $flight->departure_datetime->format('M d, H:i') }}
                </p>
            </div>
            <div class="flex items-center justify-center">
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
                <p class="text-xl font-extrabold text-tv-text">{{ $flight->schedule->destinationAirport->iata_code }}
                </p>
                <p class="text-[10px] text-[#a0aec0] font-medium">{{ $flight->arrival_datetime->format('M d, H:i') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Passenger Selection --}}
    <div class="tv-card p-5 mb-5">
        <h2 class="font-bold text-tv-text mb-4">Select Passengers</h2>
        <div class="space-y-2.5">
            @foreach($passengers as $index => $passenger)
                <label @class([
                    'flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all',
                    'border-tv-primary bg-blue-50/50' => in_array($passenger->id, $selectedPassengers),
                    'border-tv-border hover:border-[#d0d8e4]' => !in_array($passenger->id, $selectedPassengers),
                    'opacity-60 cursor-not-allowed' => $passenger->checkIn,
                ])>
                    <input type="checkbox" wire:model.live="selectedPassengers" value="{{ $passenger->id }}" @class([
                        'h-5 w-5 rounded-lg text-tv-primary border-tv-border focus:ring-tv-primary/20',
                    ])
                        @if($passenger->checkIn || !$canCheckIn) disabled @endif>
                    <div class="flex-1">
                        <p class="font-bold text-tv-text text-sm">{{ $passenger->first_name }} {{ $passenger->last_name }}
                        </p>
                        <p class="text-xs text-tv-muted mt-0.5">
                            @if($passenger->seatAssignment)
                                Seat {{ $passenger->seatAssignment->seatMap->seat_number }}
                            @else
                                <span class="text-amber-500">No seat assigned</span>
                            @endif
                        </p>
                    </div>
                    @if($passenger->checkIn)
                        <span class="tv-badge-green text-[10px]">
                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Checked In
                        </span>
                    @endif
                </label>
            @endforeach
        </div>

        {{-- CTA --}}
        <div class="mt-6">
            <button wire:click="processCheckIn" @if(!$canCheckIn || empty($selectedPassengers)) disabled @endif
                class="w-full btn-tv-accent py-3.5 text-sm disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none">
                @if(!$canCheckIn)
                    Check-in Not Available Yet
                @elseif(empty($selectedPassengers))
                    Select Passengers to Continue
                @else
                    Check In {{ count($selectedPassengers) }} {{ Str::plural('Passenger', count($selectedPassengers)) }}
                @endif
            </button>
        </div>
    </div>

    {{-- Info --}}
    <div class="tv-card bg-blue-50/60 border-blue-100 p-5">
        <h3 class="font-bold text-tv-primary text-sm mb-3">Check-in Information</h3>
        <ul class="space-y-2.5">
            @foreach([
                    'Online check-in opens 24 hours before departure',
                    'Ensure all passengers have seats assigned before check-in',
                    'After check-in, you can download your digital boarding pass',
                    'Arrive at the airport at least 2 hours before departure',
                ] as $info)
                    <li class="flex items-start gap-2.5 text-sm text-tv-muted">
                        <svg class="w-4 h-4 text-tv-primary mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ $info }}
                    </li>
            @endforeach
        </ul>
    </div>
</div>
