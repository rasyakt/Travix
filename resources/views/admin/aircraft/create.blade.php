@extends('admin.layouts.admin')
@section('title', 'Add Aircraft')
@section('subtitle', 'Add a new aircraft type')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <form action="{{ route('admin.aircraft.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="tv-label">Manufacturer</label>
                    <select name="manufacturer_id" class="tv-input" required>
                        <option value="">Select Manufacturer</option>
                        @foreach($manufacturers as $m)
                            <option value="{{ $m->id }}" {{ old('manufacturer_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="tv-label">Model</label>
                    <input type="text" name="model" class="tv-input" value="{{ old('model') }}" required placeholder="Boeing 737-800NG">
                </div>
                <div>
                    <label class="tv-label">IATA Code</label>
                    <input type="text" name="iata_code" class="tv-input" value="{{ old('iata_code') }}" required placeholder="738">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Seating Capacity</label>
                        <input type="number" name="typical_seating_capacity" class="tv-input" value="{{ old('typical_seating_capacity') }}" required min="1">
                    </div>
                    <div>
                        <label class="tv-label">Max Range (km)</label>
                        <input type="number" name="max_range_km" class="tv-input" value="{{ old('max_range_km') }}" min="0">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Cruise Speed (km/h)</label>
                        <input type="number" step="0.01" name="cruise_speed_kmh" class="tv-input" value="{{ old('cruise_speed_kmh') }}" min="0">
                    </div>
                    <div>
                        <label class="tv-label">Legroom</label>
                        <input type="text" name="legroom" class="tv-input" value="{{ old('legroom') }}" placeholder="Standard">
                    </div>
                </div>
                <div>
                    <label class="tv-label">Amenities (JSON)</label>
                    <textarea name="amenities" class="tv-input" rows="3" placeholder='{"wifi": true, "entertainment": true}'>{{ old('amenities') }}</textarea>
                </div>
                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="btn-tv-primary">Create Aircraft</button>
                    <a href="{{ route('admin.aircraft.index') }}" class="btn-tv-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
