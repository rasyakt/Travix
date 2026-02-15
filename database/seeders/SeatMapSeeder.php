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

        // Boeing 737-800 Configuration
        if ($b737 && $economy && $business) {
            // Business: 4 rows (1-4), 2-2 layout (A-C, D-F)
            $this->generateSeats($b737->id, $business->id, 1, 4, ['A', 'C', 'D', 'F'], '2-2');
            // Economy: 31 rows (5-35), 3-3 layout (A-F)
            $this->generateSeats($b737->id, $economy->id, 5, 35, ['A', 'B', 'C', 'D', 'E', 'F'], '3-3', [15, 16]);
        }

        // Airbus A320 Configuration
        if ($a320 && $economy && $business) {
            // Business: 4 rows (1-4), 2-2 layout
            $this->generateSeats($a320->id, $business->id, 1, 4, ['A', 'C', 'D', 'F'], '2-2');
            // Economy: 28 rows (5-32), 3-3 layout
            $this->generateSeats($a320->id, $economy->id, 5, 32, ['A', 'B', 'C', 'D', 'E', 'F'], '3-3', [12, 13]);
        }

        // Boeing 777-300ER Configuration
        if ($b777 && $economy && $business && $first) {
            // First: 2 rows (1-2), 1-2-1 layout (A, D-G, K)
            $this->generateSeats($b777->id, $first->id, 1, 2, ['A', 'D', 'G', 'K'], '1-2-1');
            // Business: 10 rows (3-12), 2-4-2 layout (A-C, D-G, H-K) - using 2-3-2 logic or similar
            $this->generateSeats($b777->id, $business->id, 3, 12, ['A', 'B', 'D', 'E', 'F', 'G', 'J', 'K'], '2-4-2');
            // Economy: 38 rows (13-50), 3-4-3 layout
            $this->generateSeats($b777->id, $economy->id, 13, 50, ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K'], '3-4-3', [25, 26]);
        }

        // Airbus A330 Configuration
        if ($a330 && $economy && $business) {
            // Business: 8 rows (1-8), 2-2-2 layout
            $this->generateSeats($a330->id, $business->id, 1, 8, ['A', 'B', 'D', 'G', 'J', 'K'], '2-2-2');
            // Economy: 37 rows (9-45), 2-4-2 layout
            $this->generateSeats($a330->id, $economy->id, 9, 45, ['A', 'C', 'D', 'E', 'F', 'G', 'H', 'K'], '2-4-2', [20, 21]);
        }

        // Airbus A321neo Configuration
        if ($a321 && $economy && $business) {
            // Business: 3 rows (1-3), 2-2 layout
            $this->generateSeats($a321->id, $business->id, 1, 3, ['A', 'C', 'D', 'F'], '2-2');
            // Economy: 37 rows (4-40), 3-3 layout
            $this->generateSeats($a321->id, $economy->id, 4, 40, ['A', 'B', 'C', 'D', 'E', 'F'], '3-3', [18, 19]);
        }
    }

    private function generateSeats(int $aircraftId, int $travelClassId, int $startRow, int $endRow, array $cols, string $layout, array $exitRows = []): void
    {
        for ($row = $startRow; $row <= $endRow; $row++) {
            foreach ($cols as $index => $col) {
                $position = 'middle';
                if ($index === 0 || $index === count($cols) - 1) {
                    $position = 'window';
                } elseif ($this->isAisle($index, $cols, $layout)) {
                    $position = 'aisle';
                }

                SeatMap::create([
                    'aircraft_id' => $aircraftId,
                    'travel_class_id' => $travelClassId,
                    'seat_number' => $row . $col,
                    'row_number' => $row,
                    'column_letter' => $col,
                    'position' => $position,
                    'is_exit_row' => in_array($row, $exitRows),
                    'is_available' => true,
                    'extra_price' => in_array($row, $exitRows) ? 150000 : 0,
                ]);
            }
        }
    }

    private function isAisle(int $index, array $cols, string $layout): bool
    {
        // Simple logic for aisle seats based on layout strings like '2-2', '3-3', '2-4-2'
        $parts = explode('-', $layout);
        $currentIndex = 0;
        foreach ($parts as $part) {
            $partSize = (int) $part;
            if ($index === $currentIndex || $index === $currentIndex + $partSize - 1) {
                // If it's the start or end of a block AND not a window (window handled separately)
                if ($index !== 0 && $index !== count($cols) - 1) {
                    return true;
                }
            }
            $currentIndex += $partSize;
        }
        return false;
    }
}
