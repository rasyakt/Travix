<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AviationStackService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.aviationstack.api_key');
        $this->baseUrl = config('services.aviationstack.base_url', 'http://api.aviationstack.com/v1');
    }

    /**
     * Search flights by route and date
     */
    public function searchFlights(string $departure, string $arrival, ?string $date = null): array
    {
        try {
            $cacheKey = "flights_{$departure}_{$arrival}_" . ($date ?? 'today');

            return Cache::remember($cacheKey, 3600, function () use ($departure, $arrival, $date) {
                $params = [
                    'access_key' => $this->apiKey,
                    'dep_iata' => $departure,
                    'arr_iata' => $arrival,
                ];

                if ($date) {
                    $params['flight_date'] = $date;
                }

                $response = Http::timeout(30)->get("{$this->baseUrl}/flights", $params);

                if ($response->successful()) {
                    return $response->json()['data'] ?? [];
                }

                Log::error('AviationStack API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [];
            });
        } catch (\Exception $e) {
            Log::error('AviationStack Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [];
        }
    }

    /**
     * Get real-time flight status
     */
    public function getFlightStatus(string $flightIata): array
    {
        try {
            $cacheKey = "flight_status_{$flightIata}";

            return Cache::remember($cacheKey, 300, function () use ($flightIata) {
                $response = Http::timeout(30)->get("{$this->baseUrl}/flights", [
                    'access_key' => $this->apiKey,
                    'flight_iata' => $flightIata,
                ]);

                if ($response->successful()) {
                    $data = $response->json()['data'] ?? [];
                    return $data[0] ?? [];
                }

                return [];
            });
        } catch (\Exception $e) {
            Log::error('Flight Status Exception', [
                'flight' => $flightIata,
                'message' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Get airlines list
     */
    public function getAirlines(int $limit = 100): array
    {
        try {
            return Cache::remember('airlines_list', 86400, function () use ($limit) {
                $response = Http::timeout(30)->get("{$this->baseUrl}/airlines", [
                    'access_key' => $this->apiKey,
                    'limit' => $limit,
                ]);

                if ($response->successful()) {
                    return $response->json()['data'] ?? [];
                }

                return [];
            });
        } catch (\Exception $e) {
            Log::error('Get Airlines Exception', ['message' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get airports list
     */
    public function getAirports(int $limit = 100): array
    {
        try {
            return Cache::remember('airports_list', 86400, function () use ($limit) {
                $response = Http::timeout(30)->get("{$this->baseUrl}/airports", [
                    'access_key' => $this->apiKey,
                    'limit' => $limit,
                ]);

                if ($response->successful()) {
                    return $response->json()['data'] ?? [];
                }

                return [];
            });
        } catch (\Exception $e) {
            Log::error('Get Airports Exception', ['message' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Sync flight data to database
     */
    public function syncFlightToDatabase(array $flightData): ?\App\Models\Flight
    {
        try {
            if (empty($flightData)) {
                return null;
            }

            // Find flight by flight number
            $flight = \App\Models\Flight::where('flight_number', $flightData['flight']['iata'] ?? '')
                ->first();

            if (!$flight) {
                return null;
            }

            // Map API status to our status
            $status = $this->mapApiStatus($flightData['flight_status'] ?? 'scheduled');

            // Update flight
            $flight->update([
                'status' => $status,
            ]);

            // Log status change
            \App\Models\FlightStatusLog::create([
                'flight_id' => $flight->id,
                'old_status' => $flight->getOriginal('status'),
                'new_status' => $status,
                'changed_at' => now(),
                'source' => 'aviationstack_api',
                'raw_data' => json_encode($flightData),
            ]);

            return $flight;
        } catch (\Exception $e) {
            Log::error('Sync Flight Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    private function mapApiStatus(string $apiStatus): string
    {
        return match (strtolower($apiStatus)) {
            'scheduled' => \App\Enums\FlightStatus::SCHEDULED->value,
            'active', 'en-route' => \App\Enums\FlightStatus::ACTIVE->value,
            'landed' => \App\Enums\FlightStatus::LANDED->value,
            'cancelled' => \App\Enums\FlightStatus::CANCELLED->value,
            'delayed' => \App\Enums\FlightStatus::DELAYED->value,
            default => \App\Enums\FlightStatus::SCHEDULED->value,
        };
    }
}
