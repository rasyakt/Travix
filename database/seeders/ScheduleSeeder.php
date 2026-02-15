<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Aircraft;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = $this->getScheduleData();

        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }

    private function getScheduleData(): array
    {
        $cgk = Airport::where('iata_code', 'CGK')->first()?->id;
        $dps = Airport::where('iata_code', 'DPS')->first()?->id;
        $sub = Airport::where('iata_code', 'SUB')->first()?->id;
        $jog = Airport::where('iata_code', 'JOG')->first()?->id;
        $upg = Airport::where('iata_code', 'UPG')->first()?->id;
        $kno = Airport::where('iata_code', 'KNO')->first()?->id;
        $sin = Airport::where('iata_code', 'SIN')->first()?->id;
        $kul = Airport::where('iata_code', 'KUL')->first()?->id;

        $garuda = Airline::where('iata_code', 'GA')->first()?->id;
        $lion = Airline::where('iata_code', 'JT')->first()?->id;
        $airasia = Airline::where('iata_code', 'QZ')->first()?->id;
        $citilink = Airline::where('iata_code', 'QG')->first()?->id;
        $batik = Airline::where('iata_code', 'ID')->first()?->id;

        $b737 = Aircraft::where('model', '737-800')->first()?->id;
        $a320 = Aircraft::where('model', 'A320-200')->first()?->id;

        $validFrom = Carbon::now()->format('Y-m-d');
        $validUntil = Carbon::now()->addYear()->format('Y-m-d');

        return [
            // CGK to DPS (Jakarta to Bali)
            [
                'airline_id' => $garuda,
                'aircraft_id' => $b737,
                'origin_airport_id' => $cgk,
                'destination_airport_id' => $dps,
                'flight_number' => 'GA401',
                'departure_time' => '06:00:00',
                'arrival_time' => '08:55:00',
                'duration_minutes' => 175,
                'operating_days' => json_encode([1, 2, 3, 4, 5, 6, 7]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 950000,
            ],
            [
                'airline_id' => $lion,
                'aircraft_id' => $b737,
                'origin_airport_id' => $cgk,
                'destination_airport_id' => $dps,
                'flight_number' => 'JT901',
                'departure_time' => '09:30:00',
                'arrival_time' => '12:25:00',
                'duration_minutes' => 175,
                'operating_days' => json_encode([1, 2, 3, 4, 5, 6, 7]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 750000,
            ],
            [
                'airline_id' => $airasia,
                'aircraft_id' => $a320,
                'origin_airport_id' => $cgk,
                'destination_airport_id' => $dps,
                'flight_number' => 'QZ501',
                'departure_time' => '13:00:00',
                'arrival_time' => '15:55:00',
                'duration_minutes' => 175,
                'operating_days' => json_encode([1, 2, 3, 4, 5, 6, 7]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 680000,
            ],

            // DPS to CGK (Bali to Jakarta)
            [
                'airline_id' => $garuda,
                'aircraft_id' => $b737,
                'origin_airport_id' => $dps,
                'destination_airport_id' => $cgk,
                'flight_number' => 'GA402',
                'departure_time' => '10:00:00',
                'arrival_time' => '12:50:00',
                'duration_minutes' => 170,
                'operating_days' => json_encode([1, 2, 3, 4, 5, 6, 7]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 950000,
            ],
            [
                'airline_id' => $citilink,
                'aircraft_id' => $a320,
                'origin_airport_id' => $dps,
                'destination_airport_id' => $cgk,
                'flight_number' => 'QG801',
                'departure_time' => '14:30:00',
                'arrival_time' => '17:20:00',
                'duration_minutes' => 170,
                'operating_days' => json_encode([1, 2, 3, 4, 5, 6, 7]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 720000,
            ],

            // CGK to SUB (Jakarta to Surabaya)
            [
                'airline_id' => $lion,
                'aircraft_id' => $b737,
                'origin_airport_id' => $cgk,
                'destination_airport_id' => $sub,
                'flight_number' => 'JT650',
                'departure_time' => '07:00:00',
                'arrival_time' => '08:30:00',
                'duration_minutes' => 90,
                'operating_days' => json_encode([1, 2, 3, 4, 5, 6, 7]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 550000,
            ],
            [
                'airline_id' => $batik,
                'aircraft_id' => $b737,
                'origin_airport_id' => $cgk,
                'destination_airport_id' => $sub,
                'flight_number' => 'ID7100',
                'departure_time' => '11:00:00',
                'arrival_time' => '12:30:00',
                'duration_minutes' => 90,
                'operating_days' => json_encode([1, 3, 5, 7]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 620000,
            ],

            // CGK to JOG (Jakarta to Yogyakarta)
            [
                'airline_id' => $garuda,
                'aircraft_id' => $b737,
                'origin_airport_id' => $cgk,
                'destination_airport_id' => $jog,
                'flight_number' => 'GA205',
                'departure_time' => '08:00:00',
                'arrival_time' => '09:10:00',
                'duration_minutes' => 70,
                'operating_days' => json_encode([1, 2, 3, 4, 5, 6, 7]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 480000,
            ],
            [
                'airline_id' => $airasia,
                'aircraft_id' => $a320,
                'origin_airport_id' => $cgk,
                'destination_airport_id' => $jog,
                'flight_number' => 'QZ301',
                'departure_time' => '12:30:00',
                'arrival_time' => '13:40:00',
                'duration_minutes' => 70,
                'operating_days' => json_encode([1, 2, 3, 4, 5, 6, 7]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 420000,
            ],

            // CGK to UPG (Jakarta to Makassar)
            [
                'airline_id' => $lion,
                'aircraft_id' => $b737,
                'origin_airport_id' => $cgk,
                'destination_airport_id' => $upg,
                'flight_number' => 'JT733',
                'departure_time' => '10:30:00',
                'arrival_time' => '13:45:00',
                'duration_minutes' => 195,
                'operating_days' => json_encode([1, 3, 5]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 1200000,
            ],

            // CGK to KNO (Jakarta to Medan)
            [
                'airline_id' => $garuda,
                'aircraft_id' => $b737,
                'origin_airport_id' => $cgk,
                'destination_airport_id' => $kno,
                'flight_number' => 'GA141',
                'departure_time' => '05:30:00',
                'arrival_time' => '07:45:00',
                'duration_minutes' => 135,
                'operating_days' => json_encode([1, 2, 3, 4, 5, 6, 7]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 850000,
            ],
            [
                'airline_id' => $citilink,
                'aircraft_id' => $a320,
                'origin_airport_id' => $cgk,
                'destination_airport_id' => $kno,
                'flight_number' => 'QG171',
                'departure_time' => '15:00:00',
                'arrival_time' => '17:15:00',
                'duration_minutes' => 135,
                'operating_days' => json_encode([1, 2, 3, 4, 5, 6, 7]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 720000,
            ],

            // International Routes

            // CGK to SIN (Jakarta to Singapore)
            [
                'airline_id' => $garuda,
                'aircraft_id' => $b737,
                'origin_airport_id' => $cgk,
                'destination_airport_id' => $sin,
                'flight_number' => 'GA821',
                'departure_time' => '09:00:00',
                'arrival_time' => '11:45:00',
                'duration_minutes' => 105,
                'operating_days' => json_encode([1, 2, 3, 4, 5, 6, 7]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 1500000,
            ],

            // CGK to KUL (Jakarta to Kuala Lumpur)
            [
                'airline_id' => $airasia,
                'aircraft_id' => $a320,
                'origin_airport_id' => $cgk,
                'destination_airport_id' => $kul,
                'flight_number' => 'QZ201',
                'departure_time' => '16:00:00',
                'arrival_time' => '18:50:00',
                'duration_minutes' => 110,
                'operating_days' => json_encode([1, 2, 3, 4, 5, 6, 7]),
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'base_price' => 1350000,
            ],
        ];
    }
}
