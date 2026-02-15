<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SerpApiFlightService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.serpapi.api_key');
        $this->baseUrl = config('services.serpapi.base_url', 'https://serpapi.com/search.json');
    }

    /**
     * Search flights using Google Flights engine via SerpApi
     */
    public function searchFlights(
        string $departureId,
        string $arrivalId,
        string $outboundDate,
        ?string $returnDate = null,
        int $adults = 1,
        int $children = 0,
        int $infantsOnSeat = 0,
        int $infantsOnLap = 0,
        int $travelClass = 1, // 1: Economy, 2: Premium Economy, 3: Business, 4: First Class
        string $currency = 'IDR'
    ): array {
        try {
            $cacheKey = "serpapi_flights_{$departureId}_{$arrivalId}_{$outboundDate}_" . ($returnDate ?? 'oneway') . "_{$adults}_{$children}_{$infantsOnSeat}_{$infantsOnLap}_{$travelClass}_{$currency}";

            return Cache::remember($cacheKey, 3600, function () use ($departureId, $arrivalId, $outboundDate, $returnDate, $adults, $children, $infantsOnSeat, $infantsOnLap, $travelClass, $currency) {
                $params = [
                    'engine' => 'google_flights',
                    'departure_id' => $departureId,
                    'arrival_id' => $arrivalId,
                    'outbound_date' => $outboundDate,
                    'adults' => $adults,
                    'children' => $children,
                    'infants_on_seat' => $infantsOnSeat,
                    'infants_on_lap' => $infantsOnLap,
                    'travel_class' => $travelClass,
                    'currency' => $currency,
                    'hl' => 'id',
                    'gl' => 'id',
                    'api_key' => $this->apiKey,
                ];

                if ($returnDate) {
                    $params['return_date'] = $returnDate;
                    $params['type'] = 1; // Roundtrip
                } else {
                    $params['type'] = 2; // One-way
                }

                $response = Http::timeout(60)->get($this->baseUrl, $params);

                if ($response->successful()) {
                    $data = $response->json();
                    return $this->mapResults($data);
                }

                Log::error('SerpApi Google Flights Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [];
            });
        } catch (\Exception $e) {
            Log::error('SerpApi Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [];
        }
    }

    /**
     * Map SerpApi results to Travix flight structure
     */
    protected function mapResults(array $data): array
    {
        $flights = [];
        $bestFlights = $data['best_flights'] ?? [];
        $otherFlights = $data['other_flights'] ?? [];

        $allResults = array_merge($bestFlights, $otherFlights);

        foreach ($allResults as $result) {
            $totalPrice = $result['price'] ?? 0;
            if ($totalPrice <= 0)
                continue;

            foreach ($result['flights'] ?? [] as $flight) {
                $flights[] = [
                    'flight_number' => ($flight['airline'] ?? 'Flight') . ' ' . ($flight['flight_number'] ?? ''),
                    'airline' => $flight['airline'] ?? 'Unknown',
                    'airline_logo' => $flight['airline_logo'] ?? null,
                    'origin' => $flight['departure_airport']['id'] ?? '',
                    'origin_name' => $flight['departure_airport']['name'] ?? '',
                    'destination' => $flight['arrival_airport']['id'] ?? '',
                    'destination_name' => $flight['arrival_airport']['name'] ?? '',
                    'departure_time' => isset($flight['departure_airport']['time']) ?
                        date('H:i', strtotime($flight['departure_airport']['time'])) : 'N/A',
                    'arrival_time' => isset($flight['arrival_airport']['time']) ?
                        date('H:i', strtotime($flight['arrival_airport']['time'])) : 'N/A',
                    'duration' => $flight['duration'] ?? 0,
                    'price' => $result['price'] ?? 0,
                    'status' => 'scheduled',
                    'aircraft' => $flight['airplane'] ?? 'N/A',
                    'legroom' => $flight['legroom'] ?? null,
                    'amenities' => $flight['extensions'] ?? [],
                    'from_api' => true,
                    'engine' => 'serpapi_google_flights'
                ];

                // For simplicity, we only take the first segment if there are multiple
                break;
            }
        }

        return $flights;
    }
}
