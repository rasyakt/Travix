@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    {{-- ═══ Hero Section ═══ --}}
    <div class="relative">
        {{-- Background --}}
        <div class="absolute inset-0 bg-linear-to-br from-tv-primary via-[#0560c7] to-tv-secondary overflow-hidden">
            <div class="absolute inset-0 opacity-[0.07]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            {{-- Abstract Shape --}}
            <div class="absolute -top-40 -right-40 w-[600px] h-[600px] rounded-full bg-white/5"></div>
            <div class="absolute -bottom-60 -left-20 w-[400px] h-[400px] rounded-full bg-white/5"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32">
            <div class="text-center mb-14">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/10 rounded-full px-5 py-2 mb-6">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                    <span class="text-xs font-semibold text-white/80 tracking-wide">400+ flights available today</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-5 tracking-tight text-balance">
                    Travel Made <span class="text-transparent bg-clip-text bg-linear-to-r from-orange-300 to-tv-accent">Simple</span>
                </h1>
                <p class="text-lg text-white/60 max-w-xl mx-auto font-medium">
                    Compare, book, and fly — all in one place. Get the best fares to your dream destinations.
                </p>
            </div>

            {{-- Search Box --}}
            <div class="max-w-5xl mx-auto">
                {{-- Search Form --}}
                @livewire('flight-search', ['minimal' => true])
            </div>
        </div>
    </div>

    {{-- ═══ Trust Indicators ═══ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10 mb-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="tv-card p-6 flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-tv-text mb-1">Instant Booking</h3>
                    <p class="text-sm text-tv-muted leading-relaxed">Real-time confirmation with immediate access to your digital tickets.</p>
                </div>
            </div>
            <div class="tv-card p-6 flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-tv-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-tv-text mb-1">Best Price Guarantee</h3>
                    <p class="text-sm text-tv-muted leading-relaxed">We compare airlines to guarantee you the lowest available fare.</p>
                </div>
            </div>
            <div class="tv-card p-6 flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-tv-text mb-1">Secure & Trusted</h3>
                    <p class="text-sm text-tv-muted leading-relaxed">End-to-end encryption, payment protection, and verified partners.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ How It Works ═══ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-14">
            <h2 class="tv-section-title">Your Journey, Simplified</h2>
            <p class="tv-section-subtitle mt-2">Book your next flight in just 3 easy steps</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @foreach([
                ['step' => '01', 'title' => 'Search & Compare', 'desc' => 'Enter your destination and travel dates. We search thousands of flights to show you the best options.', 'color' => 'blue'],
                ['step' => '02', 'title' => 'Select & Book', 'desc' => 'Choose your preferred flight, pick your seats, and securely complete your reservation online.', 'color' => 'orange'],
                ['step' => '03', 'title' => 'Fly & Enjoy', 'desc' => 'Check in online, get your digital boarding pass, and head straight to the gate. Bon voyage!', 'color' => 'emerald'],
            ] as $item)
            <div class="text-center">
                <div class="text-5xl font-extrabold text-tv-border mb-4">{{ $item['step'] }}</div>
                <h3 class="text-lg font-bold text-tv-text mb-2">{{ $item['title'] }}</h3>
                <p class="text-sm text-tv-muted leading-relaxed max-w-xs mx-auto">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ═══ CTA Banner ═══ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-tv-secondary to-tv-primary p-12 md:p-16">
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/5 -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full bg-white/5 translate-y-1/2 -translate-x-1/2"></div>
            <div class="relative text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4 tracking-tight">Ready for Your Next Adventure?</h2>
                <p class="text-lg text-white/50 max-w-lg mx-auto mb-8">Start searching for the perfect flight and discover a world of possibilities.</p>
                <a href="{{ route('flights.index') }}" class="btn-tv-accent text-lg py-4 px-10">
                    Explore Flights
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>
@endsection