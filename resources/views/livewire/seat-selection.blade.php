<div>
    {{-- ═══ Travel Class Selection ═══ --}}
    <div class="tv-card p-6 mb-6">
        <h2 class="font-bold text-tv-text mb-4">Select Travel Class</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            @foreach($travelClasses as $class)
                <label
                    @class([
                        'relative flex cursor-pointer rounded-xl border-2 p-5 transition-all',
                        'border-tv-primary bg-blue-50/50 shadow-sm' => $selectedTravelClassId == $class['id'],
                        'border-tv-border hover:border-[#d0d8e4]' => $selectedTravelClassId != $class['id'],
                    ])>
                    <input type="radio" wire:model.live="selectedTravelClassId" value="{{ $class['id'] }}" class="sr-only">
                    <div class="flex flex-1 flex-col">
                        <span class="font-bold text-tv-text">{{ $class['name'] }}</span>
                        <span class="text-xs text-tv-muted mt-0.5">{{ $class['available_seats'] }} seats available</span>
                        <span class="text-xl font-extrabold text-tv-accent mt-2">Rp
                            {{ number_format($class['price'], 0, ',', '.') }}</span>
                        <span class="text-[10px] text-[#a0aec0]">/person</span>
                    </div>
                    @if($selectedTravelClassId == $class['id'])
                        <div class="absolute top-3 right-3 w-6 h-6 rounded-full bg-tv-primary flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                </label>
            @endforeach
        </div>
    </div>

    @if($selectedTravelClassId && !empty($seatMap))
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">
            {{-- Seat Map --}}
            <div class="lg:col-span-3 tv-card p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                    <h2 class="font-bold text-tv-text">Choose Your Seats</h2>
                    <div class="flex items-center gap-4 text-xs">
                        <div class="flex items-center gap-1.5">
                            <div class="w-6 h-6 bg-emerald-50 border-2 border-emerald-400 rounded-lg"></div><span
                                class="text-tv-muted">Open</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-6 h-6 bg-gray-200 border-2 border-gray-300 rounded-lg"></div><span
                                class="text-tv-muted">Taken</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-6 h-6 bg-blue-100 border-2 border-tv-primary rounded-lg"></div><span
                                class="text-tv-muted">Yours</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-6 h-6 bg-amber-50 border-2 border-amber-400 rounded-lg"></div><span
                                class="text-tv-muted">Exit</span>
                        </div>
                    </div>
                </div>

                {{-- Aircraft Nose --}}
                <div class="text-center mb-4">
                    <svg class="h-10 w-32 mx-auto" viewBox="0 0 100 20" fill="none">
                        <path d="M 10,20 L 50,2 L 90,20" stroke="var(--color-tv-primary)" stroke-width="2" fill="#eff6ff" />
                    </svg>
                    <p class="text-[10px] font-medium text-[#a0aec0] mt-1 uppercase tracking-wider">Front</p>
                </div>

                {{-- Seat Grid --}}
                <div class="space-y-1.5">
                    @foreach($seatMap as $rowNumber => $row)
                        <div class="flex items-center justify-center gap-1.5">
                            <div class="w-7 text-center text-xs font-bold text-[#a0aec0]">{{ $rowNumber }}</div>
                            <div class="flex gap-1">
                                @foreach($row as $index => $seat)
                                    @if($index == 3)
                                    <div class="w-6"></div>@endif
                                    @php
                                        $isSelected = in_array($seat['id'], $selectedSeats);
                                        $isAvailable = $seat['is_available'];
                                        $isExit = $seat['is_exit_row'];
                                    @endphp
                                    <button 
                                        wire:click="selectSeat({{ $seat['id'] }})" 
                                        @if(!$isAvailable) disabled @endif
                                        @class([
                                            'w-9 h-9 rounded-lg border-2 transition-all text-xs font-bold relative',
                                            'bg-blue-100 border-tv-primary text-tv-primary ring-2 ring-blue-200' => $isSelected,
                                            'bg-gray-200 border-gray-300 text-gray-400 cursor-not-allowed' => !$isAvailable && !$isSelected,
                                            'bg-amber-50 border-amber-400 text-amber-600 hover:ring-2 hover:ring-amber-200' => $isAvailable && $isExit && !$isSelected,
                                            'bg-emerald-50 border-emerald-400 text-emerald-600 hover:ring-2 hover:ring-emerald-200' => $isAvailable && !$isExit && !$isSelected,
                                        ])
                                        title="Seat {{ $seat['number'] }}{{ $isExit ? ' (Exit)' : '' }}">
                                        {{ $seat['column'] }}
                                        @if($seat['extra_price'] > 0)
                                            <span class="absolute -top-1 -right-1 bg-tv-accent text-white text-[8px] rounded-full w-3.5 h-3.5 flex items-center justify-center font-bold">+</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                            <div class="w-7 text-center text-xs font-bold text-[#a0aec0]">{{ $rowNumber }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══ Sidebar ═══ --}}
            <div class="lg:col-span-1 space-y-4">
                {{-- Summary Card --}}
                <div class="tv-card bg-linear-to-br from-tv-primary to-tv-secondary text-white sticky top-20">
                    <div class="p-5">
                        <h3 class="font-bold text-sm mb-4">Your Selection</h3>
                        <div class="space-y-2.5 text-sm">
                            <div class="flex justify-between"><span class="text-white/60">Passengers</span><span
                                    class="font-bold">{{ count($passengers) }}</span></div>
                            <div class="flex justify-between"><span class="text-white/60">Seats Selected</span><span
                                    class="font-bold">{{ count($selectedSeats) }}/{{ count($passengers) }}</span></div>
                            <div class="border-t border-white/20 pt-2.5 mt-2.5">
                                <div class="flex justify-between items-end">
                                    <span class="text-white/60 text-xs">Total</span>
                                    <span class="text-xl font-extrabold">Rp
                                        {{ number_format($pricePerSeat * count($passengers), 0, ',', '.') }}</span>
                                </div>
                                <p class="text-[10px] text-white/40 mt-0.5">Rp
                                    {{ number_format($pricePerSeat, 0, ',', '.') }} × {{ count($passengers) }} pax</p>
                            </div>
                        </div>

                        @if(!empty($selectedSeats))
                            <div class="mt-4 pt-3 border-t border-white/15">
                                <p class="text-[10px] font-semibold text-white/50 mb-2 uppercase">Selected:</p>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($seatMap as $row)
                                        @foreach($row as $seat)
                                            @if(in_array($seat['id'], $selectedSeats))
                                                <span
                                                    class="bg-white text-tv-primary px-2 py-0.5 rounded-md text-xs font-bold">{{ $seat['number'] }}</span>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <button wire:click="confirmSeats" @if(count($selectedSeats) !== count($passengers)) disabled @endif
                            class="w-full mt-5 bg-white text-tv-primary font-bold py-3 rounded-xl text-sm transition-all
                                   hover:bg-blue-50 disabled:bg-white/20 disabled:text-white/40 disabled:cursor-not-allowed">
                            @if(count($selectedSeats) === count($passengers))
                                Continue to Payment
                            @else
                                Select {{ count($passengers) - count($selectedSeats) }} More
                            @endif
                        </button>
                    </div>
                </div>

                {{-- Passenger List --}}
                <div class="tv-card p-5">
                    <h3 class="font-bold text-tv-text text-sm mb-3">Passengers</h3>
                    <div class="space-y-2">
                        @foreach($passengers as $index => $passenger)
                            <div
                                class="flex items-center justify-between p-2.5 rounded-xl {{ isset($selectedSeats[$index]) ? 'bg-emerald-50' : 'bg-gray-50' }}">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-7 h-7 rounded-lg {{ isset($selectedSeats[$index]) ? 'bg-emerald-500' : 'bg-tv-primary' }} flex items-center justify-center text-white font-bold text-xs">
                                        {{ $index + 1 }}
                                    </div>
                                    <span class="text-sm font-medium text-tv-text">{{ $passenger->first_name }}</span>
                                </div>
                                @if(isset($selectedSeats[$index]))
                                    <span class="tv-badge-green text-[10px]">
                                        @foreach($seatMap as $row)@foreach($row as $seat)@if($seat['id'] == $selectedSeats[$index]){{ $seat['number'] }}@endif
                                        @endforeach @endforeach
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($selectedTravelClassId && empty($seatMap))
        <div
            class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-700 px-5 py-4 rounded-xl text-sm font-medium">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <span><strong>No seats available</strong> for this class. Please select a different travel class.</span>
        </div>
    @endif

    @if(!$selectedTravelClassId)
        <div
            class="flex items-center gap-3 bg-blue-50 border border-blue-200 text-tv-primary px-5 py-4 rounded-xl text-sm font-medium">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd" />
            </svg>
            Select a travel class above to see available seats.
        </div>
    @endif
</div>