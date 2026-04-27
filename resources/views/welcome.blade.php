@extends('layouts.app')

@section('title', 'Fly Smarter with Travix')

@section('content')
    {{-- ═══ Premium Hero Section ═══ --}}
    <div class="relative min-h-screen flex items-center justify-center pt-16 pb-12">
        {{-- Hero Background --}}
        <div class="absolute inset-0 z-0 overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-[10s] hover:scale-110" 
                 style="background-image: url('https://images.unsplash.com/photo-1633321088355-d0f81134ca3b?q=80&w=2070&auto=format&fit=crop');">
            </div>
            {{-- Aesthetic Overlay --}}
            <div class="absolute inset-0 bg-linear-to-b from-tv-secondary/70 via-tv-secondary/20 to-tv-secondary/90"></div>
            {{-- Decorative Light --}}
            <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-tv-primary/10 rounded-full blur-[120px] -translate-y-1/2"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <!-- <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-4 py-1.5 mb-8 animate-fade-in">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-tv-accent opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-tv-accent"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-widest text-white/90">Premium Airline Partner</span>
            </div> -->
            
            <h1 class="text-5xl md:text-7xl font-black mb-4 tracking-tighter leading-[0.9] drop-shadow-2xl">
                Elevate Your <br> 
                <span class="text-transparent bg-clip-text bg-linear-to-r from-tv-primary via-blue-400 to-tv-accent">Standard</span>
            </h1>
            <p class="text-base md:text-lg font-medium max-w-xl mx-auto text-white/70 mb-6 leading-relaxed drop-shadow-md">
                Experience air travel as it was meant to be. Simple, luxury, and transparent booking for the discerning traveler.
            </p>

            {{-- ═══ Search Widget ═══ --}}
            <div x-data="{ activeTab: 'flights' }" class="max-w-5xl mx-auto">
                {{-- Tabs --}}
                <div class="flex items-center gap-0 mb-0 px-0">
                    <button @click="activeTab = 'flights'" 
                        :class="activeTab === 'flights' ? 'bg-white text-tv-secondary' : 'bg-white/5 text-white/60 hover:bg-white/10'"
                        class="px-8 py-4 rounded-t-3xl text-xs font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Flights
                    </button>
                    <button @click="activeTab = 'hotels'" 
                        :class="activeTab === 'hotels' ? 'bg-white text-tv-secondary' : 'bg-white/5 text-white/60 hover:bg-white/10'"
                        class="px-8 py-4 rounded-t-3xl text-xs font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Stays
                    </button>
                    <button @click="activeTab = 'cars'" 
                        :class="activeTab === 'cars' ? 'bg-white text-tv-secondary' : 'bg-white/5 text-white/60 hover:bg-white/10'"
                        class="px-8 py-4 rounded-t-3xl text-xs font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Cars
                    </button>
                </div>

                {{-- Tab Content --}}
                <div :class="activeTab === 'flights' ? 'rounded-tl-none' : 'rounded-tl-[40px]'"
                    class="bg-white rounded-b-[40px] rounded-tr-[40px] p-8 md:p-10 text-tv-secondary border border-gray-100 overflow-visible">
                    {{-- Flight Search Tab --}}
                    <div x-show="activeTab === 'flights'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        @livewire('flight-search', ['minimal' => true])
                    </div>

                    {{-- Stays Tab (Coming Soon) --}}
                    <div x-show="activeTab === 'hotels'" x-cloak class="py-16 flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-blue-50/50 rounded-full flex items-center justify-center mb-8 relative">
                            <svg class="w-10 h-10 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <div class="absolute -top-1 -right-1 bg-tv-accent text-white text-[10px] font-black px-2 py-1 rounded-lg uppercase tracking-tighter">SOON</div>
                        </div>
                        <h3 class="text-3xl font-black mb-3">Premium Stays</h3>
                        <p class="text-tv-muted font-medium max-w-sm">We're auditing the finest hotels to ensure they meet the Travix standard of luxury.</p>
                    </div>

                    {{-- Cars Tab (Coming Soon) --}}
                    <div x-show="activeTab === 'cars'" x-cloak class="py-16 flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-orange-50/50 rounded-full flex items-center justify-center mb-8 relative">
                            <svg class="w-10 h-10 text-tv-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div class="absolute -top-1 -right-1 bg-tv-primary text-white text-[10px] font-black px-2 py-1 rounded-lg uppercase tracking-tighter">SOON</div>
                        </div>
                        <h3 class="text-3xl font-black mb-3">Elite Car Rentals</h3>
                        <p class="text-tv-muted font-medium max-w-sm">From luxury sedans to high-performance SUVs, your ride awaits.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ Why fly with Travix Section ═══ --}}
    <div class="bg-[#fcfdfe] py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <span class="text-xs font-black uppercase tracking-[0.3em] text-tv-primary mb-4 block">Our Manifesto</span>
                <h2 class="text-4xl md:text-6xl font-black text-tv-secondary mb-6 tracking-tight">Why fly with Travix?</h2>
                <div class="w-24 h-1.5 bg-tv-accent mx-auto rounded-full mb-8"></div>
                <p class="text-tv-muted text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">We've reimagined the booking experience to put you in control, with premium support every step of the way.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach([
                    ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Transparent Pricing', 'desc' => 'No hidden fees, ever. The price you see is the price you pay from search to checkout.', 'color' => 'blue'],
                    ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Premium Concierge', 'desc' => '24/7 dedicated support for every traveler. Seat or flight changes handled instantly by humans.', 'color' => 'indigo'],
                    ['icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'title' => 'Seamless Changes', 'desc' => 'Life happens. Change your flight with a single click in our app. Flexible policies for modern travel.', 'color' => 'blue']
                ] as $feature)
                <div class="bg-white p-12 rounded-[40px] border border-gray-100 shadow-[0_10px_40px_-20px_rgba(0,0,0,0.1)] hover:shadow-[0_40px_80px_-40px_rgba(0,0,0,0.15)] transition-all duration-500 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-tv-primary/5 rounded-bl-[100px] -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="w-16 h-16 rounded-2xl bg-{{ $feature['color'] }}-50 flex items-center justify-center mb-10 group-hover:rotate-6 transition-transform">
                        <svg class="w-8 h-8 text-{{ $feature['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="{{ $feature['icon'] }}"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-tv-secondary mb-5 tracking-tight">{{ $feature['title'] }}</h3>
                    <p class="text-tv-muted leading-relaxed font-medium">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══ Trending this season ═══ --}}
    <div class="bg-white py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div>
                    <span class="text-xs font-black uppercase tracking-[0.3em] text-tv-accent mb-4 block">Global Favorites</span>
                    <h2 class="text-4xl md:text-6xl font-black text-tv-secondary tracking-tight">Trending this season</h2>
                </div>
                <a href="{{ route('flights.index') }}" class="inline-flex items-center gap-3 bg-tv-secondary text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-tv-primary transition-all hover:translate-x-1 group">
                    Explore All
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach([
                    ['city' => 'Tokyo', 'country' => 'JAPAN', 'price' => '890', 'image' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?q=80&w=1988&auto=format&fit=crop', 'type' => 'Direct'],
                    ['city' => 'Paris', 'country' => 'FRANCE', 'price' => '650', 'image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?q=80&w=2073&auto=format&fit=crop', 'type' => 'Direct'],
                    ['city' => 'New York', 'country' => 'USA', 'price' => '350', 'image' => 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?q=80&w=2070&auto=format&fit=crop', 'type' => 'Direct'],
                    ['city' => 'Dubai', 'country' => 'UAE', 'price' => '920', 'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?q=80&w=2070&auto=format&fit=crop', 'type' => '1 Stop']
                ] as $dest)
                <div class="group relative h-[500px] rounded-[48px] overflow-hidden cursor-pointer shadow-2xl shadow-black/10">
                    <img src="{{ $dest['image'] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-[1.5s]" alt="{{ $dest['city'] }}">
                    <div class="absolute inset-0 bg-linear-to-t from-tv-secondary via-tv-secondary/20 to-transparent opacity-90 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute bottom-0 left-0 p-10 w-full">
                        <p class="text-[10px] font-black tracking-[0.3em] text-[#ffce5d] mb-2 uppercase">{{ $dest['country'] }}</p>
                        <h3 class="text-3xl font-black text-white mb-6 tracking-tight">{{ $dest['city'] }}</h3>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-xl text-white/90 text-[10px] font-black uppercase tracking-tighter">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                                {{ $dest['type'] }}
                            </div>
                            <div class="text-right">
                                <p class="text-[8px] font-black text-white/40 uppercase tracking-widest mb-1">FROM</p>
                                <p class="text-2xl font-black text-white leading-none">${{ $dest['price'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══ Mobile App Banner ═══ --}}
    <div class="bg-white py-20 pb-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-linear-to-br from-[#1b5bf7] via-tv-primary to-tv-secondary rounded-[60px] p-12 md:p-24 flex flex-col md:flex-row items-center gap-20 relative overflow-hidden shadow-[0_50px_100px_-20px_rgba(27,91,247,0.3)]">
                {{-- Abstract Orbs --}}
                <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-white/5 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-tv-accent/10 rounded-full blur-[80px] translate-y-1/2 -translate-x-1/2"></div>
                
                <div class="flex-1 text-center md:text-left text-white relative z-10">
                    <span class="text-xs font-black uppercase tracking-[0.4em] text-white/60 mb-6 block">Mobile First</span>
                    <h2 class="text-5xl md:text-7xl font-black mb-8 leading-[0.95] tracking-tighter">Travel smart, <br> in your pocket.</h2>
                    <p class="text-xl text-white/70 mb-12 max-w-lg leading-relaxed font-medium">Download the Travix app for exclusive mobile-only deals, real-time tracking, and instant passes.</p>
                    
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-6">
                        <a href="#" class="flex items-center gap-4 bg-white text-tv-secondary px-8 py-5 rounded-[24px] font-black text-sm uppercase tracking-widest hover:scale-105 transition-all shadow-xl">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.1 2.48-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .76-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.36 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z" />
                            </svg>
                            App Store
                        </a>
                        <a href="#" class="flex items-center gap-4 border-2 border-white/20 bg-white/5 backdrop-blur-md text-white px-8 py-5 rounded-[24px] font-black text-sm uppercase tracking-widest hover:bg-white/10 transition-all">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 512 512">
                                <path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z"/>
                            </svg>
                            Play Store
                        </a>
                    </div>
                </div>

                <div class="flex-1 flex justify-center md:justify-end relative z-10">
                    <div class="relative w-[320px] h-[660px] bg-white rounded-[4rem] p-4 shadow-[0_60px_120px_-30px_rgba(0,0,0,0.5)] rotate-6 hover:rotate-0 transition-all duration-700 border-12 border-tv-secondary/10 group">
                        <div class="w-full h-full rounded-[3rem] bg-tv-secondary overflow-hidden relative">
                            <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?q=80&w=2070&auto=format&fit=crop" 
                                 class="w-full h-full object-cover opacity-60 scale-110 group-hover:scale-100 transition-transform duration-1000" alt="App Preview">
                            <div class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center bg-linear-to-t from-tv-secondary via-transparent to-transparent">
                                <div class="bg-white/95 rounded-3xl p-6 w-full shadow-2xl scale-90 group-hover:scale-100 transition-transform duration-700">
                                    <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-6">
                                        <div class="text-left">
                                            <p class="text-[8px] font-black text-tv-muted uppercase tracking-widest mb-1">LON</p>
                                            <p class="text-sm font-black text-tv-secondary">10:45 AM</p>
                                        </div>
                                        <div class="flex flex-col items-center gap-1">
                                            <svg class="w-5 h-5 text-tv-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                                            <div class="h-0.5 w-12 bg-linear-to-r from-transparent via-blue-200 to-transparent"></div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[8px] font-black text-tv-muted uppercase tracking-widest mb-1">NYC</p>
                                            <p class="text-sm font-black text-tv-secondary">1:30 PM</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                            <p class="text-[10px] font-black text-emerald-600 uppercase">On Time</p>
                                        </div>
                                        <p class="text-[10px] font-black text-tv-muted uppercase">Gate 42B</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Island --}}
                        <div class="absolute top-4 left-1/2 -translate-x-1/2 w-40 h-8 bg-tv-secondary/90 rounded-3xl border border-white/5 shadow-inner"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection