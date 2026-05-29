@extends('admin.layouts.admin')
@section('title', 'Add Airport')
@section('subtitle', 'Create a new airport')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <form action="{{ route('admin.airports.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="tv-label">Airport Name</label>
                    <input type="text" name="name" class="tv-input" value="{{ old('name') }}" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">IATA Code (3 letters)</label>
                        <input type="text" name="iata_code" class="tv-input" value="{{ old('iata_code') }}" maxlength="3" required placeholder="CGK">
                    </div>
                    <div>
                        <label class="tv-label">ICAO Code (4 letters)</label>
                        <input type="text" name="icao_code" class="tv-input" value="{{ old('icao_code') }}" maxlength="4" required placeholder="WIII">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">City</label>
                        <input type="text" name="city" class="tv-input" value="{{ old('city') }}" required>
                    </div>
                    <div>
                        <label class="tv-label">Country</label>
                        <input type="text" name="country" class="tv-input" value="{{ old('country') }}" required>
                    </div>
                </div>
                <div>
                    <label class="tv-label">Timezone</label>
                    <input type="text" name="timezone" class="tv-input" value="{{ old('timezone') }}" placeholder="Asia/Jakarta">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-tv-primary rounded border-tv-border">
                    <label for="is_active" class="text-sm font-medium text-tv-text">Active</label>
                </div>
                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="btn-tv-primary">Create Airport</button>
                    <a href="{{ route('admin.airports.index') }}" class="btn-tv-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
