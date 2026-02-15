<div>
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form wire:submit.prevent="searchFlights" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="origin" class="block text-sm font-medium text-gray-700">From (IATA Code)</label>
                    <input type="text" wire:model="origin" id="origin" maxlength="3" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 uppercase"
                        placeholder="e.g., JFK">
                    @error('origin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="destination" class="block text-sm font-medium text-gray-700">To (IATA Code)</label>
                    <input type="text" wire:model="destination" id="destination" maxlength="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 uppercase"
                        placeholder="e.g., LAX">
                    @error('destination') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="departureDate" class="block text-sm font-medium text-gray-700">Departure Date</label>
                    <input type="date" wire:model="departureDate" id="departureDate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('departureDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="passengers" class="block text-sm font-medium text-gray-700">Passengers</label>
                    <input type="number" wire:model="passengers" id="passengers" min="1" max="9"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('passengers') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-center">
                <button type="submit" 
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Search Flights</span>
                    <span wire:loading>Searching...</span>
                </button>
            </div>
        </form>
    </div>

    @if(session()->has('message'))
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
        {{ session('message') }}
    </div>
    @endif

    @if(!empty($searchResults))
    <div class="space-y-4">
        <h2 class="text-2xl font-bold text-gray-900">Available Flights</h2>
        
        @foreach($searchResults as $result)
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex justify-between items-center">
                <div class="flex-1">
                    <div class="flex items-center space-x-4">
                        @if(isset($result['airline_logo']))
                        <img src="{{ $result['airline_logo'] }}" alt="{{ $result['airline'] }}" class="h-12 w-12 object-contain">
                        @endif
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $result['airline'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $result['flight_number'] ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Departure</p>
                            <p class="text-xl font-bold">{{ $result['departure_time'] }}</p>
                            <p class="text-sm text-gray-600">{{ $result['origin'] }} - {{ $result['origin_name'] }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Duration</p>
                            <div class="flex items-center justify-center my-2">
                                <div class="h-px bg-gray-300 flex-1"></div>
                                <svg class="h-5 w-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                                </svg>
                                <div class="h-px bg-gray-300 flex-1"></div>
                            </div>
                            @if(isset($result['duration']))
                            <p class="text-sm text-gray-600">{{ floor($result['duration'] / 60) }}h {{ $result['duration'] % 60 }}m</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Arrival</p>
                            <p class="text-xl font-bold">{{ $result['arrival_time'] }}</p>
                            <p class="text-sm text-gray-600">{{ $result['destination'] }} - {{ $result['destination_name'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="ml-6 text-right">
                    @if(isset($result['price']))
                    <p class="text-2xl font-bold text-blue-600">${{ number_format($result['price'], 2) }}</p>
                    <p class="text-sm text-gray-500">per person</p>
                    @endif
                    
                    @if(isset($result['available_seats']))
                    <p class="text-xs text-gray-500 mt-2">{{ $result['available_seats'] }} seats left</p>
                    @endif

                    @if(isset($result['id']))
                    <button wire:click="selectFlight({{ $result['id'] }})" 
                        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Select Flight
                    </button>
                    @else
                    <p class="mt-4 text-xs text-gray-400">API Result - Not bookable</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
