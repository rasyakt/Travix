<?php

namespace Database\Seeders;

use App\Models\AircraftManufacturer;
use App\Models\Aircraft;
use App\Models\AircraftInstance;
use App\Models\Airline;
use Illuminate\Database\Seeder;

class AircraftSeeder extends Seeder
{
    public function run(): void
    {
        // Manufacturers
        $boeing = AircraftManufacturer::create([
            'name' => 'Boeing',
            'country' => 'United States',
        ]);

        $airbus = AircraftManufacturer::create([
            'name' => 'Airbus',
            'country' => 'Europe',
        ]);

        // Aircraft Models
        $b737 = Aircraft::create([
            'manufacturer_id' => $boeing->id,
            'model' => '737-800',
            'iata_code' => '738',
            'typical_seating_capacity' => 189,
            'max_range_km' => 5436,
            'cruise_speed_kmh' => 842,
        ]);

        $a320 = Aircraft::create([
            'manufacturer_id' => $airbus->id,
            'model' => 'A320-200',
            'iata_code' => '320',
            'typical_seating_capacity' => 180,
            'max_range_km' => 6150,
            'cruise_speed_kmh' => 840,
        ]);

        $b777 = Aircraft::create([
            'manufacturer_id' => $boeing->id,
            'model' => '777-300ER',
            'iata_code' => '77W',
            'typical_seating_capacity' => 396,
            'max_range_km' => 13649,
            'cruise_speed_kmh' => 905,
        ]);

        $a330 = Aircraft::create([
            'manufacturer_id' => $airbus->id,
            'model' => 'A330-300',
            'iata_code' => '333',
            'typical_seating_capacity' => 335,
            'max_range_km' => 11750,
            'cruise_speed_kmh' => 871,
        ]);

        $a321 = Aircraft::create([
            'manufacturer_id' => $airbus->id,
            'model' => 'A321neo',
            'iata_code' => '32Q',
            'typical_seating_capacity' => 220,
            'max_range_km' => 7400,
            'cruise_speed_kmh' => 840,
        ]);

        // Aircraft Instances
        $airlines = Airline::all();

        if ($airlines->isNotEmpty()) {
            // Garuda Indonesia Fleet
            $garudaId = $airlines->where('iata_code', 'GA')->first()?->id;
            if ($garudaId) {
                AircraftInstance::create([
                    'aircraft_id' => $b737->id,
                    'airline_id' => $garudaId,
                    'registration_number' => 'PK-GFA',
                    'manufacture_year' => 2018,
                ]);
                AircraftInstance::create([
                    'aircraft_id' => $b737->id,
                    'airline_id' => $garudaId,
                    'registration_number' => 'PK-GFB',
                    'manufacture_year' => 2019,
                ]);
                AircraftInstance::create([
                    'aircraft_id' => $b777->id,
                    'airline_id' => $garudaId,
                    'registration_number' => 'PK-GIA',
                    'manufacture_year' => 2015,
                ]);
                AircraftInstance::create([
                    'aircraft_id' => $a330->id,
                    'airline_id' => $garudaId,
                    'registration_number' => 'PK-GPA',
                    'manufacture_year' => 2016,
                ]);
            }

            // Lion Air Fleet
            $lionId = $airlines->where('iata_code', 'JT')->first()?->id;
            if ($lionId) {
                AircraftInstance::create([
                    'aircraft_id' => $b737->id,
                    'airline_id' => $lionId,
                    'registration_number' => 'PK-LQA',
                    'manufacture_year' => 2017,
                ]);
                AircraftInstance::create([
                    'aircraft_id' => $b737->id,
                    'airline_id' => $lionId,
                    'registration_number' => 'PK-LQB',
                    'manufacture_year' => 2018,
                ]);
            }

            // AirAsia Fleet
            $airasiaId = $airlines->where('iata_code', 'QZ')->first()?->id;
            if ($airasiaId) {
                AircraftInstance::create([
                    'aircraft_id' => $a320->id,
                    'airline_id' => $airasiaId,
                    'registration_number' => 'PK-AXA',
                    'manufacture_year' => 2016,
                ]);
                AircraftInstance::create([
                    'aircraft_id' => $a320->id,
                    'airline_id' => $airasiaId,
                    'registration_number' => 'PK-AXB',
                    'manufacture_year' => 2017,
                ]);
            }

            // Citilink Fleet
            $citilinkId = $airlines->where('iata_code', 'QG')->first()?->id;
            if ($citilinkId) {
                AircraftInstance::create([
                    'aircraft_id' => $a320->id,
                    'airline_id' => $citilinkId,
                    'registration_number' => 'PK-GLA',
                    'manufacture_year' => 2019,
                ]);
                AircraftInstance::create([
                    'aircraft_id' => $a321->id,
                    'airline_id' => $citilinkId,
                    'registration_number' => 'PK-GLB',
                    'manufacture_year' => 2020,
                ]);
            }

            // Batik Air Fleet
            $batikId = $airlines->where('iata_code', 'ID')->first()?->id;
            if ($batikId) {
                AircraftInstance::create([
                    'aircraft_id' => $b737->id,
                    'airline_id' => $batikId,
                    'registration_number' => 'PK-BTA',
                    'manufacture_year' => 2018,
                ]);
                AircraftInstance::create([
                    'aircraft_id' => $a320->id,
                    'airline_id' => $batikId,
                    'registration_number' => 'PK-BTB',
                    'manufacture_year' => 2019,
                ]);
            }
        }
    }
}
