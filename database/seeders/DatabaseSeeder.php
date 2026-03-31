<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AirlineSeeder::class,
            AirportSeeder::class,
            AircraftSeeder::class,
            TravelClassSeeder::class,
            AirlineCabinProfileSeeder::class,
            SeatMapSeeder::class,
            ScheduleSeeder::class,
            FlightSeeder::class,
            FlightSeatPriceSeeder::class,
        ]);
    }
}
