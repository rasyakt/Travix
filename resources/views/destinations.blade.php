@extends('layouts.app')

@section('title', 'Destinations')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
        {{-- Header Section --}}
        <div class="text-center max-w-3xl mx-auto mb-16 animate-in fade-in slide-in-from-top-4 duration-500">
            <span class="tv-badge-blue mb-4">INSPIRATION</span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-tv-secondary tracking-tight leading-tight">
                Explore Our Destinations
            </h1>
            <p class="tv-section-subtitle text-base md:text-lg text-tv-muted mt-4 max-w-2xl mx-auto leading-relaxed">
                From pristine beaches to vibrant metropolitan centers, discover your next adventure with our direct, premium flights.
            </p>
        </div>

        {{-- Destinations Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($airports as $airport)
                @php
                    // Assign beautiful themed backgrounds & descriptors based on popular destination codes
                    $cardTheme = match($airport->iata_code) {
                        'DPS' => [
                            'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Tropical Paradise',
                            'desc' => 'Immerse yourself in legendary beaches, serene Hindu sea temples, and rich artistic culture.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />',
                            'tagline' => 'Bali is calling'
                        ],
                        'SIN' => [
                            'image' => 'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Futuristic Hub',
                            'desc' => 'Explore Gardens by the Bay, elite Michelin-starred dining, and luxury airport amenities.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l-.813-5.096L3 15l5.096-.813L9 9l.813 5.187L15 15l-5.187.813z M19.071 4.929l-.5 3.5-3.5.5 3.5.5.5 3.5.5-3.5 3.5-.5-3.5-.5-.5-3.5z" />',
                            'tagline' => 'Garden City Singapore'
                        ],
                        'CGK' => [
                            'image' => 'https://images.unsplash.com/photo-1585837575652-267c041d77d4?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Dynamic Capital',
                            'desc' => 'Experience the rich heritage, massive skylines, and elite culinary hotspots of Indonesia.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />',
                            'tagline' => 'Jakarta Pulse'
                        ],
                        'SUB' => [
                            'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Volcanic Gateway',
                            'desc' => 'Discover the historic City of Heroes and embark on epic hiking gateways to Mount Bromo.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l7.5-12 7.5 12m-15 0h15M12 7.5L8.25 13.5h7.5L12 7.5z" />',
                            'tagline' => 'Explore Surabaya'
                        ],
                        'JOG' => [
                            'image' => 'https://images.unsplash.com/photo-1584810359583-96fc3448beaa?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Sultanate Heritage',
                            'desc' => 'Step back in time to the magnificent Prambanan and Borobudur temples, and rich royal batik guilds.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2 7l3 11h14l3-11-5 4-3-6-3 6-5-4z M5 18h14v2H5v-2z" />',
                            'tagline' => 'Historic Yogyakarta'
                        ],
                        'UPG' => [
                            'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Coastal Maritime',
                            'desc' => 'Arrive in the major historical port city of Sulawesi, famous for Losari beach sunsets and seafood.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z M12 8l2 4-4 2 2-6z" />',
                            'tagline' => 'Makassar Gate'
                        ],
                        'KNO' => [
                            'image' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Volcanic Lake Wonders',
                            'desc' => 'Journey from the metropolitan core of Sumatra to the breathtaking volcanic majesty of Lake Toba.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503-3.485C17.766 12.437 19.5 13.982 19.5 16.5V6.75C19.5 4.907 17.077 3 15 3c-1.405 0-2.532.744-3.75 1.25L7.5 3c-2.077 0-4.5 1.907-4.5 3.75v9.75c0 1.622 1.34 2.85 3 2.85 1.4 0 2.55-.733 3.75-1.25l3.75 1.25c1.405 0 2.532-.744 3.75-1.25z" />',
                            'tagline' => 'Medan Adventure'
                        ],
                        'BDO' => [
                            'image' => 'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Highland Retreat',
                            'desc' => 'Enjoy cool mountain breezes, rich heritage Art Deco streets, and sprawling tea plantations.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 10h10v6a4 4 0 01-4 4H10a4 4 0 01-4-4v-6zm10 2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2v-6z M4 6h16" />',
                            'tagline' => 'Paris Van Java'
                        ],
                        'PLM' => [
                            'image' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Ancient River Port',
                            'desc' => 'Home of the legendary Ampera Bridge, Sriwijaya empire heritage, and signature Pempek delicacies.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 7v11 M8 10h8 M12 3a2 2 0 100 4 2 2 0 000-4z M5 14c0 3.866 3.134 7 7 7s7-3.134 7-7" />',
                            'tagline' => 'Discover Palembang'
                        ],
                        'BTH' => [
                            'image' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Coastal Oasis',
                            'desc' => 'Relax inside premium beach resorts, fresh seafood harbors, and iconic sea bridge systems.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 2v14M4 16h16l-2 4H6L4 16z M12 2L5 14h7V2z M12 2l6 12h-6V2z" />',
                            'tagline' => 'Batam Gateway'
                        ],
                        'PKU' => [
                            'image' => 'https://images.unsplash.com/photo-1540959733332-eab4deceeaf7?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Cultural Crossroads',
                            'desc' => 'Discover rich Riau-Malay heritage, deep historical mosques, and active river corridors.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z M12 3v18 M3 12h18 M12 3c3 3.5 3 14.5 0 18 M12 3c-3 3.5-3 14.5 0 18" />',
                            'tagline' => 'Explore Pekanbaru'
                        ],
                        'KUL' => [
                            'image' => 'https://images.unsplash.com/photo-1595438596637-2cf3b593673f?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Metropolitan Fusion',
                            'desc' => 'Gaze at the soaring Petronas Twin Towers, rich culinary street grids, and colonial landmarks.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />',
                            'tagline' => 'Kuala Lumpur Pulse'
                        ],
                        'BKK' => [
                            'image' => 'https://images.unsplash.com/photo-1508009603885-50cf7c579365?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Exotic Temples',
                            'desc' => 'Marvel at the golden Wat Arun temple, vibrant floating markets, and legendary Thai cuisine.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3L2 8h20L12 3zm-9 9h18v8H3v-8zm3 0v8m4-8v8m4-8v8m4-8v8" />',
                            'tagline' => 'Amazing Bangkok'
                        ],
                        'HKG' => [
                            'image' => 'https://images.unsplash.com/photo-1506318137071-a8e063b4bec0?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Metropolitan Peak',
                            'desc' => 'Enjoy breathtaking skyscraper views from Victoria Peak and elite duty-free shopping hubs.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 7.5l4.5 6L18 6l3.75 7.5M2 20h20" />',
                            'tagline' => 'Pearl of the Orient'
                        ],
                        'DXB' => [
                            'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Global Oasis',
                            'desc' => 'Gaze at the towering Burj Khalifa, luxury desert safari camps, and signature palm islands.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1M4 12H3m18 0h-1M18.36 5.64l-.7.7M6.34 17.66l-.7.7m0-12.72l.7.7m11.32 11.32l.7-.7 M2 16c3-1.5 7-1.5 10 0s7 1.5 10 0" />',
                            'tagline' => 'Luxurious Dubai'
                        ],
                        default => [
                            'image' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Global Getaway',
                            'desc' => 'Travel safely with direct routes, premium inflight dining, and high-speed Wi-Fi.',
                            'icon_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />',
                            'tagline' => 'Direct Travel Hub'
                        ]
                    };
                @endphp

                <div class="tv-card-hover overflow-hidden flex flex-col min-h-[440px] bg-white group/dest">
                    {{-- Visual Header Panel with Real City Photo --}}
                    <div class="h-48 shrink-0 relative p-6 flex flex-col justify-between text-white overflow-hidden bg-gray-900">
                        {{-- Background Image --}}
                        <img src="{{ $cardTheme['image'] }}" alt="{{ $airport->city }}"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=600&q=80';"
                             class="absolute inset-0 w-full h-full object-cover opacity-75 group-hover/dest:scale-110 transition-transform duration-700 ease-out z-0">
                        {{-- Deep premium linear gradient overlay to ensure perfect text readability --}}
                        <div class="absolute inset-0 bg-linear-to-t from-black/85 via-black/40 to-black/20 z-0"></div>

                        {{-- Content elements --}}
                        <div class="flex items-center justify-between z-10 w-full">
                            <span class="bg-black/35 backdrop-blur-md text-[9px] font-bold tracking-widest text-white/95 uppercase px-2.5 py-1 rounded-md border border-white/10 shadow-sm">
                                {{ $cardTheme['badge'] }}
                            </span>
                            <div class="w-8 h-8 rounded-full bg-white/10 backdrop-blur-md border border-white/15 flex items-center justify-center text-white shrink-0 shadow-sm transition-transform duration-300 group-hover/dest:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    {!! $cardTheme['icon_path'] !!}
                                </svg>
                            </div>
                        </div>

                        <div class="z-10">
                            <p class="text-[10px] font-black text-white/90 uppercase tracking-widest drop-shadow-sm">{{ $cardTheme['tagline'] }}</p>
                            <h3 class="text-2xl font-black mt-0.5 leading-tight drop-shadow-md">{{ $airport->city }}</h3>
                        </div>
                    </div>

                    {{-- Body Details --}}
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4 border-b border-tv-border pb-3">
                                <div>
                                    <p class="text-[10px] font-black text-tv-muted uppercase tracking-widest">Airport Name</p>
                                    <h4 class="font-extrabold text-tv-secondary text-sm truncate max-w-[200px]" title="{{ $airport->name }}">{{ $airport->name }}</h4>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black text-tv-muted uppercase tracking-widest">IATA Code</p>
                                    <span class="inline-block text-xs font-black text-tv-primary bg-blue-50 px-2 py-0.5 rounded uppercase mt-0.5">
                                        {{ $airport->iata_code }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-xs text-tv-muted leading-relaxed font-medium mb-6">
                                {{ $cardTheme['desc'] }}
                            </p>
                        </div>

                        {{-- Convert Discover to Transaction --}}
                        @php
                            // Set a realistic pre-filled flight search origin
                            // If this card is already Jakarta (CGK), search to Denpasar (DPS) to avoid self-routing error, otherwise search from CGK to this airport.
                            $origin = ($airport->iata_code === 'CGK') ? 'DPS' : 'CGK';
                            $originCity = ($airport->iata_code === 'CGK') ? 'Denpasar' : 'Jakarta';
                        @endphp
                        <div>
                            <a href="{{ route('flights.index', ['origin' => $origin, 'destination' => $airport->iata_code]) }}"
                                class="w-full btn-tv-primary text-xs py-3 font-black text-center flex items-center justify-center gap-2 group/btn shadow-md hover:shadow-lg transition-all hover:scale-[1.01]">
                                Cari Penerbangan
                                <span class="text-[10px] font-black opacity-80 uppercase tracking-wider">
                                    (Dari {{ $origin }})
                                </span>
                                <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Business stats bottom banner --}}
        <div class="mt-24 bg-linear-to-br from-tv-secondary to-[#041c3a] rounded-3xl p-8 md:p-12 text-white relative overflow-hidden shadow-2xl">
            <div class="absolute -right-20 -bottom-20 opacity-5">
                <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div>
                    <span class="tv-badge bg-white/10 text-white border border-white/20 mb-4">TRAVIX CONNECTS THE WORLD</span>
                    <h2 class="text-2xl md:text-4xl font-extrabold leading-tight">Fly Premium. Arrive Refreshed.</h2>
                    <p class="text-white/70 text-sm mt-4 leading-relaxed font-medium max-w-lg">
                        Our route networks are carefully optimized to offer fast layovers, premium airport lounge access, and state-of-the-art aircraft.
                    </p>
                </div>
                <div class="grid grid-cols-3 gap-4 text-center border-t lg:border-t-0 lg:border-l border-white/10 pt-8 lg:pt-0 lg:pl-10">
                    <div>
                        <p class="text-2xl md:text-4xl font-black text-tv-primary">100%</p>
                        <p class="text-[10px] font-bold text-white/50 uppercase tracking-wider mt-1">Direct Routes</p>
                    </div>
                    <div>
                        <p class="text-2xl md:text-4xl font-black text-tv-primary">4+</p>
                        <p class="text-[10px] font-bold text-white/50 uppercase tracking-wider mt-1">Key Hubs</p>
                    </div>
                    <div>
                        <p class="text-2xl md:text-4xl font-black text-tv-primary">1M+</p>
                        <p class="text-[10px] font-bold text-white/50 uppercase tracking-wider mt-1">Happy Guests</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
