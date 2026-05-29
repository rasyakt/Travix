@extends('admin.layouts.admin')
@section('title', 'Edit Aircraft')
@section('subtitle', $aircraft->model)

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <form action="{{ route('admin.aircraft.update', $aircraft->id) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="tv-label">Manufacturer</label>
                    <select name="manufacturer_id" class="tv-input" required>
                        @foreach($manufacturers as $m)
                            <option value="{{ $m->id }}" {{ old('manufacturer_id', $aircraft->manufacturer_id) == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="tv-label">Model</label><input type="text" name="model" class="tv-input" value="{{ old('model', $aircraft->model) }}" required></div>
                <div><label class="tv-label">IATA Code</label><input type="text" name="iata_code" class="tv-input" value="{{ old('iata_code', $aircraft->iata_code) }}" required></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="tv-label">Capacity</label><input type="number" name="typical_seating_capacity" class="tv-input" value="{{ old('typical_seating_capacity', $aircraft->typical_seating_capacity) }}" required min="1"></div>
                    <div><label class="tv-label">Range (km)</label><input type="number" name="max_range_km" class="tv-input" value="{{ old('max_range_km', $aircraft->max_range_km) }}" min="0"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="tv-label">Speed (km/h)</label><input type="number" step="0.01" name="cruise_speed_kmh" class="tv-input" value="{{ old('cruise_speed_kmh', $aircraft->cruise_speed_kmh) }}" min="0"></div>
                    <div><label class="tv-label">Legroom</label><input type="text" name="legroom" class="tv-input" value="{{ old('legroom', $aircraft->legroom) }}"></div>
                </div>
                <div><label class="tv-label">Amenities (JSON)</label><textarea name="amenities" class="tv-input" rows="3">{{ old('amenities', is_string($aircraft->amenities) ? $aircraft->amenities : json_encode($aircraft->amenities ?? [])) }}</textarea></div>
                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="btn-tv-primary">Update Aircraft</button>
                    <a href="{{ route('admin.aircraft.index') }}" class="btn-tv-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
