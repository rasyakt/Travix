<div>
    <form wire:submit.prevent="createBooking">
        {{-- Contact Information --}}
        <div class="bg-white rounded-2xl shadow-xl border border-tv-border p-8 mb-8">
            <h2 class="text-xl font-black text-tv-secondary mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informasi Kontak
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-tv-text mb-2">Nama Lengkap *</label>
                    <input type="text" wire:model="contactName" 
                        class="w-full px-4 py-3 border border-tv-border rounded-xl focus:ring-2 focus:ring-tv-primary/20 focus:border-tv-primary transition-all"
                        placeholder="John Doe">
                    @error('contactName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-tv-text mb-2">Email *</label>
                    <input type="email" wire:model="contactEmail" 
                        class="w-full px-4 py-3 border border-tv-border rounded-xl focus:ring-2 focus:ring-tv-primary/20 focus:border-tv-primary transition-all"
                        placeholder="john@example.com">
                    @error('contactEmail') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-tv-text mb-2">Nomor Telepon *</label>
                    <input type="tel" wire:model="contactPhone" 
                        class="w-full px-4 py-3 border border-tv-border rounded-xl focus:ring-2 focus:ring-tv-primary/20 focus:border-tv-primary transition-all"
                        placeholder="+62 812 3456 7890">
                    @error('contactPhone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-4 p-4 bg-blue-50 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-tv-primary shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-tv-text">Konfirmasi booking dan e-ticket akan dikirim ke email ini.</p>
            </div>
        </div>

        {{-- Passenger Information --}}
        @foreach($passengers as $index => $passenger)
            <div class="bg-white rounded-2xl shadow-xl border border-tv-border p-8 mb-8">
                <h2 class="text-xl font-black text-tv-secondary mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-tv-primary text-white flex items-center justify-center text-sm font-black">
                        {{ $index + 1 }}
                    </div>
                    Data Penumpang {{ $index + 1 }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-tv-text mb-2">Gelar *</label>
                        <select wire:model="passengers.{{ $index }}.title" 
                            class="w-full px-4 py-3 border border-tv-border rounded-xl focus:ring-2 focus:ring-tv-primary/20 focus:border-tv-primary transition-all">
                            <option value="Mr">Mr (Tuan)</option>
                            <option value="Mrs">Mrs (Nyonya)</option>
                            <option value="Ms">Ms (Nona)</option>
                            <option value="Miss">Miss (Nona)</option>
                        </select>
                        @error('passengers.'.$index.'.title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-tv-text mb-2">Kewarganegaraan *</label>
                        <input type="text" wire:model="passengers.{{ $index }}.nationality" 
                            class="w-full px-4 py-3 border border-tv-border rounded-xl focus:ring-2 focus:ring-tv-primary/20 focus:border-tv-primary transition-all"
                            placeholder="Indonesia">
                        @error('passengers.'.$index.'.nationality') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-tv-text mb-2">Nama Depan *</label>
                        <input type="text" wire:model="passengers.{{ $index }}.first_name" 
                            class="w-full px-4 py-3 border border-tv-border rounded-xl focus:ring-2 focus:ring-tv-primary/20 focus:border-tv-primary transition-all"
                            placeholder="John">
                        @error('passengers.'.$index.'.first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-tv-text mb-2">Nama Belakang *</label>
                        <input type="text" wire:model="passengers.{{ $index }}.last_name" 
                            class="w-full px-4 py-3 border border-tv-border rounded-xl focus:ring-2 focus:ring-tv-primary/20 focus:border-tv-primary transition-all"
                            placeholder="Doe">
                        @error('passengers.'.$index.'.last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-tv-text mb-2">Tanggal Lahir *</label>
                        <input type="date" wire:model="passengers.{{ $index }}.date_of_birth" 
                            class="w-full px-4 py-3 border border-tv-border rounded-xl focus:ring-2 focus:ring-tv-primary/20 focus:border-tv-primary transition-all"
                            max="{{ date('Y-m-d') }}">
                        @error('passengers.'.$index.'.date_of_birth') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-tv-text mb-2">Nomor Paspor (Opsional)</label>
                        <input type="text" wire:model="passengers.{{ $index }}.passport_number" 
                            class="w-full px-4 py-3 border border-tv-border rounded-xl focus:ring-2 focus:ring-tv-primary/20 focus:border-tv-primary transition-all"
                            placeholder="A12345678">
                        @error('passengers.'.$index.'.passport_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4 p-4 bg-amber-50 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-sm text-amber-800">Pastikan nama sesuai dengan identitas/paspor yang akan digunakan saat perjalanan.</p>
                </div>
            </div>
        @endforeach

        {{-- Price Summary --}}
        <div class="bg-white rounded-2xl shadow-xl border border-tv-border p-8 mb-8">
            <h2 class="text-xl font-black text-tv-secondary mb-6">Ringkasan Pembayaran</h2>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-tv-border">
                    <span class="text-tv-text font-bold">{{ $flightData['airline'] ?? 'Airline' }} - {{ $flightData['flight_number'] ?? '' }}</span>
                    <span class="text-tv-text font-black">{{ $flightData['origin'] ?? '' }} → {{ $flightData['destination'] ?? '' }}</span>
                </div>
                
                <div class="flex items-center justify-between py-3 border-b border-tv-border">
                    <span class="text-tv-text font-bold">Harga per penumpang</span>
                    <span class="text-tv-text font-black">Rp {{ number_format($flightData['price'] ?? 0, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex items-center justify-between py-3 border-b border-tv-border">
                    <span class="text-tv-text font-bold">Jumlah penumpang</span>
                    <span class="text-tv-text font-black">{{ $numberOfPassengers }} orang</span>
                </div>
                
                <div class="flex items-center justify-between py-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl px-6">
                    <span class="text-xl font-black text-tv-secondary">Total Pembayaran</span>
                    <span class="text-3xl font-black text-tv-accent">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Terms and Conditions --}}
        <div class="bg-white rounded-2xl shadow-xl border border-tv-border p-8 mb-8">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" required class="w-5 h-5 rounded border-tv-border text-tv-primary focus:ring-tv-primary/20 mt-0.5">
                <span class="text-sm text-tv-text">
                    Saya menyetujui <a href="#" class="text-tv-primary font-bold hover:underline">syarat dan ketentuan</a> yang berlaku dan memahami bahwa penerbangan ini disediakan oleh partner API kami.
                </span>
            </label>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('flights.index', $searchParams) }}" 
                class="flex-1 bg-gray-100 hover:bg-gray-200 text-tv-secondary px-8 py-4 rounded-xl font-black text-center transition-all border border-tv-border">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            
            <button type="submit" wire:loading.attr="disabled"
                class="flex-1 bg-tv-primary hover:bg-tv-primary/90 text-white px-8 py-4 rounded-xl font-black text-center transition-all shadow-lg shadow-tv-primary/20 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="createBooking">
                    Lanjutkan ke Pembayaran
                    <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </span>
                <span wire:loading wire:target="createBooking">
                    <svg class="animate-spin h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </div>

        {{-- Session Messages --}}
        @if(session()->has('error'))
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-red-800 text-sm font-bold">{{ session('error') }}</p>
            </div>
        @endif
    </form>
</div>
