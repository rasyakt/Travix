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
                            'icon' => '🌴',
                            'tagline' => 'Bali is calling'
                        ],
                        'SIN' => [
                            'image' => 'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Futuristic Hub',
                            'desc' => 'Explore Gardens by the Bay, elite Michelin-starred dining, and luxury airport amenities.',
                            'icon' => '✨',
                            'tagline' => 'Garden City Singapore'
                        ],
                        'CGK' => [
                            'image' => 'https://images.unsplash.com/photo-1585837575652-267c041d77d4?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Dynamic Capital',
                            'desc' => 'Experience the rich heritage, massive skylines, and elite culinary hotspots of Indonesia.',
                            'icon' => '🌆',
                            'tagline' => 'Jakarta Pulse'
                        ],
                        'SUB' => [
                            'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Volcanic Gateway',
                            'desc' => 'Discover the historic City of Heroes and embark on epic hiking gateways to Mount Bromo.',
                            'icon' => '🌋',
                            'tagline' => 'Explore Surabaya'
                        ],
                        'JOG' => [
                            'image' => 'https://images.unsplash.com/photo-1584810359583-96fc3448beaa?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Sultanate Heritage',
                            'desc' => 'Step back in time to the magnificent Prambanan and Borobudur temples, and rich royal batik guilds.',
                            'icon' => '🕌',
                            'tagline' => 'Historic Yogyakarta'
                        ],
                        'UPG' => [
                            'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Coastal Maritime',
                            'desc' => 'Arrive in the major historical port city of Sulawesi, famous for Losari beach sunsets and seafood.',
                            'icon' => '🌅',
                            'tagline' => 'Makassar Gate'
                        ],
                        'KNO' => [
                            'image' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Volcanic Lake Wonders',
                            'desc' => 'Journey from the metropolitan core of Sumatra to the breathtaking volcanic majesty of Lake Toba.',
                            'icon' => '🏞️',
                            'tagline' => 'Medan Adventure'
                        ],
                        'BDO' => [
                            'image' => 'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Highland Retreat',
                            'desc' => 'Enjoy cool mountain breezes, rich heritage Art Deco streets, and sprawling tea plantations.',
                            'icon' => '☕',
                            'tagline' => 'Paris Van Java'
                        ],
                        'PLM' => [
                            'image' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Ancient River Port',
                            'desc' => 'Home of the legendary Ampera Bridge, Sriwijaya empire heritage, and signature Pempek delicacies.',
                            'icon' => '🌉',
                            'tagline' => 'Discover Palembang'
                        ],
                        'BTH' => [
                            'image' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Coastal Oasis',
                            'desc' => 'Relax inside premium beach resorts, fresh seafood harbors, and iconic sea bridge systems.',
                            'icon' => '⛵',
                            'tagline' => 'Batam Gateway'
                        ],
                        'PKU' => [
                            'image' => 'https://images.unsplash.com/photo-1540959733332-eab4deceeaf7?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Cultural Crossroads',
                            'desc' => 'Discover rich Riau-Malay heritage, deep historical mosques, and active river corridors.',
                            'icon' => '🕌',
                            'tagline' => 'Explore Pekanbaru'
                        ],
                        'KUL' => [
                            'image' => 'https://images.unsplash.com/photo-1595438596637-2cf3b593673f?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Metropolitan Fusion',
                            'desc' => 'Gaze at the soaring Petronas Twin Towers, rich culinary street grids, and colonial landmarks.',
                            'icon' => '🗼',
                            'tagline' => 'Kuala Lumpur Pulse'
                        ],
                        'BKK' => [
                            'image' => 'https://images.unsplash.com/photo-1508009603885-50cf7c579365?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Exotic Temples',
                            'desc' => 'Marvel at the golden Wat Arun temple, vibrant floating markets, and legendary Thai cuisine.',
                            'icon' => '🕌',
                            'tagline' => 'Amazing Bangkok'
                        ],
                        'HKG' => [
                            'image' => 'https://images.unsplash.com/photo-1506318137071-a8e063b4bec0?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Metropolitan Peak',
                            'desc' => 'Enjoy breathtaking skyscraper views from Victoria Peak and elite duty-free shopping hubs.',
                            'icon' => '🌆',
                            'tagline' => 'Pearl of the Orient'
                        ],
                        'DXB' => [
                            'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Global Oasis',
                            'desc' => 'Gaze at the towering Burj Khalifa, luxury desert safari camps, and signature palm islands.',
                            'icon' => '🐪',
                            'tagline' => 'Luxurious Dubai'
                        ],
                        default => [
                            'image' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=600&q=80',
                            'badge' => 'Global Getaway',
                            'desc' => 'Travel safely with direct routes, premium inflight dining, and high-speed Wi-Fi.',
                            'icon' => '✈️',
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
                        <div class="flex items-center justify-between z-10">
                            <span class="bg-white/20 backdrop-blur-md text-[10px] font-black tracking-widest uppercase px-3 py-1.5 rounded-lg border border-white/25">
                                {{ $cardTheme['badge'] }}
                            </span>
                            <span class="text-2xl drop-shadow-md">{{ $cardTheme['icon'] }}</span>
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
