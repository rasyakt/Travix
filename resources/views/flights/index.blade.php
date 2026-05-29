@extends('layouts.app')

@section('title', 'Search Flights')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="tv-section-title">Find Flights</h1>
            <p class="tv-section-subtitle">Search hundreds of airlines to compare and book</p>
        </div>

        {{-- Popular Routes --}}
        <div class="mb-10">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-tv-accent" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                <h2 class="font-bold text-tv-text">Popular Routes</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach([
                    ['from' => 'CGK', 'to' => 'DPS', 'label' => 'Jakarta → Denpasar'],
                    ['from' => 'CGK', 'to' => 'SIN', 'label' => 'Jakarta → Singapore'],
                    ['from' => 'SUB', 'to' => 'CGK', 'label' => 'Surabaya → Jakarta'],
                    ['from' => 'DPS', 'to' => 'SIN', 'label' => 'Denpasar → Singapore'],
                ] as $route)
                <button onclick="fillRoute(this.dataset.from, this.dataset.to)"
                    data-from="{{ $route['from'] }}"
                    data-to="{{ $route['to'] }}"
                    class="tv-card-interactive p-4 text-left group">
                    <p class="text-[10px] font-semibold text-[#a0aec0] group-hover:text-tv-primary transition-colors uppercase tracking-wider mb-1">{{ $route['label'] }}</p>
                    <div class="flex items-center gap-2">
                        <span class="text-lg font-extrabold text-tv-text">{{ $route['from'] }}</span>
                        <svg class="w-4 h-4 text-tv-border group-hover:text-tv-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        <span class="text-lg font-extrabold text-tv-text">{{ $route['to'] }}</span>
                    </div>
                </button>
                @endforeach
            </div>
        </div>

        {{-- Search Component --}}
        <div class="tv-card p-6 md:p-8">
            @livewire('flight-search')
        </div>
    </div>

    <script>
        function fillRoute(origin, destination) {
            const componentEl = document.querySelector('[wire\\:id]');
            if (!componentEl) return;
            
            const componentId = componentEl.getAttribute('wire:id');
            if (window.Livewire) {
                const component = window.Livewire.find(componentId);
                if (component) {
                    component.call('selectPopularRoute', origin, destination);
                }
            }
        }
    </script>
@endsection
