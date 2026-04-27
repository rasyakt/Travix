<?php

namespace Database\Seeders;

use App\Models\FlightSeatPrice;
use App\Models\Flight;
use App\Models\TravelClass;
use App\Models\SeatMap;
use Illuminate\Database\Seeder;

class FlightSeatPriceSeeder extends Seeder
{
    public function run(): void
    {
        $flights = Flight::with(['schedule.aircraft', 'schedule.airline'])->get();
        $travelClasses = TravelClass::all();

        foreach ($flights as $flight) {
            $aircraftId = $flight->schedule?->aircraft_id;

            if (!$aircraftId) {
                continue;
            }

            foreach ($travelClasses as $travelClass) {
                // Count how many seats this aircraft has for this class
                $airlineId = $flight->schedule?->airline_id;

                // Prefer airline-specific seat maps; fall back to generic (airline_id = null).
                $seatCount = SeatMap::where('aircraft_id', $aircraftId)
                    ->where('travel_class_id', $travelClass->id)
                    ->where('airline_id', $airlineId)
                    ->count();
                if ($seatCount === 0) {
                    $seatCount = SeatMap::where('aircraft_id', $aircraftId)
                        ->where('travel_class_id', $travelClass->id)
                        ->whereNull('airline_id')
                        ->count();
                }

                if ($seatCount > 0) {
                    $basePrice = $flight->schedule?->base_price ?? 500000;
                    $price = $basePrice * $travelClass->price_multiplier;

                    FlightSeatPrice::updateOrCreate(
                        [
                            'flight_id' => $flight->id,
                            'travel_class_id' => $travelClass->id,
                        ],
                        [
                            'price' => $price,
                            'available_seats' => $seatCount,
                            'total_seats' => $seatCount,
                        ]
                    );
                }
            }
        }
    }
}
