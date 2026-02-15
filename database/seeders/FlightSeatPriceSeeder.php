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
        $flights = Flight::with('aircraftInstance.aircraft')->get();
        $travelClasses = TravelClass::all();

        foreach ($flights as $flight) {
            if (!$flight->aircraftInstance || !$flight->aircraftInstance->aircraft) {
                continue;
            }

            $aircraftId = $flight->aircraftInstance->aircraft->id;

            foreach ($travelClasses as $travelClass) {
                $seatMap = SeatMap::where('aircraft_id', $aircraftId)
                    ->where('travel_class_id', $travelClass->id)
                    ->first();

                if ($seatMap) {
                    $basePrice = $flight->schedule?->base_price ?? 500000;
                    $price = $basePrice * $travelClass->price_multiplier;

                    FlightSeatPrice::create([
                        'flight_id' => $flight->id,
                        'travel_class_id' => $travelClass->id,
                        'price' => $price,
                    ]);
                }
            }
        }
    }
}
