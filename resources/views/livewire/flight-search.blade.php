<div>
    {{-- ═══ Traveloka-Style Search Form ═══ --}}
    <div x-data="{ 
        tripType: @entangle('tripType'),
        passengersOpen: false,
        classOpen: false,
        adults: @entangle('adults'),
        children: @entangle('children'),
        infants: @entangle('infants'),
        seatClass: @entangle('seatClass'),
        originOpen: false,
        destinationOpen: false
    }" class="relative z-20">

        {{-- Top Row: Toggles & Dropdowns --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div class="flex items-center gap-2">
                <button type="button" @click="tripType = 'one-way'"
                    :class="tripType === 'one-way' ? 'bg-tv-primary text-white shadow-lg' : 'bg-gray-100 text-tv-secondary hover:bg-gray-200'"
                    class="px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-200">
                    Sekali Jalan
                </button>
                <button type="button" @click="tripType = 'round-trip'"
                    :class="tripType === 'round-trip' ? 'bg-tv-primary text-white shadow-lg' : 'bg-gray-100 text-tv-secondary hover:bg-gray-200'"
                    class="px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-200">
                    Pulang Pergi
                </button>
                <button type="button" disabled
                    class="px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-200 bg-gray-100 text-tv-muted cursor-not-allowed opacity-70">
                    Multi-kota
                </button>
            </div>

            <div class="flex items-center gap-3">
                {{-- Passenger Dropdown --}}
                <div class="relative">
                    <button type="button" @click="passengersOpen = !passengersOpen"
                        class="flex items-center gap-2.5 px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-tv-secondary hover:bg-gray-200 transition-all font-bold">
                        <svg class="w-5 h-5 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-sm" x-text="(adults + children + infants) + ' Penumpang'"></span>
                        <svg class="w-4 h-4 transition-transform text-tv-muted" :class="passengersOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="passengersOpen" @click.away="passengersOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-2xl p-5 z-50 border border-tv-border">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-tv-text text-sm">Dewasa</p>
                                    <p class="text-[10px] text-tv-muted">(Usia 12+)</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="adults = Math.max(1, adults - 1)"
                                        class="w-8 h-8 rounded-full border border-tv-border flex items-center justify-center text-tv-primary hover:border-tv-primary">-</button>
                                    <span class="w-4 text-center font-bold text-tv-text" x-text="adults"></span>
                                    <button type="button" @click="adults = Math.min(9, adults + 1)"
                                        class="w-8 h-8 rounded-full border border-tv-border flex items-center justify-center text-tv-primary hover:border-tv-primary">+</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-tv-text text-sm">Anak</p>
                                    <p class="text-[10px] text-tv-muted">(Usia 2 - 11)</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="children = Math.max(0, children - 1)"
                                        class="w-8 h-8 rounded-full border border-tv-border flex items-center justify-center text-tv-primary hover:border-tv-primary">-</button>
                                    <span class="w-4 text-center font-bold text-tv-text" x-text="children"></span>
                                    <button type="button" @click="children = Math.min(9, children + 1)"
                                        class="w-8 h-8 rounded-full border border-tv-border flex items-center justify-center text-tv-primary hover:border-tv-primary">+</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-tv-text text-sm">Bayi</p>
                                    <p class="text-[10px] text-tv-muted">(Di bawah 2)</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="infants = Math.max(0, infants - 1)"
                                        class="w-8 h-8 rounded-full border border-tv-border flex items-center justify-center text-tv-primary hover:border-tv-primary">-</button>
                                    <span class="w-4 text-center font-bold text-tv-text" x-text="infants"></span>
                                    <button type="button" @click="infants = Math.min(9, infants + 1)"
                                        class="w-8 h-8 rounded-full border border-tv-border flex items-center justify-center text-tv-primary hover:border-tv-primary">+</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="passengersOpen = false"
                            class="w-full mt-5 btn-tv-primary py-2 text-xs">Selesai</button>
                    </div>
                </div>

                {{-- Class Dropdown --}}
                <div class="relative">
                    <button type="button" @click="classOpen = !classOpen"
                        class="flex items-center gap-2.5 px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-tv-secondary hover:bg-gray-200 transition-all font-bold">
                        <svg class="w-5 h-5 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm" x-text="seatClass"></span>
                        <svg class="w-4 h-4 transition-transform text-tv-muted" :class="classOpen ? 'rotate-180' : ''" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="classOpen" @click.away="classOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl py-2 z-50 border border-tv-border">
                        @foreach(['Economy', 'Premium Economy', 'Business', 'First Class'] as $class)
                            <button type="button" @click="seatClass = '{{ $class }}'; classOpen = false"
                                class="w-full text-left px-5 py-2.5 text-sm hover:bg-blue-50 transition-colors"
                                :class="seatClass === '{{ $class }}' ? 'text-tv-primary font-bold bg-blue-50/50' : 'text-tv-text'">
                                {{ $class }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Labels Row --}}
        <div
            class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8 mb-2 px-1 text-tv-secondary text-[11px] font-black uppercase tracking-widest">
            <div class="grid grid-cols-2 gap-4">
                <div>Dari</div>
                <div>Ke</div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>Tanggal pergi</div>
                <div :class="tripType === 'round-trip' ? '' : 'text-tv-muted/50'">
                    Tanggal Pulang
                </div>
            </div>
        </div>

        {{-- Main Input Box --}}
        <form wire:submit.prevent="searchFlights" class="flex flex-col lg:flex-row items-stretch gap-4">
            <div
                class="flex-1 bg-white rounded-2xl shadow-xl flex items-center divide-x divide-tv-border ring-1 ring-black/5">
                {{-- Origin --}}
                <div class="flex-1 relative group bg-white hover:bg-gray-50/50 transition-colors rounded-l-2xl" 
                    @click.away="originOpen = false" wire:key="origin-input-wrapper">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                        <svg class="w-5 h-5 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="originSearch"
                        wire:key="origin-input-field"
                        @focus="originOpen = true; $wire.refreshOriginSuggestions(true); $nextTick(() => $el.select())"
                        @click="originOpen = true; $wire.refreshOriginSuggestions(true); $el.select()"
                        class="w-full pl-12 pr-4 py-5 border-none focus:ring-0 text-tv-text font-bold placeholder-tv-muted"
                        placeholder="Asal (...)">

                    {{-- Origin Suggestions --}}
                    <div x-show="originOpen && $wire.originSuggestions.length > 0" x-cloak
                        wire:ignore.self
                        class="absolute left-0 right-0 top-full mt-3 bg-white rounded-2xl shadow-[0_20px_60px_rgba(7,112,227,0.18)] z-50 border border-tv-border max-h-[500px] overflow-y-auto w-full md:min-w-[500px] scrollbar-thin scrollbar-thumb-tv-primary/20 scrollbar-track-transparent pb-4">
                            <div
                                class="px-6 py-4 bg-gray-50/50 border-b border-tv-border flex items-center justify-between">
                                <span class="text-xs font-black text-tv-muted uppercase tracking-widest">Kota atau Bandara
                                    Populer</span>
                                <svg class="w-4 h-4 text-tv-muted/40" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            @foreach($originSuggestions as $index => $suggestion)
                                <button type="button" wire:key="origin-suggestion-{{ $suggestion['iata_code'] }}-{{ $index }}"
                                    wire:click="selectOrigin('{{ $suggestion['iata_code'] }}', '{{ $suggestion['city'] }}')"
                                    @click="originOpen = false"
                                    class="w-full text-left px-5 py-4 hover:bg-blue-50/80 transition-all border-b border-gray-50 last:border-0 flex items-center gap-4 group/item">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center shrink-0 group-hover/item:bg-white transition-colors">
                                        <svg class="w-5 h-5 text-tv-muted group-hover/item:text-tv-primary" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-0.5">
                                            <p
                                                class="font-extrabold text-tv-text text-sm group-hover/item:text-tv-primary transition-colors truncate">
                                                {{ $suggestion['city'] }}
                                            </p>
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-[10px] font-black text-tv-primary bg-blue-50 px-2 py-0.5 rounded uppercase">{{ $suggestion['iata_code'] }}</span>
                                            </div>
                                        </div>
                                        <p class="text-[11px] text-tv-muted truncate font-medium">{{ $suggestion['name'] }}</p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                </div>

                {{-- Swap Button --}}
                <div class="flex items-center justify-center p-0 z-10 -mx-4">
                    <button type="button"
                        @click="$wire.origin = [$wire.destination, $wire.destination = $wire.origin][0]; $wire.originSearch = [$wire.destinationSearch, $wire.destinationSearch = $wire.originSearch][0]"
                        class="w-8 h-8 bg-white border border-tv-border rounded-full flex items-center justify-center text-tv-primary shadow-sm hover:shadow-md hover:border-tv-primary transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </button>
                </div>

                {{-- Destination --}}
                <div class="flex-1 relative group bg-white hover:bg-gray-50/50 transition-colors rounded-r-2xl"
                    @click.away="destinationOpen = false" wire:key="destination-input-wrapper">
                    <div class="absolute left-6 top-1/2 -translate-y-1/2">
                        <svg class="w-5 h-5 text-tv-primary rotate-45" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="destinationSearch"
                        wire:key="destination-input-field"
                        @focus="destinationOpen = true; $wire.refreshDestinationSuggestions(true); $nextTick(() => $el.select())"
                        @click="destinationOpen = true; $wire.refreshDestinationSuggestions(true); $el.select()"
                        class="w-full pl-14 pr-4 py-5 border-none focus:ring-0 text-tv-text font-bold placeholder-tv-muted"
                        placeholder="Tujuan (...)">

                    {{-- Destination Suggestions --}}
                    <div x-show="destinationOpen && $wire.destinationSuggestions.length > 0" x-cloak
                        wire:ignore.self
                        class="absolute left-0 right-0 top-full mt-3 bg-white rounded-2xl shadow-[0_20px_60px_rgba(7,112,227,0.18)] z-50 border border-tv-border max-h-[500px] overflow-y-auto w-full md:min-w-[500px] scrollbar-thin scrollbar-thumb-tv-primary/20 scrollbar-track-transparent pb-4">
                            <div
                                class="px-6 py-4 bg-gray-50/50 border-b border-tv-border flex items-center justify-between">
                                <span class="text-xs font-black text-tv-muted uppercase tracking-widest">Kota atau Bandara
                                    Populer</span>
                                <svg class="w-4 h-4 text-tv-muted/40" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            @foreach($destinationSuggestions as $index => $suggestion)
                                <button type="button" wire:key="dest-suggestion-{{ $suggestion['iata_code'] }}-{{ $index }}"
                                    wire:click="selectDestination('{{ $suggestion['iata_code'] }}', '{{ $suggestion['city'] }}')"
                                    @click="destinationOpen = false"
                                    class="w-full text-left px-5 py-4 hover:bg-blue-50/80 transition-all border-b border-gray-50 last:border-0 flex items-center gap-4 group/item">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center shrink-0 group-hover/item:bg-white transition-colors">
                                        <svg class="w-5 h-5 text-tv-muted group-hover/item:text-tv-primary rotate-45"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-0.5">
                                            <p
                                                class="font-extrabold text-tv-text text-sm group-hover/item:text-tv-primary transition-colors truncate">
                                                {{ $suggestion['city'] }}
                                            </p>
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-[10px] font-black text-tv-primary bg-blue-50 px-2 py-0.5 rounded uppercase">{{ $suggestion['iata_code'] }}</span>
                                            </div>
                                        </div>
                                        <p class="text-[11px] text-tv-muted truncate font-medium">{{ $suggestion['name'] }}</p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                </div>
            </div>

            <div
                class="flex-1 bg-white rounded-2xl shadow-xl flex items-center divide-x divide-tv-border ring-1 ring-black/5">
                {{-- Departure Date --}}
                <div class="flex-1 relative group bg-white hover:bg-gray-50/50 transition-colors rounded-l-2xl">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                        <svg class="w-5 h-5 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input type="date" wire:model="departureDate"
                        class="w-full pl-12 pr-4 py-5 border-none focus:ring-0 text-tv-text font-bold cursor-pointer">
                </div>

                {{-- Return Date --}}
                <div class="flex-1 relative group bg-white transition-colors rounded-r-2xl"
                    :class="tripType === 'round-trip' ? 'bg-white hover:bg-gray-50/50' : 'bg-gray-50/80'">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                        <svg class="w-5 h-5 transition-colors"
                            :class="tripType === 'round-trip' ? 'text-tv-primary' : 'text-tv-muted/30'" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input type="date" wire:model="returnDate" :disabled="tripType !== 'round-trip'"
                        class="w-full pl-12 pr-4 py-5 border-none focus:ring-0 font-bold transition-all"
                        :class="tripType === 'round-trip' ? 'text-tv-text cursor-pointer' : 'text-tv-muted/30 cursor-not-allowed'">
                </div>
            </div>

            {{-- Search Button --}}
            <button type="submit" wire:loading.attr="disabled"
                class="bg-tv-accent hover:bg-tv-accent/90 text-white rounded-2xl md:rounded-xl lg:rounded-2xl p-5 shadow-lg shadow-tv-accent/20 transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center shrink-0 min-w-[64px] disabled:opacity-50 disabled:cursor-not-allowed">
                <svg wire:loading.remove wire:target="searchFlights" class="w-6 h-6" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <svg wire:loading wire:target="searchFlights" class="animate-spin h-6 w-6 text-white" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </button>
        </form>

        {{-- Session Messages --}}
        @if(session()->has('message'))
            <div class="mt-4 p-4 bg-blue-50/10 border border-blue-50/20 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-white text-sm font-bold">{{ session('message') }}</p>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mt-4 flex flex-wrap gap-2 animate-bounce-subtle">
                @foreach($errors->all() as $error)
                    <div class="bg-tv-accent/90 text-white text-[10px] font-black px-3 py-1.5 rounded-lg shadow-xl flex items-center gap-2 border border-white/20 uppercase tracking-tighter">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Daily Prices Strip --}}
    @if(!empty($dailyPrices))
        <div class="mt-8 mb-6 overflow-x-auto pb-4 scrollbar-thin scrollbar-thumb-white/20 scrollbar-track-transparent">
            <div class="flex items-center gap-3 min-w-max">
                @foreach($dailyPrices as $price)
                    <button type="button" wire:click="changeDate('{{ $price['date'] }}')"
                        class="min-w-[160px] flex flex-col items-center justify-center px-6 py-3 rounded-xl border transition-all
                        {{ $price['is_current'] 
                            ? 'bg-white text-tv-primary border-white shadow-lg scale-105 z-10' 
                            : 'bg-white text-tv-text border-tv-border hover:border-tv-primary/30 hover:bg-blue-50/60 shadow-sm' }}">
                        <span class="text-[10px] font-bold uppercase tracking-wider mb-1 {{ $price['is_current'] ? 'text-tv-primary/70' : 'text-tv-muted' }}">{{ $price['label'] }}</span>
                        <span class="text-sm font-black">Rp {{ number_format($price['price'], 0, ',', '.') }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Main Content Area (Filters + Res    {{-- Main Content Area (Filters + Results) --}}
    @if($isFullPage)
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            {{-- Filter Sidebar --}}
            @if(!empty($searchResults))
                <div class="w-full lg:w-72 shrink-0 space-y-6">
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-tv-border">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-black text-tv-text uppercase tracking-tight text-sm">Filter</h3>
                            <button type="button" wire:click="$set('selectedAirlines', [])" class="text-tv-primary text-[10px] font-black uppercase hover:underline">Reset</button>
                        </div>

                        {{-- Airline Filter --}}
                        <div>
                            <p class="text-[11px] font-black text-tv-muted uppercase tracking-widest mb-4">Maskapai</p>
                            <div class="space-y-3">
                                @foreach($filterAirlines as $airline)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" wire:model.live="selectedAirlines" value="{{ $airline }}"
                                            class="w-5 h-5 rounded border-tv-border text-tv-primary focus:ring-tv-primary/20 cursor-pointer">
                                        <span class="text-sm font-bold text-tv-text group-hover:text-tv-primary transition-colors">{{ $airline }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Price Filter --}}
                        @if(isset($maxPrice))
                            <div class="mt-8 pt-8 border-t border-tv-border">
                                <p class="text-[11px] font-black text-tv-muted uppercase tracking-widest mb-4">Harga Tertinggi</p>
                                <div class="px-2">
                                    <input type="range" min="0" max="{{ $maxPrice }}" step="100000"
                                        class="w-full h-1.5 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-tv-primary">
                                    <div class="flex justify-between mt-3">
                                        <span class="text-[10px] font-bold text-tv-muted">Rp 0</span>
                                        <span class="text-[10px] font-bold text-tv-primary">Rp {{ number_format($maxPrice, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Promo Banner --}}
                    <div class="bg-linear-to-br from-tv-primary to-[#0052ad] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                        </div>
                        <p class="text-[11px] font-black uppercase tracking-widest mb-1">Promo Khusus</p>
                        <h4 class="text-lg font-black leading-tight mb-3">Diskon Rp 100rb untuk pengguna baru!</h4>
                        <button class="bg-white text-tv-primary px-4 py-2 rounded-lg text-[10px] font-black uppercase shadow-lg">Pakai Sekarang</button>
                    </div>
                </div>
            @endif

            {{-- Results Column --}}
            <div class="flex-1 space-y-4 w-full">
                @if(!empty($flights))
                    <div class="flex items-center justify-between px-2 mb-2">
                        <h2 class="text-xl font-extrabold text-white">Hasil Pencarian</h2>
                        <span class="tv-badge-blue bg-white/10 text-white border-white/20">{{ count($flights) }} Penerbangan</span>
                    </div>

                    @foreach($flights as $result)
                        <div x-data="{ open: false }" class="tv-card-hover overflow-visible transition-all bg-white/95 backdrop-blur-md">
                            <div class="p-5 md:p-6">
                                <div class="flex flex-col md:flex-row md:items-center gap-5">
                                    <div class="flex items-center gap-3 md:w-44 shrink-0">
                                        @if(isset($result['airline_logo']))
                                            <img src="{{ $result['airline_logo'] }}" alt="{{ $result['airline'] }}"
                                                class="h-10 w-10 rounded-lg object-contain bg-gray-50 p-1">
                                        @else
                                            <div class="h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-tv-primary" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-tv-text text-sm">{{ $result['airline'] }}</p>
                                            <p class="text-[10px] text-tv-muted uppercase font-mono">{{ $result['flight_number'] ?? '' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex-1 grid grid-cols-3 gap-3 items-center">
                                        <div>
                                            <p class="text-2xl font-black text-tv-text">{{ $result['departure_time'] }}</p>
                                            <p class="text-xs text-tv-muted font-bold">{{ $result['origin'] }}</p>
                                        </div>
                                        <div class="text-center">
                                            <div class="flex items-center gap-2 justify-center">
                                                <div class="w-1.5 h-1.5 rounded-full bg-tv-primary/30"></div>
                                                <div class="h-px bg-tv-border flex-1 max-w-[40px]"></div>
                                                <svg class="w-4 h-4 text-tv-primary" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                                </svg>
                                                <div class="h-px bg-tv-border flex-1 max-w-[40px]"></div>
                                                <div class="w-1.5 h-1.5 rounded-full bg-tv-accent/30"></div>
                                            </div>
                                            @if(isset($result['duration']))
                                                <p class="text-[10px] font-bold text-tv-muted mt-1">{{ floor($result['duration'] / 60) }}j {{ $result['duration'] % 60 }}m · Langsung</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-black text-tv-text">{{ $result['arrival_time'] }}</p>
                                            <p class="text-xs text-tv-muted font-bold">{{ $result['destination'] }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 md:flex-col md:items-end md:w-44 shrink-0 border-t md:border-t-0 md:border-l border-tv-border pt-4 md:pt-0 md:pl-6">
                                        @if(isset($result['price']))
                                            <div class="md:text-right">
                                                <p class="text-xl font-black text-tv-accent">Rp {{ number_format($result['price'], 0, ',', '.') }}</p>
                                                <p class="text-[10px] text-tv-muted font-bold">/orang</p>
                                            </div>
                                        @endif
                                        @if(!empty($result['bookable']) && !empty($result['id']))
                                            <button wire:click="selectFlight({{ $result['id'] }})"
                                                class="btn-tv-primary text-[11px] py-2.5 px-6 font-black ml-auto md:ml-0 shadow-lg">PILIH</button>
                                        @else
                                            <button type="button" wire:click="notifyUnbookableFlight"
                                                class="bg-gray-100 text-tv-muted border border-tv-border text-[11px] py-2.5 px-4 rounded-lg font-black ml-auto md:ml-0">
                                                PARTNER API
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Footer Actions --}}
                            <div class="bg-gray-50/80 px-5 py-3 border-t border-tv-border flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <button @click="open = !open" class="text-tv-primary text-[10px] font-black uppercase flex items-center gap-1.5 hover:opacity-80 transition-opacity">
                                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        Detail Penerbangan
                                    </button>
                                    @if(!empty($result['amenities']))
                                        <div class="flex items-center gap-3 border-l border-tv-border pl-4">
                                            @foreach(array_slice($result['amenities'], 0, 3) as $amenity)
                                                @php
                                                    $isWifi = str_contains(strtolower($amenity), 'wifi');
                                                    $isPower = str_contains(strtolower($amenity), 'power') || str_contains(strtolower($amenity), 'usb');
                                                    $isFood = str_contains(strtolower($amenity), 'food') || str_contains(strtolower($amenity), 'meal');
                                                @endphp
                                                @if($isWifi) <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="WiFi"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-4.08-7.071a9 9 0 0112.14 0M4.929 7.929a13 13 0 0118.142 0"/></svg>
                                                @elseif($isPower) <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Power"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                @elseif($isFood) <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Makanan"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <span class="text-[10px] font-black text-tv-primary bg-blue-50 px-2 py-0.5 rounded">{{ $result['aircraft'] }}</span>
                            </div>

                            {{-- Details Panel --}}
                            <div x-show="open" x-collapse x-cloak class="border-t border-tv-border bg-gray-50/50 p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <h4 class="text-xs font-black text-tv-text uppercase mb-4 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Rincian Penerbangan
                                        </h4>
                                        <div class="space-y-4">
                                            <div class="flex items-start gap-4">
                                                <div class="flex flex-col items-center gap-1 mt-1">
                                                    <div class="w-2 h-2 rounded-full border-2 border-tv-primary bg-white"></div>
                                                    <div class="w-0.5 h-12 bg-tv-border border-dashed"></div>
                                                    <div class="w-2 h-2 rounded-full bg-tv-accent"></div>
                                                </div>
                                                <div class="space-y-8">
                                                    <div>
                                                        <p class="text-sm font-black text-tv-text">{{ $result['departure_time'] }} · {{ $result['origin_name'] }} ({{ $result['origin'] }})</p>
                                                        <p class="text-[11px] text-tv-muted mt-0.5">{{ \Carbon\Carbon::parse($departureDate)->format('d M Y') }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-black text-tv-text">{{ $result['arrival_time'] }} · {{ $result['destination_name'] }} ({{ $result['destination'] }})</p>
                                                        <p class="text-[11px] text-tv-muted mt-0.5">{{ \Carbon\Carbon::parse($departureDate)->format('d M Y') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-tv-text uppercase mb-4 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                            Fasilitas & Bagasi
                                        </h4>
                                        <div class="bg-white rounded-xl p-4 border border-tv-border shadow-sm space-y-3">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="text-tv-muted font-bold">Bagasi Kabin</span>
                                                <span class="text-tv-text font-black">7 kg</span>
                                            </div>
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="text-tv-muted font-bold">Bagasi Terdaftar</span>
                                                <span class="text-tv-text font-black">20 kg</span>
                                            </div>
                                            <div class="pt-3 border-t border-tv-border">
                                                <p class="text-[10px] font-black text-tv-muted uppercase mb-2">Amenities</p>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($result['amenities'] as $amenity)
                                                        <span class="px-2.5 py-1 bg-gray-50 rounded-lg text-[10px] font-bold text-tv-text border border-tv-border">{{ $amenity }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @elseif($searching)
                    <div class="flex flex-col items-center justify-center py-20 text-white">
                        <svg class="animate-spin h-10 w-10 mb-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <p class="font-black uppercase tracking-widest text-sm">Mencari Penerbangan Terbaik...</p>
                    </div>
                @elseif(!empty($originSearch) && !empty($destinationSearch))
                    <div class="tv-card p-12 text-center bg-white rounded-2xl shadow-xl">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-tv-text mb-2">Maaf, Tidak Ada Penerbangan</h3>
                        <p class="text-tv-muted text-sm max-w-sm mx-auto">Coba ganti tanggal atau rute pencarian Anda untuk melihat jadwal lainnya.</p>
                        <button wire:click="changeDate('{{ now()->addDay()->format('Y-m-d') }}')" class="mt-8 btn-tv-primary px-8">Lihat Besok</button>
                    </div>
                @endif
            </div>
        </div>
    @endif
    </div>
</div>