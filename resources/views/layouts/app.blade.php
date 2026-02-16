<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Travix') }} — @yield('title', 'Airlines Reservation')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/pln.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen flex flex-col">
    {{-- ═══ Navigation ═══ --}}
    <nav class="bg-white/80 backdrop-blur-xl sticky top-0 z-50 border-b border-tv-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <div
                            class="w-10 h-10 rounded-xl overflow-hidden shadow-sm shadow-blue-200/60 group-hover:shadow-md group-hover:shadow-blue-200/80 transition-all flex items-center justify-center bg-white p-1.5">
                            <img src="{{ asset('img/pln.png') }}" alt="Travix Logo"
                                class="w-full h-full object-contain">
                        </div>
                        <span class="text-xl font-extrabold text-tv-secondary tracking-tight">Travix</span>
                    </a>
                </div>

                {{-- Centered Nav Links --}}
                <div class="hidden md:flex items-center gap-10">
                    <a href="{{ route('flights.index') }}"
                        class="text-[11px] font-black uppercase tracking-[0.2em] transition-all duration-300 {{ request()->routeIs('flights.*') ? 'text-tv-primary' : 'text-tv-muted hover:text-tv-primary' }}">Book</a>
                    <a href="{{ route('dashboard') }}"
                        class="text-[11px] font-black uppercase tracking-[0.2em] transition-all duration-300 {{ request()->routeIs('dashboard') ? 'text-tv-primary' : 'text-tv-muted hover:text-tv-primary' }}">Manage</a>
                    <a href="#"
                        class="text-[11px] font-black uppercase tracking-[0.2em] text-tv-muted hover:text-tv-primary transition-all duration-300">Experience</a>
                    <a href="#"
                        class="text-[11px] font-black uppercase tracking-[0.2em] text-tv-muted hover:text-tv-primary transition-all duration-300">Destinations</a>
                </div>

                {{-- Right Side Buttons --}}
                <div class="flex items-center gap-6">
                    @auth
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden sm:block">
                                <p class="text-[10px] font-black text-tv-muted uppercase tracking-widest mb-0.5">Welcome</p>
                                <p class="text-xs font-black text-tv-secondary">{{ Auth::user()->name }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="relative group">
                                <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                    alt="Avatar"
                                    class="w-10 h-10 rounded-xl object-cover ring-2 ring-transparent group-hover:ring-tv-primary/20 transition-all">
                                <div
                                    class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full">
                                </div>
                            </a>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit"
                                class="text-xs font-black uppercase text-tv-muted hover:text-red-500 transition-colors">Sign
                                Out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-xs font-black uppercase text-tv-muted hover:text-tv-primary transition-all">Sign
                            In</a>
                        <a href="{{ route('register') }}"
                            class="bg-tv-primary hover:bg-[#0560c7] text-white text-xs font-black px-8 py-2.5 rounded-xl shadow-lg shadow-tv-primary/20 transition-all hover:scale-105 active:scale-95 uppercase tracking-widest">Daftar</a>
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
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
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

    {{-- ═══ Custom Footer ═══ --}}
    <footer class="bg-tv-secondary py-20 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-16 md:gap-8">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-tv-primary flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-black tracking-tight">Travix</span>
                    </div>
                    <p class="text-white/50 leading-relaxed mb-8">Redefining luxury travel with premium service,
                        transparent pricing, and a commitment to your comfort.</p>
                    <div class="flex items-center gap-4">
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-white/10 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-white/10 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.058-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest mb-8 text-white/40">Company</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-white/60 hover:text-white transition-colors font-bold">About Us</a>
                        </li>
                        <li><a href="#" class="text-white/60 hover:text-white transition-colors font-bold">Careers</a>
                        </li>
                        <li><a href="#" class="text-white/60 hover:text-white transition-colors font-bold">Blog</a></li>
                        <li><a href="#" class="text-white/60 hover:text-white transition-colors font-bold">Press</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest mb-8 text-white/40">Support</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-white/60 hover:text-white transition-colors font-bold">Help
                                Center</a></li>
                        <li><a href="#" class="text-white/60 hover:text-white transition-colors font-bold">Terms of
                                Service</a></li>
                        <li><a href="#" class="text-white/60 hover:text-white transition-colors font-bold">Privacy
                                Policy</a></li>
                        <li><a href="#" class="text-white/60 hover:text-white transition-colors font-bold">Cookie
                                Policy</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest mb-8 text-white/40">Join the Trivix Club
                    </h4>
                    <p class="text-white/50 text-sm mb-6 leading-relaxed">Subscribe for exclusive offers and travel
                        inspiration.</p>
                    <div class="relative">
                        <input type="email" placeholder="Email address"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white placeholder-white/20 focus:outline-none focus:border-tv-primary focus:ring-1 focus:ring-tv-primary transition-all">
                        <button
                            class="w-full mt-4 bg-tv-primary hover:bg-tv-primary/90 text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-tv-primary/20">Subscribe</button>
                    </div>
                </div>
            </div>

            <div
                class="mt-20 pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-8">
                <p class="text-white/30 text-sm font-bold">&copy; 2026 Trivix Inc. All rights reserved.</p>
                <div class="flex flex-wrap justify-center items-center gap-8">
                    <a href="#"
                        class="text-xs font-black uppercase text-white/30 hover:text-white/60 transition-colors">Sitemap</a>
                    <a href="#"
                        class="text-xs font-black uppercase text-white/30 hover:text-white/60 transition-colors">Security</a>
                    <a href="#"
                        class="text-xs font-black uppercase text-white/30 hover:text-white/60 transition-colors">Accessibility</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>

</html>