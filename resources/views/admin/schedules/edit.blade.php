@extends('admin.layouts.admin')
@section('title', 'Edit Schedule')
@section('subtitle', $schedule->flight_number)

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Airline</label>
                        <select name="airline_id" class="tv-input" required>
                            @foreach($airlines as $airline)
                                <option value="{{ $airline->id }}" {{ old('airline_id', $schedule->airline_id) == $airline->id ? 'selected' : '' }}>{{ $airline->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="tv-label">Aircraft</label>
                        <select name="aircraft_id" class="tv-input" required>
                            @foreach($aircraft as $a)
                                <option value="{{ $a->id }}" {{ old('aircraft_id', $schedule->aircraft_id) == $a->id ? 'selected' : '' }}>{{ $a->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Origin</label>
                        <select name="origin_airport_id" class="tv-input" required>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}" {{ old('origin_airport_id', $schedule->origin_airport_id) == $airport->id ? 'selected' : '' }}>{{ $airport->city }} ({{ $airport->iata_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="tv-label">Destination</label>
                        <select name="destination_airport_id" class="tv-input" required>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}" {{ old('destination_airport_id', $schedule->destination_airport_id) == $airport->id ? 'selected' : '' }}>{{ $airport->city }} ({{ $airport->iata_code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="tv-label">Flight Number</label>
                    <input type="text" name="flight_number" class="tv-input" value="{{ old('flight_number', $schedule->flight_number) }}" required>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div><label class="tv-label">Departure</label><input type="time" name="departure_time" class="tv-input" value="{{ old('departure_time', $schedule->departure_time->format('H:i')) }}" required></div>
                    <div><label class="tv-label">Arrival</label><input type="time" name="arrival_time" class="tv-input" value="{{ old('arrival_time', $schedule->arrival_time->format('H:i')) }}" required></div>
                    <div><label class="tv-label">Duration</label><input type="number" name="duration_minutes" class="tv-input" value="{{ old('duration_minutes', $schedule->duration_minutes) }}" required min="1"></div>
                </div>
                <div>
                    <label class="tv-label">Operating Days</label>
                    <div class="grid grid-cols-7 gap-2">
                        @foreach(['0'=>'Mon','1'=>'Tue','2'=>'Wed','3'=>'Thu','4'=>'Fri','5'=>'Sat','6'=>'Sun'] as $val => $label)
                            <label class="flex flex-col items-center gap-1 p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 has-checked:bg-blue-50 has-checked:border-tv-primary">
                                <input type="checkbox" name="operating_days[]" value="{{ $val }}" {{ in_array($val, old('operating_days', $schedule->operating_days ?? [])) ? 'checked' : '' }} class="w-4 h-4 text-tv-primary rounded border-gray-300">
                                <span class="text-[10px] font-bold text-tv-muted">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="tv-label">Valid From</label><input type="date" name="valid_from" class="tv-input" value="{{ old('valid_from', $schedule->valid_from->format('Y-m-d')) }}" required></div>
                    <div><label class="tv-label">Valid Until</label><input type="date" name="valid_until" class="tv-input" value="{{ old('valid_until', $schedule->valid_until->format('Y-m-d')) }}" required></div>
                </div>
                <div>
                    <label class="tv-label">Base Price (Rp)</label>
                    <input type="number" name="base_price" class="tv-input" value="{{ old('base_price', $schedule->base_price) }}" required min="0">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $schedule->is_active) ? 'checked' : '' }} class="w-4 h-4 text-tv-primary rounded border-tv-border">
                    <label for="is_active" class="text-sm font-medium text-tv-text">Active</label>
                </div>
                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="btn-tv-primary">Update Schedule</button>
                    <a href="{{ route('admin.schedules.index') }}" class="btn-tv-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
