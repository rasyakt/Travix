@extends('admin.layouts.admin')
@section('title', 'Booking Management')
@section('subtitle', 'Manage all flight bookings')

@section('content')
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <form method="GET" class="flex items-center gap-2 flex-wrap">
                <input type="text" name="search" placeholder="Search bookings..." class="tv-input py-2 px-3 text-sm w-48" value="{{ request('search') }}">
                <select name="status" class="tv-input py-2 px-3 text-sm w-32">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <input type="date" name="date_from" class="tv-input py-2 px-3 text-sm w-36" value="{{ request('date_from') }}" placeholder="From">
                <input type="date" name="date_to" class="tv-input py-2 px-3 text-sm w-36" value="{{ request('date_to') }}" placeholder="To">
                <button type="submit" class="btn-tv-primary text-xs py-2 px-3">Filter</button>
                <a href="{{ route('admin.bookings.index') }}" class="text-xs text-tv-muted hover:text-tv-primary">Reset</a>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Booking Code</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Flight</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-tv-primary text-sm">{{ $booking->display_booking_code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-tv-text">{{ $booking->contact_name }}</p>
                                <p class="text-[10px] text-tv-muted">{{ $booking->contact_email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @php $flight = $booking->flights->first(); @endphp
                                @if($flight)
                                    <p class="text-sm font-bold text-tv-text">
                                        {{ $flight->schedule->originAirport->iata_code ?? '?' }} → {{ $flight->schedule->destinationAirport->iata_code ?? '?' }}
                                    </p>
                                    <p class="text-[10px] text-tv-muted">{{ $flight->flight_number }}</p>
                                @else
                                    <span class="text-xs text-red-400">No Flight</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-sm text-tv-text">
                                Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="tv-badge {{ match($booking->status) { 'confirmed' => 'bg-emerald-50 text-emerald-600', 'pending' => 'bg-amber-50 text-amber-600', 'cancelled' => 'bg-red-50 text-red-600', default => 'bg-gray-100 text-gray-600' } }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-tv-muted">{{ $booking->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="p-2 text-tv-primary hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">{{ $bookings->links() }}</div>
        @endif
    </div>
@endsection
