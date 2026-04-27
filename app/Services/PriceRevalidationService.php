<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PriceRevalidationService
{
    protected $serpApiService;

    public function __construct(SerpApiFlightService $serpApiService)
    {
        $this->serpApiService = $serpApiService;
    }

    /**
     * Revalidate price for API flight before payment
     */
    public function revalidatePrice(array $flightData, array $searchParams): array
    {
        try {
            $cacheKey = "price_check_{$flightData['flight_number']}_" . md5(json_encode($searchParams));
            
            // Check cache first (valid for 5 minutes)
            $cachedResult = Cache::get($cacheKey);
            if ($cachedResult) {
                return $cachedResult;
            }

            // Re-search the flight
            $freshResults = $this->serpApiService->searchFlights(
                $searchParams['origin'],
                $searchParams['destination'],
                $searchParams['departure_date'],
                $searchParams['return_date'] ?? null,
                $searchParams['adults'] ?? 1,
                $searchParams['children'] ?? 0,
                $searchParams['infants'] ?? 0,
                0,
                $this->getSeatClassId($searchParams['seat_class'] ?? 'Economy')
            );

            // Find matching flight
            $matchingFlight = $this->findMatchingFlight($freshResults, $flightData);

            if (!$matchingFlight) {
                return [
                    'available' => false,
                    'reason' => 'Flight no longer available',
                    'original_price' => $flightData['price'],
                    'current_price' => null,
                ];
            }

            $originalPrice = $flightData['price'];
            $currentPrice = $matchingFlight['price'];
            $priceDifference = $currentPrice - $originalPrice;
            $priceChangePercentage = ($priceDifference / $originalPrice) * 100;

            $result = [
                'available' => true,
                'original_price' => $originalPrice,
                'current_price' => $currentPrice,
                'price_difference' => $priceDifference,
                'price_change_percentage' => round($priceChangePercentage, 2),
                'price_increased' => $priceDifference > 0,
                'price_decreased' => $priceDifference < 0,
                'price_stable' => $priceDifference === 0,
                'seats_available' => true, // API doesn't provide exact count
                'revalidated_at' => now()->toDateTimeString(),
            ];

            // Cache for 5 minutes
            Cache::put($cacheKey, $result, 300);

            return $result;

        } catch (\Exception $e) {
            Log::error('Price Revalidation Error', [
                'message' => $e->getMessage(),
                'flight' => $flightData['flight_number'] ?? 'unknown',
            ]);

            // Return original price if revalidation fails
            return [
                'available' => true,
                'original_price' => $flightData['price'],
                'current_price' => $flightData['price'],
                'price_difference' => 0,
                'price_stable' => true,
                'revalidation_failed' => true,
                'error' => 'Unable to revalidate price, proceeding with original price',
            ];
        }
    }

    protected function findMatchingFlight(array $results, array $originalFlight): ?array
    {
        foreach ($results as $flight) {
            // Match by flight number and times
            if (
                $flight['flight_number'] === $originalFlight['flight_number'] &&
                $flight['departure_time'] === $originalFlight['departure_time'] &&
                $flight['arrival_time'] === $originalFlight['arrival_time']
            ) {
                return $flight;
            }
        }

        return null;
    }

    protected function getSeatClassId(string $className): int
    {
        return match ($className) {
            'Economy' => 1,
            'Premium Economy' => 2,
            'Business' => 3,
            'First Class' => 4,
            default => 1,
        };
    }
}
