<?php

namespace Database\Seeders;

use App\Models\TravelClass;
use Illuminate\Database\Seeder;

class TravelClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            [
                'name' => 'Economy',
                'code' => 'Y',
                'price_multiplier' => 1.0,
            ],
            [
                'name' => 'Business',
                'code' => 'C',
                'price_multiplier' => 3.0,
            ],
            [
                'name' => 'First Class',
                'code' => 'F',
                'price_multiplier' => 5.0,
            ],
        ];

        foreach ($classes as $class) {
            TravelClass::create($class);
        }
    }
}
