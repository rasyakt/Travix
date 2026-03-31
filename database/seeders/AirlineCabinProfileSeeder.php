<?php

namespace Database\Seeders;

use App\Models\Aircraft;
use App\Models\Airline;
use App\Models\AirlineCabinProfile;
use App\Models\TravelClass;
use Illuminate\Database\Seeder;

/**
 * Seeds airline-specific cabin configurations.
 *
 * Each profile defines how a particular airline configures a specific aircraft type
 * for a given travel class: seat rows, column letters, aisle layout, and exit rows.
 * This replaces the generic per-aircraft seat layout with realistic per-airline data.
 */
class AirlineCabinProfileSeeder extends Seeder
{
    public function run(): void
    {
        $economy  = TravelClass::where('code', 'Y')->first();
        $business = TravelClass::where('code', 'C')->first();
        $first    = TravelClass::where('code', 'F')->first();

        $b737 = Aircraft::where('model', '737-800')->first();
        $a320 = Aircraft::where('model', 'A320-200')->first();
        $b777 = Aircraft::where('model', '777-300ER')->first();
        $a330 = Aircraft::where('model', 'A330-300')->first();
        $a321 = Aircraft::where('model', 'A321neo')->first();

        // ─── Garuda Indonesia (GA) ────────────────────────────────────────────
        // Full-service carrier; generous cabin ratios.
        $garuda = Airline::where('iata_code', 'GA')->first();
        if ($garuda) {
            // B737-800: 6 rows Executive (2-2) + 29 rows Economy (3-3)
            if ($b737 && $business) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $garuda->id, 'aircraft_id' => $b737->id, 'travel_class_id' => $business->id],
                    [
                        'start_row' => 1, 'end_row' => 6,
                        'columns' => ['A', 'C', 'D', 'F'],
                        'layout_code' => '2-2',
                        'exit_rows' => null,
                        'extra_price_exit' => 0,
                    ]
                );
            }
            if ($b737 && $economy) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $garuda->id, 'aircraft_id' => $b737->id, 'travel_class_id' => $economy->id],
                    [
                        'start_row' => 7, 'end_row' => 35,
                        'columns' => ['A', 'B', 'C', 'D', 'E', 'F'],
                        'layout_code' => '3-3',
                        'exit_rows' => [16, 17],
                        'extra_price_exit' => 150000,
                    ]
                );
            }
            // B777-300ER: 3 rows First (1-2-1) + 8 rows Business (2-3-2) + 38 rows Economy (3-3-3)
            if ($b777 && $first) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $garuda->id, 'aircraft_id' => $b777->id, 'travel_class_id' => $first->id],
                    [
                        'start_row' => 1, 'end_row' => 3,
                        'columns' => ['A', 'D', 'G', 'K'],
                        'layout_code' => '1-2-1',
                        'exit_rows' => null,
                        'extra_price_exit' => 0,
                    ]
                );
            }
            if ($b777 && $business) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $garuda->id, 'aircraft_id' => $b777->id, 'travel_class_id' => $business->id],
                    [
                        'start_row' => 4, 'end_row' => 11,
                        'columns' => ['A', 'B', 'D', 'E', 'F', 'J', 'K'],
                        'layout_code' => '2-3-2',
                        'exit_rows' => null,
                        'extra_price_exit' => 0,
                    ]
                );
            }
            if ($b777 && $economy) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $garuda->id, 'aircraft_id' => $b777->id, 'travel_class_id' => $economy->id],
                    [
                        'start_row' => 12, 'end_row' => 49,
                        'columns' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K'],
                        'layout_code' => '3-4-3',
                        'exit_rows' => [24, 25, 40],
                        'extra_price_exit' => 200000,
                    ]
                );
            }
            // A330-300: 8 rows Business (2-2-2) + 36 rows Economy (2-4-2)
            if ($a330 && $business) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $garuda->id, 'aircraft_id' => $a330->id, 'travel_class_id' => $business->id],
                    [
                        'start_row' => 1, 'end_row' => 8,
                        'columns' => ['A', 'B', 'D', 'G', 'J', 'K'],
                        'layout_code' => '2-2-2',
                        'exit_rows' => null,
                        'extra_price_exit' => 0,
                    ]
                );
            }
            if ($a330 && $economy) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $garuda->id, 'aircraft_id' => $a330->id, 'travel_class_id' => $economy->id],
                    [
                        'start_row' => 9, 'end_row' => 44,
                        'columns' => ['A', 'C', 'D', 'E', 'F', 'G', 'H', 'K'],
                        'layout_code' => '2-4-2',
                        'exit_rows' => [20, 21, 33],
                        'extra_price_exit' => 175000,
                    ]
                );
            }
        }

        // ─── Lion Air (JT) ────────────────────────────────────────────────────
        // LCC; minimal business-style rows, high-density economy.
        $lion = Airline::where('iata_code', 'JT')->first();
        if ($lion) {
            // B737-800: 2 rows "Business" (2-2) + 32 rows Economy (3-3)
            if ($b737 && $business) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $lion->id, 'aircraft_id' => $b737->id, 'travel_class_id' => $business->id],
                    [
                        'start_row' => 1, 'end_row' => 2,
                        'columns' => ['A', 'C', 'D', 'F'],
                        'layout_code' => '2-2',
                        'exit_rows' => null,
                        'extra_price_exit' => 0,
                    ]
                );
            }
            if ($b737 && $economy) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $lion->id, 'aircraft_id' => $b737->id, 'travel_class_id' => $economy->id],
                    [
                        'start_row' => 3, 'end_row' => 34,
                        'columns' => ['A', 'B', 'C', 'D', 'E', 'F'],
                        'layout_code' => '3-3',
                        'exit_rows' => [15, 16],
                        'extra_price_exit' => 100000,
                    ]
                );
            }
        }

        // ─── AirAsia Indonesia (QZ) ───────────────────────────────────────────
        // Pure LCC; all-economy A320 with no business cabin.
        $airasia = Airline::where('iata_code', 'QZ')->first();
        if ($airasia) {
            if ($a320 && $economy) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $airasia->id, 'aircraft_id' => $a320->id, 'travel_class_id' => $economy->id],
                    [
                        'start_row' => 1, 'end_row' => 32,
                        'columns' => ['A', 'B', 'C', 'D', 'E', 'F'],
                        'layout_code' => '3-3',
                        'exit_rows' => [12, 13],
                        'extra_price_exit' => 100000,
                    ]
                );
            }
        }

        // ─── Citilink (QG) ────────────────────────────────────────────────────
        // LCC subsidiary of Garuda; all-economy A320/A321.
        $citilink = Airline::where('iata_code', 'QG')->first();
        if ($citilink) {
            if ($a320 && $economy) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $citilink->id, 'aircraft_id' => $a320->id, 'travel_class_id' => $economy->id],
                    [
                        'start_row' => 1, 'end_row' => 30,
                        'columns' => ['A', 'B', 'C', 'D', 'E', 'F'],
                        'layout_code' => '3-3',
                        'exit_rows' => [12, 13],
                        'extra_price_exit' => 100000,
                    ]
                );
            }
            if ($a321 && $economy) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $citilink->id, 'aircraft_id' => $a321->id, 'travel_class_id' => $economy->id],
                    [
                        'start_row' => 1, 'end_row' => 40,
                        'columns' => ['A', 'B', 'C', 'D', 'E', 'F'],
                        'layout_code' => '3-3',
                        'exit_rows' => [16, 17],
                        'extra_price_exit' => 100000,
                    ]
                );
            }
        }

        // ─── Batik Air (ID) ───────────────────────────────────────────────────
        // Full-service LCC (part of Lion Group); offers proper business cabin.
        $batik = Airline::where('iata_code', 'ID')->first();
        if ($batik) {
            // B737-800: 4 rows Business (2-2) + 30 rows Economy (3-3)
            if ($b737 && $business) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $batik->id, 'aircraft_id' => $b737->id, 'travel_class_id' => $business->id],
                    [
                        'start_row' => 1, 'end_row' => 4,
                        'columns' => ['A', 'C', 'D', 'F'],
                        'layout_code' => '2-2',
                        'exit_rows' => null,
                        'extra_price_exit' => 0,
                    ]
                );
            }
            if ($b737 && $economy) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $batik->id, 'aircraft_id' => $b737->id, 'travel_class_id' => $economy->id],
                    [
                        'start_row' => 5, 'end_row' => 34,
                        'columns' => ['A', 'B', 'C', 'D', 'E', 'F'],
                        'layout_code' => '3-3',
                        'exit_rows' => [16, 17],
                        'extra_price_exit' => 125000,
                    ]
                );
            }
            // A320: 3 rows Business (2-2) + 28 rows Economy (3-3)
            if ($a320 && $business) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $batik->id, 'aircraft_id' => $a320->id, 'travel_class_id' => $business->id],
                    [
                        'start_row' => 1, 'end_row' => 3,
                        'columns' => ['A', 'C', 'D', 'F'],
                        'layout_code' => '2-2',
                        'exit_rows' => null,
                        'extra_price_exit' => 0,
                    ]
                );
            }
            if ($a320 && $economy) {
                AirlineCabinProfile::updateOrCreate(
                    ['airline_id' => $batik->id, 'aircraft_id' => $a320->id, 'travel_class_id' => $economy->id],
                    [
                        'start_row' => 4, 'end_row' => 31,
                        'columns' => ['A', 'B', 'C', 'D', 'E', 'F'],
                        'layout_code' => '3-3',
                        'exit_rows' => [13, 14],
                        'extra_price_exit' => 125000,
                    ]
                );
            }
        }
    }
}
