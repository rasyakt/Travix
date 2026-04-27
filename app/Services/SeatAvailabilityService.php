<?php

namespace App\Services;

use App\Models\Flight;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SeatAvailabilityService
{
    /**
     * Check seat availability for database flights
     */
    public function checkDatabaseFlight(int $flightId, int $travelClassId, int $passengerCount): array
    {
        try {
            $flight = Flight::with('seatPrices')->find($flightId);

            if (!$flight) {
                return [
                    'available' => false,
                    'reason' => 'Flight not found',
                ];
            }

            $classSeats = $flight->getAvailableSeatsForClass($travelClassId);
            $totalSeats = $flight->available_seats;

            return [
                'available' => $classSeats >= $passengerCount && $totalSeats >= $passengerCount,
                'class_seats_available' => $classSeats,
                'total_seats_available' => $totalSeats,
                'requested_seats' => $passengerCount,
                'can_accommodate' => $classSeats >= $passengerCount,
                'reason' => $classSeats < $passengerCount ? 'Insufficient seats in selected class' : null,
            ];

        } catch (\Exception $e) {
            Log::error('Seat Availability Check Error', [
                'flight_id' => $flightId,
                'message' => $e->getMessage(),
            ]);

            return [
                'available' => false,
                'reason' => 'Unable to check availability',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check seat availability for API flights (estimated)
     */
    public function checkApiFlightEstimate(array $flightData, int $passengerCount): array
    {
        // For API flights, we estimate based on typical aircraft capacity
        $aircraftType = $flightData['aircraft'] ?? 'Unknown';
        $estimatedCapacity = $this->estimateCapacity($aircraftType);
        
        // Assume 80% load factor for safety
        $estimatedAvailable = (int) ($estimatedCapacity * 0.2);

        return [
            'available' => $estimatedAvailable >= $passengerCount,
            'estimated_seats' => $estimatedAvailable,
            'requested_seats' => $passengerCount,
            'aircraft_type' => $aircraftType,
            'is_estimate' => true,
            'confidence' => 'medium',
            'note' => 'Availability will be confirmed by partner airline',
        ];
    }

    /**
     * Reserve seats temporarily (for database flights)
     */
    public function reserveSeats(int $flightId, int $travelClassId, int $passengerCount, int $bookingId): bool
    {
        try {
            $lockKey = "seat_reservation_{$flightId}_{$travelClassId}";
            
            return Cache::lock($lockKey, 10)->block(5, function () use ($flightId, $travelClassId, $passengerCount, $bookingId) {
                $flight = Flight::lockForUpdate()->find($flightId);
                
                if (!$flight) {
                    return false;
                }

                // Check availability again under lock
                $available = $flight->getAvailableSeatsForClass($travelClassId);
                
                if ($available < $passengerCount) {
                    return false;
                }

                // Decrease available seats
                return $flight->decreaseAvailableSeats($passengerCount, $travelClassId);
            });

        } catch (\Exception $e) {
            Log::error('Seat Reservation Error', [
                'flight_id' => $flightId,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Release reserved seats (on booking cancellation)
     */
    public function releaseSeats(int $flightId, int $travelClassId, int $passengerCount): bool
    {
        try {
            $flight = Flight::lockForUpdate()->find($flightId);
            
            if (!$flight) {
                return false;
            }

            return $flight->increaseAvailableSeats($passengerCount, $travelClassId);

        } catch (\Exception $e) {
            Log::error('Seat Release Error', [
                'flight_id' => $flightId,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    protected function estimateCapacity(string $aircraftType): int
    {
        // Common aircraft capacities
        $capacities = [
            'Boeing 737' => 180,
            'Airbus A320' => 180,
            'Boeing 777' => 350,
            'Airbus A330' => 300,
            'Boeing 787' => 280,
            'Airbus A350' => 300,
            'ATR 72' => 70,
            'Bombardier CRJ' => 90,
        ];

        foreach ($capacities as $type => $capacity) {
            if (stripos($aircraftType, $type) !== false) {
                return $capacity;
            }
        }

        // Default estimate
        return 150;
    }
}
