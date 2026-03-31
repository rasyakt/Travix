<div>
    @if (session()->has('success'))
        <div class="mb-5 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ═══ Flight Summary ═══ --}}
    <div class="tv-card p-5 mb-5">
        <div class="flex items-center gap-4 mb-4">
            @if($flight->schedule->airline->logo_url ?? null)
                <img src="{{ $flight->schedule->airline->logo_url }}" alt=""
                    class="h-9 w-9 rounded-lg object-contain bg-gray-50 p-1">
            @endif
            <div>
                <p class="font-bold text-tv-text text-sm">{{ $flight->flight_number }}</p>
                <p class="text-xs text-tv-muted">
                    {{ $flight->schedule->airline->name ?? $flight->airline->name ?? 'Airline' }}
                </p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4 items-center">
            <div>
                <p class="text-lg font-extrabold text-tv-text">
                    {{ ($flight->schedule->originAirport->iata_code ?? $flight->originAirport->iata_code ?? '—') }}
                </p>
                <p class="text-[10px] text-[#a0aec0]">
                    {{ ($flight->departure_datetime ?? $flight->departure_time)?->format('M d, H:i') }}
                </p>
            </div>
            <div class="flex items-center justify-center">
                <div class="w-2 h-2 rounded-full border-2 border-tv-primary"></div>
                <div class="h-px bg-tv-border flex-1"></div>
                <svg class="w-4 h-4 text-tv-primary mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                </svg>
                <div class="h-px bg-tv-border flex-1"></div>
                <div class="w-2 h-2 rounded-full border-2 border-tv-accent"></div>
            </div>
            <div class="text-right">
                <p class="text-lg font-extrabold text-tv-text">
                    {{ ($flight->schedule->destinationAirport->iata_code ?? $flight->destinationAirport->iata_code ?? '—') }}
                </p>
                <p class="text-[10px] text-[#a0aec0]">
                    {{ ($flight->arrival_datetime ?? $flight->arrival_time)?->format('M d, H:i') }}
                </p>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="createBooking" class="space-y-5">
        {{-- ═══ Travel Class ═══ --}}
        <div class="tv-card p-5">
            <h2 class="font-bold text-tv-text mb-4">Travel Class</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach($travelClasses as $class)
                    <label
                        class="relative flex cursor-pointer rounded-xl border-2 p-4 transition-all
                                        {{ $selectedClassId == $class['id'] ? 'border-tv-primary bg-blue-50/50' : 'border-tv-border hover:border-[#d0d8e4]' }}">
                        <input type="radio" wire:model.live="selectedClassId" value="{{ $class['id'] }}" class="sr-only">
                        <div class="flex flex-1 flex-col">
                            <span class="font-bold text-tv-text text-sm">{{ $class['name'] }}</span>
                            <span class="text-xs text-tv-muted mt-0.5">{{ $class['available_seats'] }} available</span>
                            <span class="text-lg font-extrabold text-tv-accent mt-2">Rp
                                {{ number_format($class['price'], 0, ',', '.') }}</span>
                        </div>
                        @if($selectedClassId == $class['id'])
                            <div
                                class="absolute top-3 right-3 w-5 h-5 rounded-full bg-tv-primary flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                    </label>
                @endforeach
            </div>
            @error('selectedClassId') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span>
            @enderror
        </div>

        {{-- ═══ Passengers ═══ --}}
        <div class="tv-card p-5">
            <h2 class="font-bold text-tv-text mb-4">Passenger Information</h2>
            @foreach($passengers as $index => $passenger)
                <div class="mb-5 pb-5 {{ $index < count($passengers) - 1 ? 'border-b border-tv-border' : '' }}">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div
                            class="w-7 h-7 rounded-lg bg-tv-primary flex items-center justify-center text-white text-xs font-bold">
                            {{ $index + 1 }}
                        </div>
                        <h3 class="font-bold text-tv-text text-sm">Passenger {{ $index + 1 }}</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="tv-label">First Name</label>
                            <input type="text" wire:model="passengers.{{ $index }}.first_name" class="tv-input"
                                placeholder="John">
                            @error("passengers.{$index}.first_name") <span
                            class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="tv-label">Last Name</label>
                            <input type="text" wire:model="passengers.{{ $index }}.last_name" class="tv-input"
                                placeholder="Doe">
                            @error("passengers.{$index}.last_name") <span
                            class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="tv-label">Date of Birth</label>
                            <input type="date" wire:model="passengers.{{ $index }}.date_of_birth" class="tv-input">
                            @error("passengers.{$index}.date_of_birth") <span
                            class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="tv-label">Passport <span class="text-[#a0aec0]">(Optional)</span></label>
                            <input type="text" wire:model="passengers.{{ $index }}.passport_number" class="tv-input"
                                placeholder="A12345678">
                            @error("passengers.{$index}.passport_number") <span
                            class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="tv-label">Nationality</label>
                            <input type="text" wire:model="passengers.{{ $index }}.nationality" class="tv-input"
                                placeholder="Indonesian">
                            @error("passengers.{$index}.nationality") <span
                            class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ═══ Contact ═══ --}}
        <div class="tv-card p-5">
            <h2 class="font-bold text-tv-text mb-4">Contact Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="tv-label">Full Name</label>
                    <input type="text" wire:model="contactName" class="tv-input" placeholder="Your Full Name">
                    @error('contactName') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="tv-label">Email Address</label>
                    <input type="email" wire:model="contactEmail" class="tv-input" placeholder="you@example.com">
                    @error('contactEmail') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="tv-label">Phone Number</label>
                    <input type="tel" wire:model="contactPhone" class="tv-input" placeholder="+62 812 3456 7890">
                    @error('contactPhone') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ═══ Total & Submit ═══ --}}
        <div class="tv-card bg-linear-to-r from-blue-50 to-white p-5">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-medium text-tv-muted">Total Price</p>
                    <p class="text-[10px] text-[#a0aec0]">{{ $numberOfPassengers }}
                        {{ \Illuminate\Support\Str::plural('passenger', $numberOfPassengers) }}
                    </p>
                </div>
                <p class="text-3xl font-extrabold text-tv-accent">Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
            </div>
        </div>

        @guest
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-amber-900">Login masih diperlukan untuk pembayaran akhir</p>
                        <p class="mt-1 text-xs leading-5 text-amber-800">
                            Anda tetap bisa membuat booking sebagai tamu. Setelah klik Continue to Payment,
                            Anda akan masuk ke halaman pembayaran, tetapi harus login untuk menyelesaikan pembayaran.
                        </p>
                        <div class="mt-3 flex flex-wrap items-center gap-3 text-xs">
                            <a href="{{ route('login') }}" class="font-bold text-tv-primary hover:underline">Login sekarang</a>
                            <span class="text-amber-700/80">atau lanjut sebagai tamu dulu lalu login saat pembayaran</span>
                        </div>
                    </div>
                </div>
            </div>
        @endguest

        <div class="flex flex-wrap items-center justify-end gap-3">
            <a href="{{ route('flights.index') }}" class="btn-tv-ghost text-sm">Cancel</a>
            <button type="submit" class="btn-tv-accent py-3 px-8 text-sm">
                @auth
                    Continue to Seat Selection
                @else
                    Continue to Seat Selection
                @endauth
            </button>
        </div>
        @guest
            <p class="text-right text-[11px] font-medium text-tv-muted">
                Setelah pilih kursi, Anda bisa lanjut ke pembayaran. Login tetap dibutuhkan untuk pembayaran final.
            </p>
        @endguest
    </form>
</div>