@props([
    'expiresAt',
    'refreshUrl',
    'title' => 'Selesaikan pembayaran sebelum waktu habis',
    'description' => 'Reservasi dan kursi yang dipilih akan dilepas otomatis saat timer mencapai nol.',
])

@if($expiresAt)
    <div
        x-data="{
            expiresAt: new Date('{{ $expiresAt->toIso8601String() }}').getTime(),
            remainingMs: 0,
            intervalId: null,
            init() {
                this.tick();
                this.intervalId = setInterval(() => this.tick(), 1000);
            },
            tick() {
                this.remainingMs = Math.max(this.expiresAt - Date.now(), 0);

                if (this.remainingMs === 0 && this.intervalId) {
                    clearInterval(this.intervalId);
                    this.intervalId = null;
                    window.setTimeout(() => {
                        window.location.href = '{{ $refreshUrl }}';
                    }, 1200);
                }
            },
            formattedTime() {
                const totalSeconds = Math.floor(this.remainingMs / 1000);
                const minutes = Math.floor(totalSeconds / 60).toString().padStart(2, '0');
                const seconds = (totalSeconds % 60).toString().padStart(2, '0');

                return `${minutes}:${seconds}`;
            },
            urgencyClass() {
                if (this.remainingMs <= 300000) {
                    return 'border-red-200 bg-red-50 text-red-700';
                }

                return 'border-amber-200 bg-amber-50 text-amber-800';
            }
        }"
        x-init="init()"
        class="rounded-2xl border p-4 sm:p-5"
        :class="urgencyClass()"
    >
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.2em]" x-text="remainingMs <= 300000 ? 'Batas Waktu Kritis' : 'Batas Waktu Pembayaran'"></p>
                <h3 class="mt-1 text-sm font-extrabold">{{ $title }}</h3>
                <p class="mt-1 text-xs opacity-80">{{ $description }}</p>
            </div>
            <div class="rounded-2xl bg-white/80 px-4 py-3 text-center shadow-sm ring-1 ring-black/5">
                <p class="text-[10px] font-black uppercase tracking-[0.25em] text-current/60">Sisa waktu</p>
                <p class="mt-1 font-mono text-3xl font-extrabold tracking-tight" x-text="formattedTime()"></p>
            </div>
        </div>
    </div>
@endif