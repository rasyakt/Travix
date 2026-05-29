<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin {{ config('app.name', 'Travix') }} — @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/pln.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-gray-50 antialiased">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside class="w-64 bg-tv-secondary text-white flex flex-col shrink-0">
            <div class="p-5 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 rounded-xl overflow-hidden bg-white p-1.5 shadow-sm">
                        <img src="{{ asset('img/pln.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="text-lg font-extrabold tracking-tight">Travix</span>
                        <span class="text-[9px] font-black uppercase tracking-widest text-tv-accent block leading-tight">Admin Panel</span>
                    </div>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <p class="px-4 pt-4 pb-1 text-[9px] font-black uppercase tracking-widest text-white/30">Management</p>

                <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.bookings.*') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Bookings
                </a>

                <a href="{{ route('admin.flights.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.flights.*') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                    Flights
                </a>

                <a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.schedules.*') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Schedules
                </a>

                <p class="px-4 pt-4 pb-1 text-[9px] font-black uppercase tracking-widest text-white/30">Master Data</p>

                <a href="{{ route('admin.airlines.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.airlines.*') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 10.5l1.5-3M7.5 4.5l1.5 3m0 0l-1.5 3m1.5-3h-6m6 0l1.5-3m-1.5 3l1.5 3M12 4.5l3 7.5m-3-7.5L9 12m3-7.5L15 12m-3 7.5l-3-7.5m3 7.5l3-7.5M21 10.5l-1.5-3M16.5 4.5l-1.5 3m1.5-3l1.5 3m-1.5 3l-1.5-3m1.5 3h-6"/></svg>
                    Airlines
                </a>

                <a href="{{ route('admin.airports.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.airports.*') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Airports
                </a>

                <a href="{{ route('admin.aircraft.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.aircraft.*') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    Aircraft
                </a>

                <p class="px-4 pt-4 pb-1 text-[9px] font-black uppercase tracking-widest text-white/30">Others</p>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.users.*') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Users
                </a>

                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.payments.*') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Payments
                </a>
            </nav>

            <div class="p-3 border-t border-white/10">
                <div class="flex items-center gap-3 px-4 py-2.5">
                    <img src="{{ Auth::user()->avatar_url }}" alt="Admin" class="w-8 h-8 rounded-xl object-cover ring-2 ring-white/20">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-white/40 uppercase tracking-wider font-bold">Admin</p>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 text-white/40 hover:text-white transition-colors" title="Logout">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Top Bar --}}
            <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between shrink-0">
                <div>
                    <h1 class="text-lg font-extrabold text-tv-text tracking-tight">@yield('title', 'Dashboard')</h1>
                    <p class="text-xs text-tv-muted">@yield('subtitle', '')</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-xs font-bold text-tv-muted hover:text-tv-primary transition-colors" target="_blank">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View Site
                        </span>
                    </a>
                </div>
            </header>

            {{-- Flash Messages --}}
            <div class="px-6 pt-4">
                @if(session('success'))
                    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-xl text-sm font-medium mb-4">
                        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-xl text-sm font-medium mb-4">
                        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('info'))
                    <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 text-blue-700 px-5 py-3.5 rounded-xl text-sm font-medium mb-4">
                        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        {{ session('info') }}
                    </div>
                @endif
            </div>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto px-6 py-6">
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
