<div>
    {{-- ═══ Search Form ═══ --}}
    <form wire:submit.prevent="searchFlights">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <div>
                <label for="origin" class="tv-label">From</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#a0aec0]" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <input type="text" wire:model="origin" id="origin" maxlength="3" class="tv-input pl-10 uppercase"
                        placeholder="e.g. CGK">
                </div>
                @error('origin') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="destination" class="tv-label">To</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#a0aec0]" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <input type="text" wire:model="destination" id="destination" maxlength="3"
                        class="tv-input pl-10 uppercase" placeholder="e.g. DPS">
                </div>
                @error('destination') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="departureDate" class="tv-label">Departure</label>
                <input type="date" wire:model="departureDate" id="departureDate" class="tv-input">
                @error('departureDate') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="passengers" class="tv-label">Passengers</label>
                <input type="number" wire:model="passengers" id="passengers" min="1" max="9" class="tv-input"
                    placeholder="1">
                @error('passengers') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="btn-tv-accent py-3.5 px-10 text-sm gap-2 w-full md:w-auto"
                wire:loading.attr="disabled">
                <svg class="w-5 h-5" wire:loading.remove fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span wire:loading.remove>Search Flights</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                        </path>
                    </svg>
                    Searching...
                </span>
            </button>
        </div>
    </form>

    {{-- ═══ Session Message ═══ --}}
    @if(session()->has('message'))
        <div
            class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-700 px-5 py-3.5 rounded-xl text-sm font-medium mt-6">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- ═══ Search Results ═══ --}}
    @if(!empty($searchResults))
        <div class="mt-8 space-y-4">
            <div class="flex items-center justify-between mb-2">
                <h2 class="tv-section-title text-xl">Available Flights</h2>
                <span class="tv-badge-blue">{{ count($searchResults) }}
                    {{ Str::plural('result', count($searchResults)) }}</span>
            </div>

            @foreach($searchResults as $result)
                <div class="tv-card-hover p-5 md:p-6">
                    <div class="flex flex-col md:flex-row md:items-center gap-5">
                        {{-- Airline Logo + Info --}}
                        <div class="flex items-center gap-3 md:w-44 flex-shrink-0">
                            @if(isset($result['airline_logo']))
                                <img src="{{ $result['airline_logo'] }}" alt="{{ $result['airline'] }}"
                                    class="h-10 w-10 rounded-lg object-contain bg-gray-50 p-1">
                            @else
                                <div class="h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#0770e3]" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-[#1a2b49] text-sm">{{ $result['airline'] }}</p>
                                <p class="text-xs text-[#687b8e]">{{ $result['flight_number'] ?? '' }}</p>
                            </div>
                        </div>

                        {{-- Route Timeline --}}
                        <div class="flex-1 grid grid-cols-3 gap-3 items-center">
                            <div>
                                <p class="text-xl font-extrabold text-[#1a2b49]">{{ $result['departure_time'] }}</p>
                                <p class="text-xs text-[#687b8e] font-medium">{{ $result['origin'] }}<span
                                        class="hidden sm:inline"> · {{ $result['origin_name'] }}</span></p>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center gap-1 justify-center">
                                    <div class="w-2 h-2 rounded-full border-2 border-[#0770e3]"></div>
                                    <div class="h-px bg-[#e8ecf1] flex-1 min-w-[30px]"></div>
                                    <svg class="w-4 h-4 text-[#0770e3]" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                    </svg>
                                    <div class="h-px bg-[#e8ecf1] flex-1 min-w-[30px]"></div>
                                    <div class="w-2 h-2 rounded-full border-2 border-[#ff5e1f]"></div>
                                </div>
                                @if(isset($result['duration']))
                                    <p class="text-[10px] font-semibold text-[#687b8e] mt-1">{{ floor($result['duration'] / 60) }}h
                                        {{ $result['duration'] % 60 }}m · Direct</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-extrabold text-[#1a2b49]">{{ $result['arrival_time'] }}</p>
                                <p class="text-xs text-[#687b8e] font-medium">{{ $result['destination'] }}<span
                                        class="hidden sm:inline"> · {{ $result['destination_name'] }}</span></p>
                            </div>
                        </div>

                        {{-- Price + Action --}}
                        <div
                            class="flex items-center gap-4 md:flex-col md:items-end md:w-40 flex-shrink-0 border-t md:border-t-0 md:border-l border-[#e8ecf1] pt-4 md:pt-0 md:pl-5">
                            @if(isset($result['price']))
                                <div class="md:text-right">
                                    <p class="text-xl font-extrabold text-[#ff5e1f]">${{ number_format($result['price'], 2) }}</p>
                                    <p class="text-[10px] text-[#a0aec0] font-medium">/person</p>
                                </div>
                            @endif
                            @if(isset($result['available_seats']))
                                <span class="tv-badge-green text-[10px] hidden md:inline-flex">{{ $result['available_seats'] }}
                                    left</span>
                            @endif
                            @if(isset($result['id']))
                                <button wire:click="selectFlight({{ $result['id'] }})"
                                    class="btn-tv-primary text-xs py-2 px-5 ml-auto md:ml-0">
                                    Select
                                </button>
                            @else
                                <span class="tv-badge-gray text-[10px]">API Result</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>