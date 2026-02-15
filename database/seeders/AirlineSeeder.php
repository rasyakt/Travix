<?php

namespace Database\Seeders;

use App\Models\Airline;
use Illuminate\Database\Seeder;

class AirlineSeeder extends Seeder
{
    public function run(): void
    {
        $airlines = [
            [
                'iata_code' => 'GA',
                'icao_code' => 'GIA',
                'name' => 'Garuda Indonesia',
                'country' => 'ID',
            ],
            [
                'iata_code' => 'JT',
                'icao_code' => 'LNI',
                'name' => 'Lion Air',
                'country' => 'ID',
            ],
            [
                'iata_code' => 'QZ',
                'icao_code' => 'AWQ',
                'name' => 'AirAsia Indonesia',
                'country' => 'ID',
            ],
            [
                'iata_code' => 'QG',
                'icao_code' => 'CTV',
                'name' => 'Citilink',
                'country' => 'ID',
            ],
            [
                'iata_code' => 'ID',
                'icao_code' => 'BTK',
                'name' => 'Batik Air',
                'country' => 'ID',
            ],
            [
                'iata_code' => 'SJ',
                'icao_code' => 'SJY',
                'name' => 'Sriwijaya Air',
                'country' => 'ID',
            ],
            [
                'iata_code' => 'IU',
                'icao_code' => 'SWG',
                'name' => 'Super Air Jet',
                'country' => 'ID',
            ],
            [
                'iata_code' => 'SQ',
                'icao_code' => 'SIA',
                'name' => 'Singapore Airlines',
                'country' => 'SG',
            ],
            [
                'iata_code' => 'MH',
                'icao_code' => 'MAS',
                'name' => 'Malaysia Airlines',
                'country' => 'MY',
            ],
            [
                'iata_code' => 'TG',
                'icao_code' => 'THA',
                'name' => 'Thai Airways',
                'country' => 'TH',
            ],
            [
                'iata_code' => 'CX',
                'icao_code' => 'CPA',
                'name' => 'Cathay Pacific',
                'country' => 'HK',
            ],
            [
                'iata_code' => 'EK',
                'icao_code' => 'UAE',
                'name' => 'Emirates',
                'country' => 'AE',
            ],
            [
                'iata_code' => 'QR',
                'icao_code' => 'QTR',
                'name' => 'Qatar Airways',
                'country' => 'QA',
            ],
            [
                'iata_code' => 'AA',
                'icao_code' => 'AAL',
                'name' => 'American Airlines',
                'country' => 'US',
            ],
            [
                'iata_code' => 'DL',
                'icao_code' => 'DAL',
                'name' => 'Delta Air Lines',
                'country' => 'US',
            ],
        ];

        foreach ($airlines as $airline) {
            Airline::updateOrCreate(
                ['iata_code' => $airline['iata_code']],
                $airline
            );
        }
    }
}
