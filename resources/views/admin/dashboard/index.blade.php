@extends('admin.layouts.admin')
@section('title', 'Dashboard')
@section('subtitle', 'Overview of your travel platform')

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-xs font-bold text-tv-muted uppercase tracking-wider">Total</span>
            </div>
            <p class="text-3xl font-extrabold text-tv-text">{{ number_format($totalBookings) }}</p>
            <p class="text-sm font-medium text-tv-muted mt-1">Total Bookings</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-tv-muted uppercase tracking-wider">Revenue</span>
            </div>
            <p class="text-3xl font-extrabold text-tv-text">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-sm font-medium text-tv-muted mt-1">Total Revenue</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-tv-muted uppercase tracking-wider">Users</span>
            </div>
            <p class="text-3xl font-extrabold text-tv-text">{{ number_format($totalUsers) }}</p>
            <p class="text-sm font-medium text-tv-muted mt-1">Registered Users</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                </div>
                <span class="text-xs font-bold text-tv-muted uppercase tracking-wider">Flights</span>
            </div>
            <p class="text-3xl font-extrabold text-tv-text">{{ number_format($totalFlights) }}</p>
            <p class="text-sm font-medium text-tv-muted mt-1">Total Flights</p>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 10.5l1.5-3M7.5 4.5l1.5 3m0 0l-1.5 3m1.5-3h-6m6 0l1.5-3m-1.5 3l1.5 3M12 4.5l3 7.5m-3-7.5L9 12m3-7.5L15 12m-3 7.5l-3-7.5m3 7.5l3-7.5M21 10.5l-1.5-3M16.5 4.5l-1.5 3m1.5-3l1.5 3m-1.5 3l-1.5-3m1.5 3h-6"/></svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-tv-text">{{ number_format($totalAirlines) }}</p>
                <p class="text-xs font-medium text-tv-muted">Airlines</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-tv-text">{{ number_format($totalAirports) }}</p>
                <p class="text-xs font-medium text-tv-muted">Airports</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-tv-text">{{ number_format($totalBookings) }}</p>
                <p class="text-xs font-medium text-tv-muted">Total Bookings</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Bookings --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-tv-text">Recent Bookings</h3>
                <a href="{{ route('admin.bookings.index') }}" class="text-xs font-bold text-tv-primary hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentBookings as $booking)
                    <div class="px-6 py-3.5 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-tv-text">{{ $booking->contact_name }}</p>
                            <p class="text-[10px] text-tv-muted font-mono">{{ $booking->display_booking_code ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="tv-badge {{ match($booking->status) { 'confirmed' => 'bg-emerald-50 text-emerald-600', 'pending' => 'bg-amber-50 text-amber-600', 'cancelled' => 'bg-red-50 text-red-600', default => 'bg-gray-100 text-gray-600' } }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                            <p class="text-[10px] text-tv-muted mt-1">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-tv-muted">No bookings yet</div>
                @endforelse
            </div>
        </div>

        {{-- Booking by Status --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-tv-text">Bookings by Status</h3>
            </div>
            <div class="px-6 py-5 space-y-4">
                @php
                    $statusColors = ['confirmed' => 'bg-emerald-500', 'pending' => 'bg-amber-500', 'cancelled' => 'bg-red-500', 'completed' => 'bg-blue-500'];
                    $total = array_sum($bookingsByStatus->toArray()) ?: 1;
                @endphp
                @foreach(['confirmed', 'pending', 'cancelled', 'completed'] as $status)
                    @php $count = $bookingsByStatus[$status] ?? 0; @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-tv-text capitalize">{{ $status }}</span>
                            <span class="font-bold text-tv-text">{{ number_format($count) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-500 {{ $statusColors[$status] ?? 'bg-gray-400' }}" style="width: {{ ($count / $total) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-tv-text">Latest Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-tv-primary hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($latestUsers as $user)
                    <div class="px-6 py-3.5 flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" alt="" class="w-8 h-8 rounded-lg object-cover">
                        <div class="flex-1">
                            <p class="text-sm font-bold text-tv-text">{{ $user->name }}</p>
                            <p class="text-[10px] text-tv-muted">{{ $user->email }}</p>
                        </div>
                        <span class="text-[10px] text-tv-muted">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-tv-muted">No users yet</div>
                @endforelse
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-tv-text">Quick Actions</h3>
            </div>
            <div class="px-6 py-5 grid grid-cols-2 gap-3">
                <a href="{{ route('admin.flights.create') }}" class="p-4 rounded-xl bg-blue-50 border border-blue-100 hover:bg-blue-100 transition-colors text-center">
                    <svg class="w-6 h-6 text-tv-primary mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span class="text-xs font-bold text-tv-primary">Add Flight</span>
                </a>
                <a href="{{ route('admin.schedules.create') }}" class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 hover:bg-emerald-100 transition-colors text-center">
                    <svg class="w-6 h-6 text-emerald-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span class="text-xs font-bold text-emerald-600">Add Schedule</span>
                </a>
                <a href="{{ route('admin.airlines.create') }}" class="p-4 rounded-xl bg-purple-50 border border-purple-100 hover:bg-purple-100 transition-colors text-center">
                    <svg class="w-6 h-6 text-purple-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span class="text-xs font-bold text-purple-600">Add Airline</span>
                </a>
                <a href="{{ route('admin.airports.create') }}" class="p-4 rounded-xl bg-rose-50 border border-rose-100 hover:bg-rose-100 transition-colors text-center">
                    <svg class="w-6 h-6 text-rose-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span class="text-xs font-bold text-rose-600">Add Airport</span>
                </a>
            </div>
        </div>
    </div>
@endsection
