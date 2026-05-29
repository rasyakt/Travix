@extends('admin.layouts.admin')
@section('title', 'Edit Airline')
@section('subtitle', $airline->name)

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <form action="{{ route('admin.airlines.update', $airline->id) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="tv-label">Airline Name</label>
                    <input type="text" name="name" class="tv-input" value="{{ old('name', $airline->name) }}" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">IATA Code</label>
                        <input type="text" name="iata_code" class="tv-input" value="{{ old('iata_code', $airline->iata_code) }}" maxlength="2" required>
                    </div>
                    <div>
                        <label class="tv-label">ICAO Code</label>
                        <input type="text" name="icao_code" class="tv-input" value="{{ old('icao_code', $airline->icao_code) }}" maxlength="3" required>
                    </div>
                </div>
                <div>
                    <label class="tv-label">Country</label>
                    <input type="text" name="country" class="tv-input" value="{{ old('country', $airline->country) }}" required>
                </div>
                <div>
                    <label class="tv-label">Logo URL</label>
                    <input type="url" name="logo_url" class="tv-input" value="{{ old('logo_url', $airline->logo_url) }}">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $airline->is_active) ? 'checked' : '' }} class="w-4 h-4 text-tv-primary rounded border-tv-border">
                    <label for="is_active" class="text-sm font-medium text-tv-text">Active</label>
                </div>
                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="btn-tv-primary">Update Airline</button>
                    <a href="{{ route('admin.airlines.index') }}" class="btn-tv-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
