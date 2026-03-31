@php
    $currentStep = isset($currentStep) ? (int) $currentStep : 1;
    $steps = [
        1 => 'Pilih Penerbangan',
        2 => 'Isi Data',
        3 => 'Pilih Kursi',
        4 => 'Pembayaran',
        5 => 'Berhasil',
    ];
@endphp

<div class="tv-card p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
        @foreach($steps as $index => $label)
            <div class="flex items-center gap-3">
                <div @class([
                    'w-8 h-8 rounded-full flex items-center justify-center text-xs font-black shrink-0 border',
                    'bg-emerald-500 border-emerald-500 text-white' => $currentStep > $index,
                    'bg-tv-primary border-tv-primary text-white' => $currentStep === $index,
                    'bg-white border-tv-border text-tv-muted' => $currentStep < $index,
                ])>
                    @if($currentStep > $index)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        {{ $index }}
                    @endif
                </div>
                <div>
                    <p @class([
                        'text-xs font-black uppercase tracking-wider',
                        'text-emerald-600' => $currentStep > $index,
                        'text-tv-primary' => $currentStep === $index,
                        'text-tv-muted' => $currentStep < $index,
                    ])>{{ $label }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
