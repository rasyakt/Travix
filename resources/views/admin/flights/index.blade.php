@extends('admin.layouts.admin')
@section('title', 'Flight Management')
@section('subtitle', 'Manage all flight schedules')

@section('content')
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <form method="GET" class="flex items-center gap-2">
                    <input type="text" name="search" placeholder="Search flights..." class="tv-input py-2 px-3 text-sm w-48" value="{{ request('search') }}">
                    <select name="status" class="tv-input py-2 px-3 text-sm w-32">
                        <option value="">All Status</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="delayed" {{ request('status') === 'delayed' ? 'selected' : '' }}>Delayed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="btn-tv-primary text-xs py-2 px-3">Filter</button>
                    <a href="{{ route('admin.flights.index') }}" class="text-xs text-tv-muted hover:text-tv-primary">Reset</a>
                </form>
            </div>
            <a href="{{ route('admin.flights.create') }}" class="btn-tv-primary text-xs py-2 px-4">+ Add Flight</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Flight</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Route</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Seats</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($flights as $flight)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-tv-text">{{ $flight->flight_number }}</p>
                                <p class="text-[10px] text-tv-muted">{{ $flight->schedule->airline->name ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-tv-text">
                                    {{ $flight->schedule->originAirport->iata_code ?? '???' }} → {{ $flight->schedule->destinationAirport->iata_code ?? '???' }}
                                </p>
                                <p class="text-[10px] text-tv-muted">{{ $flight->departure_datetime->format('H:i') }} - {{ $flight->arrival_datetime->format('H:i') }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-tv-text">{{ $flight->flight_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="tv-badge {{ match($flight->status) { 'scheduled' => 'bg-blue-50 text-blue-600', 'active' => 'bg-emerald-50 text-emerald-600', 'delayed' => 'bg-amber-50 text-amber-600', 'cancelled' => 'bg-red-50 text-red-600', default => 'bg-gray-100 text-gray-600' } }}">
                                    {{ ucfirst($flight->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-tv-text">{{ $flight->available_seats }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-tv-text">Rp {{ number_format($flight->current_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.flights.show', $flight->id) }}" class="p-2 text-tv-primary hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.flights.edit', $flight->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($flights->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">{{ $flights->links() }}</div>
        @endif
    </div>
@endsection
