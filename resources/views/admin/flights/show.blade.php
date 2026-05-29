@extends('admin.layouts.admin')
@section('title', 'Flight Details')
@section('subtitle', $flight->flight_number)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Flight Info --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-tv-text mb-4">Flight Information</h3>
                <div class="grid grid-cols-2 gap-y-4">
                    <div><span class="tv-label text-[10px]">Flight Number</span><p class="text-sm font-bold">{{ $flight->flight_number }}</p></div>
                    <div><span class="tv-label text-[10px]">Status</span>
                        <span class="tv-badge {{ match($flight->status) { 'scheduled' => 'bg-blue-50 text-blue-600', 'active' => 'bg-emerald-50 text-emerald-600', 'delayed' => 'bg-amber-50 text-amber-600', 'cancelled' => 'bg-red-50 text-red-600', default => 'bg-gray-100 text-gray-600' } }}">
                            {{ ucfirst($flight->status) }}
                        </span>
                    </div>
                    <div><span class="tv-label text-[10px]">Route</span><p class="text-sm font-bold">{{ $flight->schedule->originAirport->iata_code ?? '?' }} → {{ $flight->schedule->destinationAirport->iata_code ?? '?' }}</p></div>
                    <div><span class="tv-label text-[10px]">Airline</span><p class="text-sm font-bold">{{ $flight->schedule->airline->name ?? '?' }}</p></div>
                    <div><span class="tv-label text-[10px]">Departure</span><p class="text-sm font-bold">{{ $flight->departure_datetime->format('M d, Y H:i') }}</p></div>
                    <div><span class="tv-label text-[10px]">Arrival</span><p class="text-sm font-bold">{{ $flight->arrival_datetime->format('M d, Y H:i') }}</p></div>
                    <div><span class="tv-label text-[10px]">Gate</span><p class="text-sm font-bold">{{ $flight->gate ?? '-' }}</p></div>
                    <div><span class="tv-label text-[10px]">Terminal</span><p class="text-sm font-bold">{{ $flight->terminal ?? '-' }}</p></div>
                    <div><span class="tv-label text-[10px]">Available Seats</span><p class="text-sm font-bold">{{ $flight->available_seats }}</p></div>
                    <div><span class="tv-label text-[10px]">Price</span><p class="text-sm font-bold">Rp {{ number_format($flight->current_price, 0, ',', '.') }}</p></div>
                </div>
                @if($flight->aircraftInstance)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="tv-label text-[10px]">Aircraft</span>
                        <p class="text-sm font-bold">{{ $flight->aircraftInstance->registration_number }} - {{ $flight->aircraftInstance->aircraft->model ?? '' }} ({{ $flight->aircraftInstance->aircraft->manufacturer->name ?? '' }})</p>
                    </div>
                @endif
            </div>

            {{-- Seat Prices --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-tv-text mb-4">Seat Prices</h3>
                <form action="{{ route('admin.flights.update-prices', $flight->id) }}" method="POST">
                    @csrf
                    <div class="space-y-3">
                        @forelse($flight->seatPrices as $seatPrice)
                            <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl">
                                <input type="hidden" name="prices[{{ $loop->index }}][travel_class_id]" value="{{ $seatPrice->travel_class_id }}">
                                <span class="text-sm font-bold text-tv-text w-24">{{ $seatPrice->travelClass->name ?? 'Class' }}</span>
                                <div class="flex-1">
                                    <label class="text-[10px] text-tv-muted">Price</label>
                                    <input type="number" name="prices[{{ $loop->index }}][price]" class="tv-input py-1.5 text-sm" value="{{ $seatPrice->price }}" required>
                                </div>
                                <div class="w-24">
                                    <label class="text-[10px] text-tv-muted">Seats</label>
                                    <input type="number" name="prices[{{ $loop->index }}][available_seats]" class="tv-input py-1.5 text-sm" value="{{ $seatPrice->available_seats }}" required>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-tv-muted">No seat prices configured yet.</p>
                        @endforelse
                    </div>
                    @if($flight->seatPrices->count() > 0)
                        <button type="submit" class="btn-tv-primary text-xs py-2 px-4 mt-4">Update Prices</button>
                    @endif
                </form>
            </div>

            {{-- Bookings --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-tv-text mb-4">Related Bookings</h3>
                @forelse($flight->bookingFlights as $bf)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <span class="text-sm font-medium">{{ $bf->booking->contact_name }}</span>
                        <span class="text-xs text-tv-muted font-mono">{{ $bf->booking->display_booking_code }}</span>
                    </div>
                @empty
                    <p class="text-sm text-tv-muted">No bookings for this flight.</p>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-tv-text mb-4">Update Status</h3>
                <form action="{{ route('admin.flights.update-status', $flight->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <select name="status" class="tv-input text-sm" required>
                        @foreach(['scheduled','active','boarding','departed','in_air','landed','arrived','delayed','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $flight->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-tv-primary w-full text-xs py-2">Update Status</button>
                </form>
            </div>

            {{-- Status Log --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-tv-text mb-4">Status History</h3>
                <div class="space-y-2">
                    @forelse($flight->statusLogs as $log)
                        <div class="text-xs border-l-2 border-gray-200 pl-3 py-1">
                            <p class="font-medium text-tv-text">{{ $log->old_status }} → {{ $log->new_status }}</p>
                            <p class="text-tv-muted">{{ $log->changed_at ? $log->changed_at->format('M d, H:i') : 'N/A' }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-tv-muted">No status changes logged.</p>
                    @endforelse
                </div>
            </div>

            {{-- Delete --}}
            <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-6">
                <h3 class="font-bold text-red-600 mb-2">Danger Zone</h3>
                <p class="text-xs text-tv-muted mb-3">Delete this flight permanently.</p>
                <form action="{{ route('admin.flights.destroy', $flight->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full border border-red-200 text-red-600 font-bold py-2 rounded-xl text-xs hover:bg-red-50 transition-colors">Delete Flight</button>
                </form>
            </div>
        </div>
    </div>
@endsection
