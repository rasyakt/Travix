@extends('admin.layouts.admin')
@section('title', 'Create Flight')
@section('subtitle', 'Add a new flight')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <form action="{{ route('admin.flights.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="tv-label">Schedule</label>
                    <select name="schedule_id" class="tv-input" required>
                        <option value="">Select Schedule</option>
                        @foreach($schedules as $schedule)
                            <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                {{ $schedule->airline->name }} - {{ $schedule->flight_number }} ({{ $schedule->originAirport->iata_code }} → {{ $schedule->destinationAirport->iata_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Flight Date</label>
                        <input type="date" name="flight_date" class="tv-input" value="{{ old('flight_date') }}" required>
                    </div>
                    <div>
                        <label class="tv-label">Aircraft Instance</label>
                        <select name="aircraft_instance_id" class="tv-input">
                            <option value="">Select Aircraft</option>
                            @foreach($aircraftInstances as $instance)
                                <option value="{{ $instance->id }}" {{ old('aircraft_instance_id') == $instance->id ? 'selected' : '' }}>
                                    {{ $instance->airline->name }} - {{ $instance->registration_number }} ({{ $instance->aircraft->model ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Departure DateTime</label>
                        <input type="datetime-local" name="departure_datetime" class="tv-input" value="{{ old('departure_datetime') }}" required>
                    </div>
                    <div>
                        <label class="tv-label">Arrival DateTime</label>
                        <input type="datetime-local" name="arrival_datetime" class="tv-input" value="{{ old('arrival_datetime') }}" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Available Seats</label>
                        <input type="number" name="available_seats" class="tv-input" value="{{ old('available_seats') }}" required min="0">
                    </div>
                    <div>
                        <label class="tv-label">Current Price (Rp)</label>
                        <input type="number" name="current_price" class="tv-input" value="{{ old('current_price') }}" required min="0">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Status</label>
                        <select name="status" class="tv-input" required>
                            <option value="scheduled" {{ old('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="delayed" {{ old('status') === 'delayed' ? 'selected' : '' }}>Delayed</option>
                            <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="tv-label">Gate</label>
                            <input type="text" name="gate" class="tv-input" value="{{ old('gate') }}">
                        </div>
                        <div>
                            <label class="tv-label">Terminal</label>
                            <input type="text" name="terminal" class="tv-input" value="{{ old('terminal') }}">
                        </div>
                    </div>
                </div>
                @error('arrival_datetime') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="btn-tv-primary">Create Flight</button>
                    <a href="{{ route('admin.flights.index') }}" class="btn-tv-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
