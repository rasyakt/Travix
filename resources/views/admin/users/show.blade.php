@extends('admin.layouts.admin')
@section('title', 'User Details')
@section('subtitle', $user->name)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- User Info --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 text-center">
                <img src="{{ $user->avatar_url }}" alt="" class="w-20 h-20 rounded-xl object-cover mx-auto mb-4 ring-2 ring-gray-100">
                <h3 class="text-lg font-extrabold text-tv-text">{{ $user->name }}</h3>
                <p class="text-sm text-tv-muted">{{ $user->email }}</p>
                @if($user->role === 'admin')
                    <span class="tv-badge bg-purple-50 text-purple-600 mt-2">Admin</span>
                @else
                    <span class="tv-badge bg-gray-100 text-gray-600 mt-2">User</span>
                @endif
                <div class="mt-5 pt-5 border-t border-gray-100 text-left space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-tv-muted">Phone</span><span class="font-medium">{{ $user->phone ?? '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-tv-muted">Nationality</span><span class="font-medium">{{ $user->nationality ?? '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-tv-muted">Joined</span><span class="font-medium">{{ $user->created_at->format('M d, Y') }}</span></div>
                    <div class="flex justify-between"><span class="text-tv-muted">Last Updated</span><span class="font-medium">{{ $user->updated_at->format('M d, Y') }}</span></div>
                </div>
                <div class="mt-5 flex gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-tv-primary text-xs py-2 px-4 flex-1">Edit User</a>
                </div>
            </div>
        </div>

        {{-- Bookings --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-tv-text">Recent Bookings ({{ $bookings->count() }})</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($bookings as $booking)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-tv-text font-mono">{{ $booking->display_booking_code ?? 'N/A' }}</p>
                                <p class="text-xs text-tv-muted">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="tv-badge {{ match($booking->status) { 'confirmed' => 'bg-emerald-50 text-emerald-600', 'pending' => 'bg-amber-50 text-amber-600', 'cancelled' => 'bg-red-50 text-red-600', default => 'bg-gray-100 text-gray-600' } }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                                <p class="text-[10px] text-tv-muted mt-1">{{ $booking->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-tv-muted">No bookings yet</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
