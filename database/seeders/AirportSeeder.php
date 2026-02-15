<?php

namespace Database\Seeders;

use App\Models\Airport;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    public function run(): void
    {
        $airports = [
            [
                'iata_code' => 'CGK',
                'icao_code' => 'WIII',
                'name' => 'Soekarno-Hatta International Airport',
                'city' => 'Jakarta',
                'country_code' => 'ID',
                'latitude' => -6.1255,
                'longitude' => 106.6559,
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'iata_code' => 'DPS',
                'icao_code' => 'WADD',
                'name' => 'Ngurah Rai International Airport',
                'city' => 'Denpasar',
                'country_code' => 'ID',
                'latitude' => -8.7467,
                'longitude' => 115.1667,
                'timezone' => 'Asia/Makassar',
            ],
            [
                'iata_code' => 'SUB',
                'icao_code' => 'WARR',
                'name' => 'Juanda International Airport',
                'city' => 'Surabaya',
                'country_code' => 'ID',
                'latitude' => -7.3798,
                'longitude' => 112.7869,
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'iata_code' => 'JOG',
                'icao_code' => 'WAHH',
                'name' => 'Adisucipto International Airport',
                'city' => 'Yogyakarta',
                'country_code' => 'ID',
                'latitude' => -7.7886,
                'longitude' => 110.4317,
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'iata_code' => 'UPG',
                'icao_code' => 'WAAA',
                'name' => 'Sultan Hasanuddin International Airport',
                'city' => 'Makassar',
                'country_code' => 'ID',
                'latitude' => -5.0614,
                'longitude' => 119.5542,
                'timezone' => 'Asia/Makassar',
            ],
            [
                'iata_code' => 'KNO',
                'icao_code' => 'WIMM',
                'name' => 'Kualanamu International Airport',
                'city' => 'Medan',
                'country_code' => 'ID',
                'latitude' => 3.6422,
                'longitude' => 98.8853,
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'iata_code' => 'BDO',
                'icao_code' => 'WICC',
                'name' => 'Husein Sastranegara International Airport',
                'city' => 'Bandung',
                'country_code' => 'ID',
                'latitude' => -6.9006,
                'longitude' => 107.5764,
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'iata_code' => 'PLM',
                'icao_code' => 'WIPP',
                'name' => 'Sultan Mahmud Badaruddin II International Airport',
                'city' => 'Palembang',
                'country_code' => 'ID',
                'latitude' => -2.8976,
                'longitude' => 104.6998,
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'iata_code' => 'BTH',
                'icao_code' => 'WIDD',
                'name' => 'Hang Nadim International Airport',
                'city' => 'Batam',
                'country_code' => 'ID',
                'latitude' => 1.1210,
                'longitude' => 104.1186,
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'iata_code' => 'PKU',
                'icao_code' => 'WIBB',
                'name' => 'Sultan Syarif Kasim II International Airport',
                'city' => 'Pekanbaru',
                'country_code' => 'ID',
                'latitude' => 0.4607,
                'longitude' => 101.4446,
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'iata_code' => 'SIN',
                'icao_code' => 'WSSS',
                'name' => 'Singapore Changi Airport',
                'city' => 'Singapore',
                'country_code' => 'SG',
                'latitude' => 1.3644,
                'longitude' => 103.9915,
                'timezone' => 'Asia/Singapore',
            ],
            [
                'iata_code' => 'KUL',
                'icao_code' => 'WMKK',
                'name' => 'Kuala Lumpur International Airport',
                'city' => 'Kuala Lumpur',
                'country_code' => 'MY',
                'latitude' => 2.7456,
                'longitude' => 101.7099,
                'timezone' => 'Asia/Kuala_Lumpur',
            ],
            [
                'iata_code' => 'BKK',
                'icao_code' => 'VTBS',
                'name' => 'Suvarnabhumi Airport',
                'city' => 'Bangkok',
                'country_code' => 'TH',
                'latitude' => 13.6900,
                'longitude' => 100.7501,
                'timezone' => 'Asia/Bangkok',
            ],
            [
                'iata_code' => 'HKG',
                'icao_code' => 'VHHH',
                'name' => 'Hong Kong International Airport',
                'city' => 'Hong Kong',
                'country_code' => 'HK',
                'latitude' => 22.3080,
                'longitude' => 113.9185,
                'timezone' => 'Asia/Hong_Kong',
            ],
            [
                'iata_code' => 'DXB',
                'icao_code' => 'OMDB',
                'name' => 'Dubai International Airport',
                'city' => 'Dubai',
                'country_code' => 'AE',
                'latitude' => 25.2528,
                'longitude' => 55.3644,
                'timezone' => 'Asia/Dubai',
            ],
        ];

        foreach ($airports as $airport) {
            Airport::create($airport);
        }
    }
}
