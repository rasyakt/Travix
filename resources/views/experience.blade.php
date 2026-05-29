@extends('layouts.app')

@section('title', 'Flight Experience')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
        {{-- Header Section --}}
        <div class="text-center max-w-3xl mx-auto mb-16 animate-in fade-in slide-in-from-top-4 duration-500">
            <span class="tv-badge-blue mb-4">IN-FLIGHT COMFORT</span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-tv-secondary tracking-tight leading-tight">
                The Travix Cabin Experience
            </h1>
            <p class="tv-section-subtitle text-base md:text-lg text-tv-muted mt-4 max-w-2xl mx-auto leading-relaxed">
                We've redesigned every aspect of the passenger journey to deliver absolute comfort, culinary delight, and seamless connectivity.
            </p>
        </div>

        {{-- Interactive Cabin Class Showcase (Alpine.js Tabs) --}}
        <div x-data="{ tab: 'business' }" class="mb-20">
            {{-- Tabs Control Row --}}
            <div class="flex flex-wrap items-center justify-center gap-2 mb-10 border-b border-tv-border pb-4">
                <button type="button" @click="tab = 'economy'"
                    :class="tab === 'economy' ? 'text-tv-primary border-b-2 border-tv-primary font-extrabold scale-105' : 'text-tv-muted hover:text-tv-text font-bold'"
                    class="px-6 py-3.5 text-xs md:text-sm uppercase tracking-widest transition-all duration-200 cursor-pointer">
                    Economy Class
                </button>
                <button type="button" @click="tab = 'premium'"
                    :class="tab === 'premium' ? 'text-tv-primary border-b-2 border-tv-primary font-extrabold scale-105' : 'text-tv-muted hover:text-tv-text font-bold'"
                    class="px-6 py-3.5 text-xs md:text-sm uppercase tracking-widest transition-all duration-200 cursor-pointer">
                    Premium Economy
                </button>
                <button type="button" @click="tab = 'business'"
                    :class="tab === 'business' ? 'text-tv-primary border-b-2 border-tv-primary font-extrabold scale-105' : 'text-tv-muted hover:text-tv-text font-bold'"
                    class="px-6 py-3.5 text-xs md:text-sm uppercase tracking-widest transition-all duration-200 cursor-pointer">
                    Business Class
                </button>
                <button type="button" @click="tab = 'first'"
                    :class="tab === 'first' ? 'text-tv-primary border-b-2 border-tv-primary font-extrabold scale-105' : 'text-tv-muted hover:text-tv-text font-bold'"
                    class="px-6 py-3.5 text-xs md:text-sm uppercase tracking-widest transition-all duration-200 cursor-pointer">
                    First Class
                </button>
            </div>

            {{-- Tabs Content Cards --}}
            <div class="relative bg-white rounded-3xl border border-tv-border shadow-xl p-6 md:p-10 min-h-[450px]">
                
                {{-- ECONOMY CLASS --}}
                <div x-show="tab === 'economy'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <span class="tv-badge-blue mb-4">RELIABLE & COMFORTABLE</span>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-tv-secondary mb-4">Modern Comfort for Everyone</h2>
                        <p class="text-tv-muted leading-relaxed mb-6 font-medium">
                            Experience comfort and great value on every short and long-haul journey. With ergonomically designed seats, adjustable headrests, and a complimentary selection of warm meals and cold beverages, your flight will fly by.
                        </p>
                        <div class="grid grid-cols-2 gap-4 border-t border-tv-border pt-6">
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Seat Pitch</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">31 inches (78 cm)</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Seat Recline</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">5 inches (12 cm)</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Luggage Allowance</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">20 kg Checked</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Connectivity</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">USB Power Port</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-linear-to-br from-blue-50 to-indigo-100/40 rounded-2xl p-6 md:p-8 flex flex-col justify-center min-h-[300px]">
                        <h3 class="font-extrabold text-tv-secondary mb-4 text-base uppercase tracking-wider">Features Included:</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                9-inch Personal Touchscreen IFE
                            </li>
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Complimentary hot meal and soft drinks
                            </li>
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Pillow & blanket set (for long-haul flights)
                            </li>
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Shared cabin Wi-Fi access (chargeable)
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- PREMIUM ECONOMY --}}
                <div x-show="tab === 'premium'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <span class="tv-badge-orange mb-4">MORE SPACE TO UNWIND</span>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-tv-secondary mb-4">A Truly Enhanced Journey</h2>
                        <p class="text-tv-muted leading-relaxed mb-6 font-medium">
                            Step up to a more spacious and dedicated cabin designed with priority travelers in mind. Enjoy wider seats with leg-rests, priority check-in queues at the airport, and upgraded culinary choices with free-flowing wine and beers.
                        </p>
                        <div class="grid grid-cols-2 gap-4 border-t border-tv-border pt-6">
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Seat Pitch</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">38 inches (96 cm)</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Seat Width</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">19.5 inches (49 cm)</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Checked Bag</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">30 kg Priority Bag</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Airport Boarding</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">Priority Zone 2</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-linear-to-br from-orange-50/50 to-orange-100/20 rounded-2xl p-6 md:p-8 flex flex-col justify-center min-h-[300px]">
                        <h3 class="font-extrabold text-tv-secondary mb-4 text-base uppercase tracking-wider">Features Included:</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Premium Leg-rest and wider seat pitch
                            </li>
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                13.3-inch Full HD screen & noise-canceling headphones
                            </li>
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Dedicated cabin crew and welcome mocktails
                            </li>
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Custom Travel Amenity Kit by L'Occitane
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- BUSINESS CLASS --}}
                <div x-show="tab === 'business'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <span class="tv-badge-blue mb-4">REDEFINING LUXURY TRAVEL</span>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-tv-secondary mb-4">Your Private Sanctuary in the Clouds</h2>
                        <p class="text-tv-muted leading-relaxed mb-6 font-medium">
                            Experience unmatched privacy and space with direct aisle access and lie-flat beds. Savor gourmet dining from our "Dine on Demand" menu, select a complimentary bottle of vintage Champagne, and arrive refreshed after sleeping in fine Egyptian linen.
                        </p>
                        <div class="grid grid-cols-2 gap-4 border-t border-tv-border pt-6">
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Seat Bed Type</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">180° Fully Lie-Flat Bed</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Checked Bag</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">40 kg Extra Priority</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Airport Lounge</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">Free Lounge Access</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Boarding Priority</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">SkyPriority Fast Track</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-linear-to-br from-blue-50/80 to-blue-100/30 rounded-2xl p-6 md:p-8 flex flex-col justify-center min-h-[300px]">
                        <h3 class="font-extrabold text-tv-secondary mb-4 text-base uppercase tracking-wider">Features Included:</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Premium lie-flat seat with massager
                            </li>
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Dine on Demand gourmet menu with multi-course service
                            </li>
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                complimentary High-Speed Wi-Fi (Unlimited)
                            </li>
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Luxury Amenity Kit & Slippers by Bvlgari
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- FIRST CLASS --}}
                <div x-show="tab === 'first'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <span class="tv-badge-orange mb-4">THE GOLD STANDARD</span>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-tv-secondary mb-4">Your Private Suite in the Skies</h2>
                        <p class="text-tv-muted leading-relaxed mb-6 font-medium">
                            The ultimate standard of modern air travel. Relax behind sliding doors in your own private sanctuary, complete with a leather-clad armchair, separate single bed, personal wardrobe, caviar service, and top-shelf whiskey pairing.
                        </p>
                        <div class="grid grid-cols-2 gap-4 border-t border-tv-border pt-6">
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Suite Cabin Setup</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">Private Suite (Sliding Doors)</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Checked Bag</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">50 kg Priority Baggage</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Airport Transfers</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">Private Chauffeur Service</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-tv-muted tracking-widest">Lounge Access</p>
                                <p class="text-lg font-extrabold text-tv-text mt-0.5">VVIP Signature First Lounge</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-linear-to-br from-amber-50 to-amber-100/40 rounded-2xl p-6 md:p-8 flex flex-col justify-center min-h-[300px]">
                        <h3 class="font-extrabold text-tv-secondary mb-4 text-base uppercase tracking-wider">Features Included:</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Private Suite with wardrobe & luxury minibar
                            </li>
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Dom Pérignon Champagne & imperial caviar service
                            </li>
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Premium silk sleepwear & amenity kit by Prada
                            </li>
                            <li class="flex items-center gap-3.5 text-sm font-bold text-tv-text">
                                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">✓</span>
                                Private butler service & fast-track check-in escort
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        {{-- Core In-Flight Amenities Showcase --}}
        <div class="mt-24">
            <div class="text-center mb-16">
                <span class="tv-badge-blue mb-4">THE TRAVIX PROMISE</span>
                <h2 class="text-2xl md:text-4xl font-extrabold text-tv-secondary">
                    Award-Winning Services on Every Flight
                </h2>
                <p class="tv-section-subtitle max-w-2xl mx-auto mt-2">
                    Every detail of our onboard amenities has been crafted to make you feel right at home.
                   <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Gourmet Catering --}}
                <div class="tv-card-hover p-6 flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-orange-50 text-tv-accent flex items-center justify-center mb-5 shrink-0 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 17h18M6 17a6 6 0 0112 0M12 5v2m0 0a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </div>
                    <h3 class="font-extrabold text-tv-secondary text-lg mb-3">Gourmet Catering</h3>
                    <p class="text-xs text-tv-muted leading-relaxed font-medium">
                        World-class culinary recipes designed by master chefs using only fresh, hand-picked regional ingredients.
                    </p>
                </div>

                {{-- In-Flight Wi-Fi --}}
                <div class="tv-card-hover p-6 flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-tv-primary flex items-center justify-center mb-5 shrink-0 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5zM8.25 15a5.303 5.303 0 017.5 0M5.25 12a9.546 9.546 0 0113.5 0M2.25 9A13.784 13.784 0 0121.75 9" />
                        </svg>
                    </div>
                    <h3 class="font-extrabold text-tv-secondary text-lg mb-3">High-Speed Wi-Fi</h3>
                    <p class="text-xs text-tv-muted leading-relaxed font-medium">
                        Stay connected with friends, family, or work using high-speed, reliable satellite cabin Wi-Fi on your device.
                    </p>
                </div>

                {{-- Entertainment --}}
                <div class="tv-card-hover p-6 flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-5 shrink-0 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" />
                        </svg>
                    </div>
                    <h3 class="font-extrabold text-tv-secondary text-lg mb-3">Non-Stop Entertainment</h3>
                    <p class="text-xs text-tv-muted leading-relaxed font-medium">
                        Access over 2,000+ hours of movies, TV shows, interactive games, and curated audio playlists directly at your seat.
                    </p>
                </div>

                {{-- Lounge Comfort --}}
                <div class="tv-card-hover p-6 flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5 shrink-0 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0-1.5-1.5-3-3-3h-9c-1.5 0-3 1.5-3 3v6c0 .75.75 1.5 1.5 1.5h12c.75 0 1.5-.75 1.5-1.5v-6zM4.5 10.5h15M7.5 18v3M16.5 18v3" />
                        </svg>
                    </div>
                    <h3 class="font-extrabold text-tv-secondary text-lg mb-3">Airport Lounges</h3>
                    <p class="text-xs text-tv-muted leading-relaxed font-medium">
                        Step out of the crowded airport and unwind inside our premium lounges featuring gourmet dining and quiet workstations.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
