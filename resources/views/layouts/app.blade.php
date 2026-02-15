<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Travix') }} — @yield('title', 'Airlines Reservation')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen flex flex-col">
    {{-- ═══ Navigation ═══ --}}
    <nav class="bg-white/80 backdrop-blur-xl sticky top-0 z-50 border-b border-[#e8ecf1]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- Logo + Nav Links --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <div
                            class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#0770e3] to-[#0560c7] flex items-center justify-center shadow-sm shadow-blue-200/60 group-hover:shadow-md group-hover:shadow-blue-200/80 transition-all">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                        </div>
                        <span class="text-xl font-extrabold text-[#05264e] tracking-tight">Travix</span>
                    </a>

                    @auth
                        <div class="hidden sm:flex items-center gap-1">
                            <a href="{{ route('flights.index') }}"
                                class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200
                                      {{ request()->routeIs('flights.*') ? 'bg-blue-50 text-[#0770e3]' : 'text-[#687b8e] hover:bg-gray-50 hover:text-[#1a2b49]' }}">
                                Flights
                            </a>
                            <a href="{{ route('dashboard') }}"
                                class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200
                                      {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-[#0770e3]' : 'text-[#687b8e] hover:bg-gray-50 hover:text-[#1a2b49]' }}">
                                My Bookings
                            </a>
                        </div>
                    @endauth
                </div>

                {{-- User Area --}}
                <div class="flex items-center gap-3">
                    @auth
                        <div class="hidden md:flex items-center gap-3">
                            <div class="text-right">
                                <p class="text-xs font-medium text-[#687b8e]">Welcome,</p>
                                <p class="text-sm font-bold text-[#1a2b49] -mt-0.5">{{ auth()->user()->name }}</p>
                            </div>
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Avatar"
                                    class="h-9 w-9 rounded-xl object-cover border-2 border-blue-100">
                            @else
                                <div
                                    class="h-9 w-9 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center text-[#0770e3] font-bold text-sm">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="h-6 w-px bg-[#e8ecf1] hidden md:block"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-tv-ghost text-sm gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="hidden sm:inline">Sign Out</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('auth.google') }}" class="btn-tv-primary gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12.48 10.92v3.28h7.84c-.24 1.84-.9 3.03-1.6 3.75-1.04 1.04-2.6 2.14-5.64 2.14-5.11 0-9.2-4.14-9.2-9.2s4.09-9.2 9.2-9.2c2.72 0 4.67 1.06 6.09 2.42l2.31-2.31C19.16 2.38 16.32 1.07 12.48 1.07 6.46 1.07 1.5 6.03 1.5 12.05s4.96 10.98 10.98 10.98c3.27 0 5.75-1.08 7.64-3.05 1.95-1.95 2.57-4.7 2.57-6.95 0-.64-.05-1.24-.15-1.74h-10.36z" />
                            </svg>
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ═══ Flash Messages ═══ --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-xl text-sm font-medium"
                role="alert">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-xl text-sm font-medium"
                role="alert">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- ═══ Page Content ═══ --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ═══ Footer ═══ --}}
    <footer class="bg-white border-t border-[#e8ecf1] mt-auto">
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                {{-- Brand --}}
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#0770e3] to-[#0560c7] flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                        </div>
                        <span class="text-lg font-extrabold text-[#05264e]">Travix</span>
                    </div>
                    <p class="text-sm text-[#687b8e] leading-relaxed">Your trusted airline reservation platform. Fast,
                        reliable, and secure booking experience.</p>
                </div>
                {{-- Links --}}
                <div>
                    <h4 class="text-xs font-bold text-[#1a2b49] uppercase tracking-wider mb-4">Product</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('flights.index') }}"
                                class="text-sm text-[#687b8e] hover:text-[#0770e3] transition-colors">Flight Search</a>
                        </li>
                        <li><span class="text-sm text-[#a0aec0]">Hotels (Coming Soon)</span></li>
                        <li><span class="text-sm text-[#a0aec0]">Activities (Coming Soon)</span></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-[#1a2b49] uppercase tracking-wider mb-4">Support</h4>
                    <ul class="space-y-2.5">
                        <li><span class="text-sm text-[#687b8e]">Help Center</span></li>
                        <li><span class="text-sm text-[#687b8e]">Refund Policy</span></li>
                        <li><span class="text-sm text-[#687b8e]">Privacy Policy</span></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-[#1a2b49] uppercase tracking-wider mb-4">Connect</h4>
                    <ul class="space-y-2.5">
                        <li><span class="text-sm text-[#687b8e]">support@travix.com</span></li>
                        <li><span class="text-sm text-[#687b8e]">+62 21 1234 5678</span></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-[#e8ecf1] pt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-sm text-[#a0aec0]">&copy; {{ date('Y') }} Travix. All rights reserved.</p>
                <div class="flex items-center gap-2">
                    <span class="tv-badge-blue text-[10px]">✓ Secure</span>
                    <span class="tv-badge-green text-[10px]">✓ Verified Partner</span>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>

</html>