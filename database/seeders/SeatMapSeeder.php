<?php

namespace Database\Seeders;

use App\Models\SeatMap;
use App\Models\Aircraft;
use App\Models\TravelClass;
use Illuminate\Database\Seeder;

class SeatMapSeeder extends Seeder
{
    public function run(): void
    {
        $b737 = Aircraft::where('model', '737-800')->first();
        $a320 = Aircraft::where('model', 'A320-200')->first();
        $b777 = Aircraft::where('model', '777-300ER')->first();
        $a330 = Aircraft::where('model', 'A330-300')->first();
        $a321 = Aircraft::where('model', 'A321neo')->first();

        $economy = TravelClass::where('code', 'Y')->first();
        $business = TravelClass::where('code', 'C')->first();
        $first = TravelClass::where('code', 'F')->first();

        if ($b737 && $economy && $business) {
            // Boeing 737-800 - Business (rows 1-4, 4 seats per row = 16 seats)
            SeatMap::create([
                'aircraft_id' => $b737->id,
                'travel_class_id' => $business->id,
                'rows' => 4,
                'seats_per_row' => 4,
                'seat_layout' => '2-2',
                'start_row' => 1,
                'end_row' => 4,
            ]);

            // Boeing 737-800 - Economy (rows 5-35, 6 seats per row = 186 seats)
            SeatMap::create([
                'aircraft_id' => $b737->id,
                'travel_class_id' => $economy->id,
                'rows' => 31,
                'seats_per_row' => 6,
                'seat_layout' => '3-3',
                'start_row' => 5,
                'end_row' => 35,
            ]);
        }

        if ($a320 && $economy && $business) {
            // Airbus A320 - Business (rows 1-4, 4 seats per row = 16 seats)
            SeatMap::create([
                'aircraft_id' => $a320->id,
                'travel_class_id' => $business->id,
                'rows' => 4,
                'seats_per_row' => 4,
                'seat_layout' => '2-2',
                'start_row' => 1,
                'end_row' => 4,
            ]);

            // Airbus A320 - Economy (rows 5-32, 6 seats per row = 168 seats)
            SeatMap::create([
                'aircraft_id' => $a320->id,
                'travel_class_id' => $economy->id,
                'rows' => 28,
                'seats_per_row' => 6,
                'seat_layout' => '3-3',
                'start_row' => 5,
                'end_row' => 32,
            ]);
        }

        if ($b777 && $economy && $business && $first) {
            // Boeing 777-300ER - First Class (rows 1-2, 4 seats per row = 8 seats)
            SeatMap::create([
                'aircraft_id' => $b777->id,
                'travel_class_id' => $first->id,
                'rows' => 2,
                'seats_per_row' => 4,
                'seat_layout' => '1-2-1',
                'start_row' => 1,
                'end_row' => 2,
            ]);

            // Boeing 777-300ER - Business (rows 3-12, 8 seats per row = 80 seats)
            SeatMap::create([
                'aircraft_id' => $b777->id,
                'travel_class_id' => $business->id,
                'rows' => 10,
                'seats_per_row' => 8,
                'seat_layout' => '2-4-2',
                'start_row' => 3,
                'end_row' => 12,
            ]);

            // Boeing 777-300ER - Economy (rows 13-50, 10 seats per row = 380 seats)
            SeatMap::create([
                'aircraft_id' => $b777->id,
                'travel_class_id' => $economy->id,
                'rows' => 38,
                'seats_per_row' => 10,
                'seat_layout' => '3-4-3',
                'start_row' => 13,
                'end_row' => 50,
            ]);
        }

        if ($a330 && $economy && $business) {
            // Airbus A330-300 - Business (rows 1-8, 8 seats per row = 64 seats)
            SeatMap::create([
                'aircraft_id' => $a330->id,
                'travel_class_id' => $business->id,
                'rows' => 8,
                'seats_per_row' => 8,
                'seat_layout' => '2-2-2',
                'start_row' => 1,
                'end_row' => 8,
            ]);

            // Airbus A330-300 - Economy (rows 9-45, 8 seats per row = 296 seats)
            SeatMap::create([
                'aircraft_id' => $a330->id,
                'travel_class_id' => $economy->id,
                'rows' => 37,
                'seats_per_row' => 8,
                'seat_layout' => '2-4-2',
                'start_row' => 9,
                'end_row' => 45,
            ]);
        }

        if ($a321 && $economy && $business) {
            // Airbus A321neo - Business (rows 1-3, 4 seats per row = 12 seats)
            SeatMap::create([
                'aircraft_id' => $a321->id,
                'travel_class_id' => $business->id,
                'rows' => 3,
                'seats_per_row' => 4,
                'seat_layout' => '2-2',
                'start_row' => 1,
                'end_row' => 3,
            ]);

            // Airbus A321neo - Economy (rows 4-40, 6 seats per row = 222 seats)
            SeatMap::create([
                'aircraft_id' => $a321->id,
                'travel_class_id' => $economy->id,
                'rows' => 37,
                'seats_per_row' => 6,
                'seat_layout' => '3-3',
                'start_row' => 4,
                'end_row' => 40,
            ]);
        }
    }
}
