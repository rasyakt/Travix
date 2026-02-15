<div>
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Flight Details</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Flight Number</p>
                <p class="font-semibold">{{ $flight->flight_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Airline</p>
                <p class="font-semibold">{{ $flight->airline->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">From</p>
                <p class="font-semibold">{{ $flight->originAirport->name }} ({{ $flight->originAirport->iata_code }})</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">To</p>
                <p class="font-semibold">{{ $flight->destinationAirport->name }} ({{ $flight->destinationAirport->iata_code }})</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Departure</p>
                <p class="font-semibold">{{ $flight->departure_time->format('M d, Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Arrival</p>
                <p class="font-semibold">{{ $flight->arrival_time->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="createBooking" class="space-y-6">
        <!-- Travel Class Selection -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Select Travel Class</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($travelClasses as $class)
                <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none {{ $selectedClassId == $class['id'] ? 'border-blue-600 ring-2 ring-blue-600' : 'border-gray-300' }}">
                    <input type="radio" wire:model.live="selectedClassId" value="{{ $class['id'] }}" class="sr-only">
                    <div class="flex flex-1 flex-col">
                        <span class="block text-sm font-medium text-gray-900">{{ $class['name'] }}</span>
                        <span class="mt-1 flex items-center text-sm text-gray-500">{{ $class['available_seats'] }} seats available</span>
                        <span class="mt-2 text-lg font-bold text-blue-600">${{ number_format($class['price'], 2) }}</span>
                    </div>
                </label>
                @endforeach
            </div>
            @error('selectedClassId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Passenger Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Passenger Information</h2>
            @foreach($passengers as $index => $passenger)
            <div class="mb-6 pb-6 {{ $index < count($passengers) - 1 ? 'border-b border-gray-200' : '' }}">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Passenger {{ $index + 1 }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" wire:model="passengers.{{ $index }}.first_name" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error("passengers.{$index}.first_name") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" wire:model="passengers.{{ $index }}.last_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error("passengers.{$index}.last_name") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date" wire:model="passengers.{{ $index }}.date_of_birth"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error("passengers.{$index}.date_of_birth") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Passport Number (Optional)</label>
                        <input type="text" wire:model="passengers.{{ $index }}.passport_number"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error("passengers.{$index}.passport_number") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Nationality</label>
                        <input type="text" wire:model="passengers.{{ $index }}.nationality"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error("passengers.{$index}.nationality") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Contact Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model="contactEmail"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('contactEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="tel" wire:model="contactPhone"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('contactPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Total Price -->
        <div class="bg-blue-50 rounded-lg p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Total Price</p>
                    <p class="text-xs text-gray-500">{{ $numberOfPassengers }} passenger(s)</p>
                </div>
                <p class="text-3xl font-bold text-blue-600">${{ number_format($totalPrice, 2) }}</p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('flights.index') }}" class="px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                class="px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Continue to Payment
            </button>
        </div>
    </form>
</div>
