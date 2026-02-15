<div>
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('flights.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Search Flights</h3>
                    <p class="text-sm text-gray-500">Find your next destination</p>
                </div>
            </div>
        </a>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $upcomingFlights->count() }}</h3>
                    <p class="text-sm text-gray-500">Upcoming Flights</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-gray-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $pastFlights->count() }}</h3>
                    <p class="text-sm text-gray-500">Past Flights</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Flights -->
    @if($upcomingFlights->count() > 0)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Upcoming Flights</h2>
        <div class="space-y-4">
            @foreach($upcomingFlights as $booking)
            @php
                $flight = $booking->flights->first();
            @endphp
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                            <span class="text-sm text-gray-500">Booking Code: {{ $booking->booking_code }}</span>
                        </div>
                        
                        @if($flight)
                        <h3 class="text-lg font-semibold text-gray-900">{{ $flight->originAirport->city }} → {{ $flight->destinationAirport->city }}</h3>
                        <p class="text-sm text-gray-600">{{ $flight->airline->name }} - {{ $flight->flight_number }}</p>
                        <p class="text-sm text-gray-500 mt-2">{{ $flight->departure_time->format('M d, Y H:i') }}</p>
                        @endif
                    </div>
                    
                    <div class="text-right">
                        <p class="text-xl font-bold text-blue-600">${{ number_format($booking->total_price, 2) }}</p>
                        <div class="mt-4 space-x-2">
                            <a href="{{ route('booking.show', $booking->id) }}" 
                                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                View Details
                            </a>
                            @if($booking->status !== 'cancelled')
                            <button wire:click="cancelBooking({{ $booking->id }})" 
                                wire:confirm="Are you sure you want to cancel this booking?"
                                class="inline-flex items-center px-3 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                Cancel
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Past Flights -->
    @if($pastFlights->count() > 0)
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Past Flights</h2>
        <div class="space-y-4">
            @foreach($pastFlights as $booking)
            @php
                $flight = $booking->flights->first();
            @endphp
            <div class="bg-gray-50 rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">
                                Completed
                            </span>
                            <span class="text-sm text-gray-500">Booking Code: {{ $booking->booking_code }}</span>
                        </div>
                        
                        @if($flight)
                        <h3 class="text-lg font-semibold text-gray-700">{{ $flight->originAirport->city }} → {{ $flight->destinationAirport->city }}</h3>
                        <p class="text-sm text-gray-600">{{ $flight->airline->name }} - {{ $flight->flight_number }}</p>
                        <p class="text-sm text-gray-500 mt-2">{{ $flight->departure_time->format('M d, Y H:i') }}</p>
                        @endif
                    </div>
                    
                    <div class="text-right">
                        <p class="text-lg font-semibold text-gray-600">${{ number_format($booking->total_price, 2) }}</p>
                        <a href="{{ route('booking.show', $booking->id) }}" 
                            class="mt-4 inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($bookings->count() === 0)
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings yet</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by searching for flights.</p>
        <div class="mt-6">
            <a href="{{ route('flights.index') }}" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Search Flights
            </a>
        </div>
    </div>
    @endif
</div>
