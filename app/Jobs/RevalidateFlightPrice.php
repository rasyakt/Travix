<?php

namespace App\Jobs;

use App\Services\PriceRevalidationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RevalidateFlightPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $flightData;
    public $searchParams;
    public $bookingId;
    public $tries = 2;
    public $timeout = 30;

    public function __construct(array $flightData, array $searchParams, int $bookingId)
    {
        $this->flightData = $flightData;
        $this->searchParams = $searchParams;
        $this->bookingId = $bookingId;
    }

    public function handle(PriceRevalidationService $priceService): void
    {
        try {
            $result = $priceService->revalidatePrice($this->flightData, $this->searchParams);

            // Store result in cache for quick access
            cache()->put(
                "price_revalidation_{$this->bookingId}",
                $result,
                now()->addMinutes(10)
            );

            Log::info('Price revalidation completed', [
                'booking_id' => $this->bookingId,
                'price_changed' => !$result['price_stable'],
                'difference' => $result['price_difference'] ?? 0,
            ]);

        } catch (\Exception $e) {
            Log::error('Price revalidation failed', [
                'booking_id' => $this->bookingId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
