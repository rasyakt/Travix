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

<div class="bg-white rounded-xl border border-gray-100 p-6 mb-6 shadow-sm">
    <div class="flex items-center justify-between max-w-4xl mx-auto">
        @foreach($steps as $index => $label)
            <div class="flex items-center {{ $index < count($steps) ? 'flex-1' : '' }}">
                {{-- Step Circle --}}
                <div class="flex flex-col items-center">
                    <div @class([
                        'w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300',
                        'bg-emerald-500 text-white shadow-sm' => $currentStep > $index,
                        'bg-blue-600 text-white shadow-md ring-4 ring-blue-100' => $currentStep === $index,
                        'bg-gray-100 text-gray-400' => $currentStep < $index,
                    ])>
                        @if($currentStep > $index)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            {{ $index }}
                        @endif
                    </div>
                    <p @class([
                        'mt-2 text-xs font-medium text-center whitespace-nowrap',
                        'text-emerald-600' => $currentStep > $index,
                        'text-blue-600' => $currentStep === $index,
                        'text-gray-400' => $currentStep < $index,
                    ])>{{ $label }}</p>
                </div>

                {{-- Connector Line --}}
                @if($index < count($steps))
                    <div class="flex-1 h-0.5 mx-3 {{ $currentStep > $index ? 'bg-emerald-500' : 'bg-gray-200' }} transition-all duration-300"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>
