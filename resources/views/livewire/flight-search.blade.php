<div>
    {{-- ═══ Traveloka-Style Search Form ═══ --}}
    <div x-data="{ 
        tripType: @entangle('tripType'),
        showReturn: false,
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
                    :class="tripType === 'one-way' ? 'bg-tv-primary text-white shadow-lg' : 'bg-black/20 text-white/80 hover:bg-black/30'"
                    class="px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-200">
                    Sekali Jalan / Pulang Pergi
                </button>
                <button type="button" @click="tripType = 'multi-city'"
                    :class="tripType === 'multi-city' ? 'bg-tv-primary text-white shadow-lg' : 'bg-black/20 text-white/80 hover:bg-black/30'"
                    class="px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-200">
                    Multi-kota
                </button>
            </div>

            <div class="flex items-center gap-3">
                {{-- Passenger Dropdown --}}
                <div class="relative">
                    <button type="button" @click="passengersOpen = !passengersOpen"
                        class="flex items-center gap-2.5 px-4 py-2.5 bg-black/10 border border-white/20 rounded-lg text-white hover:bg-black/20 transition-all">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-sm font-bold" x-text="(adults + children + infants) + ' Penumpang'"></span>
                        <svg class="w-4 h-4 transition-transform" :class="passengersOpen ? 'rotate-180' : ''"
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
                        class="flex items-center gap-2.5 px-4 py-2.5 bg-black/10 border border-white/20 rounded-lg text-white hover:bg-black/20 transition-all">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm font-bold" x-text="seatClass"></span>
                        <svg class="w-4 h-4 transition-transform" :class="classOpen ? 'rotate-180' : ''" fill="none"
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
            class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8 mb-2 px-1 text-white text-[11px] font-bold uppercase tracking-wider">
            <div class="grid grid-cols-2 gap-4">
                <div>Dari</div>
                <div>Ke</div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>Tanggal pergi</div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" x-model="showReturn"
                        class="w-4 h-4 rounded text-tv-primary focus:ring-tv-primary/20 bg-white/20 border-white/40 cursor-pointer">
                    <span>Tanggal Pulang</span>
                </div>
            </div>
        </div>

        {{-- Main Input Box --}}
        <form wire:submit.prevent="searchFlights" class="flex flex-col lg:flex-row items-stretch gap-4">
            <div
                class="flex-1 bg-white rounded-2xl shadow-xl flex items-center divide-x divide-tv-border ring-1 ring-black/5">
                {{-- Origin --}}
                <div class="flex-1 relative group bg-white hover:bg-gray-50/50 transition-colors rounded-l-2xl">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                        <svg class="w-5 h-5 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="originSearch"
                        @focus="originOpen = true; $wire.refreshOriginSuggestions()" @click.away="originOpen = false"
                        class="w-full pl-12 pr-4 py-5 border-none focus:ring-0 text-tv-text font-bold placeholder-tv-muted/50"
                        placeholder="Asal (Misal: Jakarta)">

                    {{-- Origin Suggestions --}}
                    @if(!empty($originSuggestions))
                        <div x-show="originOpen" x-cloak
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
                    @endif
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
                <div class="flex-1 relative group bg-white hover:bg-gray-50/50 transition-colors rounded-r-2xl">
                    <div class="absolute left-6 top-1/2 -translate-y-1/2">
                        <svg class="w-5 h-5 text-tv-primary rotate-45" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="destinationSearch"
                        @focus="destinationOpen = true; $wire.refreshDestinationSuggestions()"
                        @click.away="destinationOpen = false"
                        class="w-full pl-14 pr-4 py-5 border-none focus:ring-0 text-tv-text font-bold placeholder-tv-muted/50"
                        placeholder="Tujuan (Misal: Singapore)">

                    {{-- Destination Suggestions --}}
                    @if(!empty($destinationSuggestions))
                        <div x-show="destinationOpen" x-cloak
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
                    @endif
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
                    :class="showReturn ? 'bg-white hover:bg-gray-50/50' : 'bg-gray-50/80'">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                        <svg class="w-5 h-5 transition-colors"
                            :class="showReturn ? 'text-tv-primary' : 'text-tv-muted/30'" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input type="date" wire:model="returnDate" :disabled="!showReturn"
                        class="w-full pl-12 pr-4 py-5 border-none focus:ring-0 font-bold transition-all"
                        :class="showReturn ? 'text-tv-text cursor-pointer' : 'text-tv-muted/30 cursor-not-allowed'">
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

        {{-- No Results Message --}}
        @if(session()->has('message'))
            <div
                class="mt-4 p-4 bg-blue-50/10 border border-blue-50/20 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-white text-sm font-bold">{{ session('message') }}</p>
            </div>
        @endif

        {{-- Validation Errors (Grouped) --}}
        @if($errors->any())
            <div class="mt-4 flex flex-wrap gap-2 animate-bounce-subtle">
                @foreach($errors->all() as $error)
                    <div
                        class="bg-tv-accent/90 text-white text-[10px] font-black px-3 py-1.5 rounded-lg shadow-xl flex items-center gap-2 border border-white/20 uppercase tracking-tighter">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ═══ Search Results (Styled to fit the new theme) ═══ --}}
    @if(!empty($searchResults))
        <div class="mt-12 space-y-4 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-xl font-extrabold text-white">Hasil Pencarian</h2>
                <span class="tv-badge-blue bg-white/10 text-white border-white/20">{{ count($searchResults) }}
                    Penerbangan</span>
            </div>

            @foreach($searchResults as $result)
                <div class="tv-card-hover p-5 md:p-6 bg-white/95 backdrop-blur-md">
                    <div class="flex flex-col md:flex-row md:items-center gap-5">
                        <div class="flex items-center gap-3 md:w-44 shrink-0">
                            @if(isset($result['airline_logo']))
                                <img src="{{ $result['airline_logo'] }}" alt="{{ $result['airline'] }}"
                                    class="h-10 w-10 rounded-lg object-contain bg-gray-50 p-1">
                            @else
                                <div class="h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-tv-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-tv-text text-sm">{{ $result['airline'] }}</p>
                                <div class="flex items-center gap-2">
                                    <p class="text-[10px] text-tv-muted uppercase font-mono">
                                        {{ $result['flight_number'] ?? '' }}</p>
                                    <span class="text-tv-border text-[10px]">·</span>
                                    <p class="text-[10px] text-tv-primary font-bold">{{ $result['aircraft'] ?? 'Aircraft' }}</p>
                                </div>
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
                                        <path
                                            d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                    </svg>
                                    <div class="h-px bg-tv-border flex-1 max-w-[40px]"></div>
                                    <div class="w-1.5 h-1.5 rounded-full bg-tv-accent/30"></div>
                                </div>
                                @if(isset($result['duration']))
                                    <p class="text-[10px] font-bold text-tv-muted mt-1">{{ floor($result['duration'] / 60) }}j
                                        {{ $result['duration'] % 60 }}m · Langsung
                                    </p>
                                @endif
                                {{-- Amenities Icons --}}
                                @if(!empty($result['amenities']))
                                    <div class="flex items-center justify-center gap-1.5 mt-2">
                                        @foreach($result['amenities'] as $amenity)
                                            @php
                                                $isWifi = str_contains(strtolower($amenity), 'wifi');
                                                $isPower = str_contains(strtolower($amenity), 'power') || str_contains(strtolower($amenity), 'usb');
                                                $isFood = str_contains(strtolower($amenity), 'food') || str_contains(strtolower($amenity), 'meal');
                                                $isEntertainment = str_contains(strtolower($amenity), 'entertainment') || str_contains(strtolower($amenity), 'video');
                                            @endphp
                                            @if($isWifi)
                                                <div class="group relative" title="WiFi">
                                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-4.08-7.071a9 9 0 0112.14 0M4.929 7.929a13 13 0 0118.142 0" />
                                                    </svg>
                                                </div>
                                            @elseif($isPower)
                                                <div class="group relative" title="Power/USB">
                                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    </svg>
                                                </div>
                                            @elseif($isFood)
                                                <div class="group relative" title="Makanan">
                                                    <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                </div>
                                            @endif
                                        @endforeach
                                        @if(isset($result['legroom']))
                                            <span
                                                class="text-[9px] font-black text-tv-muted bg-gray-100 px-1.5 py-0.5 rounded ml-1">{{ $result['legroom'] }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-black text-tv-text">{{ $result['arrival_time'] }}</p>
                                <p class="text-xs text-tv-muted font-bold">{{ $result['destination'] }}</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-4 md:flex-col md:items-end md:w-44 shrink-0 border-t md:border-t-0 md:border-l border-tv-border pt-4 md:pt-0 md:pl-6">
                            @if(isset($result['price']))
                                <div class="md:text-right">
                                    <p class="text-xl font-black text-tv-accent">Rp
                                        {{ number_format($result['price'], 0, ',', '.') }}
                                    </p>
                                    <p class="text-[10px] text-tv-muted font-bold">/orang</p>
                                </div>
                            @endif
                            <button wire:click="selectFlight({{ $result['id'] ?? 0 }})"
                                class="btn-tv-primary text-[11px] py-2.5 px-6 font-black ml-auto md:ml-0 shadow-lg shadow-tv-primary/10">
                                PILIH
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>