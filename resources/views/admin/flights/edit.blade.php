@extends('admin.layouts.admin')
@section('title', 'Edit Flight')
@section('subtitle', $flight->flight_number)

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <form action="{{ route('admin.flights.update', $flight->id) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="tv-label">Schedule</label>
                    <select name="schedule_id" class="tv-input" required>
                        @foreach($schedules as $schedule)
                            <option value="{{ $schedule->id }}" {{ old('schedule_id', $flight->schedule_id) == $schedule->id ? 'selected' : '' }}>
                                {{ $schedule->airline->name }} - {{ $schedule->flight_number }} ({{ $schedule->originAirport->iata_code }} → {{ $schedule->destinationAirport->iata_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Flight Date</label>
                        <input type="date" name="flight_date" class="tv-input" value="{{ old('flight_date', $flight->flight_date->format('Y-m-d')) }}" required>
                    </div>
                    <div>
                        <label class="tv-label">Aircraft Instance</label>
                        <select name="aircraft_instance_id" class="tv-input">
                            <option value="">None</option>
                            @foreach($aircraftInstances as $instance)
                                <option value="{{ $instance->id }}" {{ old('aircraft_instance_id', $flight->aircraft_instance_id) == $instance->id ? 'selected' : '' }}>
                                    {{ $instance->registration_number }} ({{ $instance->aircraft->model ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Departure</label>
                        <input type="datetime-local" name="departure_datetime" class="tv-input" value="{{ old('departure_datetime', $flight->departure_datetime->format('Y-m-d\TH:i')) }}" required>
                    </div>
                    <div>
                        <label class="tv-label">Arrival</label>
                        <input type="datetime-local" name="arrival_datetime" class="tv-input" value="{{ old('arrival_datetime', $flight->arrival_datetime->format('Y-m-d\TH:i')) }}" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Available Seats</label>
                        <input type="number" name="available_seats" class="tv-input" value="{{ old('available_seats', $flight->available_seats) }}" required min="0">
                    </div>
                    <div>
                        <label class="tv-label">Price (Rp)</label>
                        <input type="number" name="current_price" class="tv-input" value="{{ old('current_price', $flight->current_price) }}" required min="0">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Status</label>
                        <select name="status" class="tv-input" required>
                            @foreach(['scheduled','active','boarding','departed','in_air','landed','arrived','delayed','cancelled'] as $s)
                                <option value="{{ $s }}" {{ old('status', $flight->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="tv-label">Gate</label><input type="text" name="gate" class="tv-input" value="{{ old('gate', $flight->gate) }}"></div>
                        <div><label class="tv-label">Terminal</label><input type="text" name="terminal" class="tv-input" value="{{ old('terminal', $flight->terminal) }}"></div>
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="btn-tv-primary">Update Flight</button>
                    <a href="{{ route('admin.flights.index') }}" class="btn-tv-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
