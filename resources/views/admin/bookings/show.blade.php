@extends('admin.layouts.admin')
@section('title', 'Booking Details')
@section('subtitle', 'Referral: ' . ($booking->display_booking_code ?? 'N/A'))

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Reservation Info --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-tv-text mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Reservation Information
                </h3>
                <div class="grid grid-cols-2 gap-y-4">
                    <div>
                        <p class="tv-label text-[10px] mb-0.5">Customer</p>
                        <p class="text-sm font-bold text-tv-text">{{ $booking->contact_name }}</p>
                        <p class="text-[10px] text-tv-muted">{{ $booking->contact_email }}</p>
                    </div>
                    <div>
                        <p class="tv-label text-[10px] mb-0.5">Booking Date</p>
                        <p class="text-sm font-bold text-tv-text">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="tv-label text-[10px] mb-0.5">Contact</p>
                        <p class="text-sm font-bold text-tv-text">{{ $booking->contact_phone }}</p>
                    </div>
                    <div>
                        <p class="tv-label text-[10px] mb-0.5">Status</p>
                        <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <select name="status" class="tv-input py-1.5 text-xs w-32" onchange="this.form.submit()">
                                @foreach(['pending','confirmed','cancelled','completed'] as $s)
                                    <option value="{{ $s }}" {{ $booking->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Flights --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-tv-text mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-tv-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                    Flight Content
                </h3>
                @foreach($booking->flights as $flight)
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-3 last:mb-0">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-lg p-1 border border-gray-200 flex items-center justify-center">
                                    @if($flight->schedule->airline->logo_url ?? false)
                                        <img src="{{ $flight->schedule->airline->logo_url }}" class="w-full h-full object-contain">
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-tv-text">{{ $flight->flight_number }}</p>
                                    <p class="text-[10px] text-tv-muted">{{ $flight->schedule->airline->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-tv-primary">{{ $flight->departure_datetime->format('M d, H:i') }}</p>
                                <p class="text-[10px] text-tv-muted">Departure</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-2 px-1">
                            <div class="text-center">
                                <p class="text-lg font-extrabold text-tv-text">{{ $flight->schedule->originAirport->iata_code ?? '?' }}</p>
                                <p class="text-[9px] text-tv-muted">{{ $flight->schedule->originAirport->city ?? '' }}</p>
                            </div>
                            <div class="flex-1 px-4">
                                <div class="h-px bg-gray-300 relative">
                                    <div class="absolute inset-0 flex items-center justify-center -top-2">
                                        <svg class="w-4 h-4 text-tv-primary bg-gray-50" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-extrabold text-tv-text">{{ $flight->schedule->destinationAirport->iata_code ?? '?' }}</p>
                                <p class="text-[9px] text-tv-muted">{{ $flight->schedule->destinationAirport->city ?? '' }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-2">
                            <span class="tv-badge {{ match($flight->status) { 'scheduled' => 'bg-blue-50 text-blue-600', 'active' => 'bg-emerald-50 text-emerald-600', 'delayed' => 'bg-amber-50 text-amber-600', 'cancelled' => 'bg-red-50 text-red-600', default => 'bg-gray-100 text-gray-600' } }}">
                                {{ ucfirst($flight->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Passengers --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-tv-text mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Passengers ({{ $booking->passengers->count() }})
                </h3>
                <div class="space-y-3">
                    @foreach($booking->passengers as $passenger)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center border border-gray-200 text-tv-primary font-bold">
                                    {{ substr($passenger->first_name, 0, 1) }}{{ substr($passenger->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-tv-text">{{ $passenger->first_name }} {{ $passenger->last_name }}</p>
                                    <p class="text-[10px] text-tv-muted">{{ $passenger->nationality }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($passenger->seatAssignment)
                                    <span class="tv-badge-blue text-[10px]">Seat: {{ $passenger->seatAssignment->seatMap->seat_number ?? $passenger->seatAssignment->seat_number }}</span>
                                @endif
                                @if($passenger->boardingPass)
                                    <div class="mt-1"><span class="text-[9px] text-emerald-500 font-bold uppercase">Checked In</span></div>
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
            <div class="bg-gradient-to-br from-tv-primary to-tv-secondary text-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-sm mb-4">Financial Summary</h3>
                <div class="space-y-3 pb-4 mb-4 border-b border-white/10">
                    <div class="flex justify-between text-sm">
                        <span class="text-white/60">Base Fare</span>
                        <span class="font-bold">Rp {{ number_format($booking->base_fare, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-white/60">Taxes & Fees</span>
                        <span class="font-bold">Rp {{ number_format($booking->taxes_fees, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-white/60">Extras</span>
                        <span class="font-bold">Rp {{ number_format($booking->baggage_fee + $booking->seat_fee, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-end">
                    <span class="text-white/60 text-xs uppercase tracking-widest font-bold">Total</span>
                    <span class="text-2xl font-extrabold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                </div>
                @if($booking->payment)
                    <div class="mt-5 p-3 rounded-xl bg-white/10 border border-white/20">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[10px] text-white/50">Payment</span>
                            <span class="text-[10px] font-bold uppercase">{{ $booking->payment->status }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] text-white/50">Method</span>
                            <span class="text-[10px] font-bold capitalize">{{ str_replace('_', ' ', $booking->payment->payment_method ?? '-') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-tv-text text-sm mb-4">Actions</h3>
                <div class="space-y-2">
                    <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="confirmed">
                        @if($booking->status === 'pending')
                            <button type="submit" class="w-full btn-tv-primary py-2.5 text-xs" onclick="return confirm('Confirm this booking?')">Confirm Booking</button>
                        @endif
                    </form>
                    <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="cancelled">
                        @if(in_array($booking->status, ['pending', 'confirmed']))
                            <button type="submit" class="w-full border border-red-200 text-red-600 font-bold py-2.5 rounded-xl text-xs hover:bg-red-50 transition-colors" onclick="return confirm('Cancel this booking?')">Cancel Booking</button>
                        @endif
                    </form>
                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Delete this booking permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full border border-red-200 text-red-600 font-bold py-2.5 rounded-xl text-xs hover:bg-red-50 transition-colors">Delete Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
