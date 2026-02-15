<?php

namespace Database\Seeders;

use App\Models\Flight;
use App\Models\Schedule;
use App\Enums\FlightStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = Schedule::with(['originAirport', 'destinationAirport', 'airline'])->get();

        // Generate flights for next 30 days
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(30);

        foreach ($schedules as $schedule) {
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                $recurringDays = json_decode($schedule->recurring_days, true) ?? [];
                $dayOfWeek = $currentDate->dayOfWeekIso; // 1=Monday, 7=Sunday

                if (in_array($dayOfWeek, $recurringDays)) {
                    $this->createFlight($schedule, $currentDate);
                }

                $currentDate->addDay();
            }
        }
    }

    private function createFlight(Schedule $schedule, Carbon $date): void
    {
        $departureDateTime = $date->copy()->setTimeFromTimeString($schedule->departure_time);
        $arrivalDateTime = $date->copy()->setTimeFromTimeString($schedule->arrival_time);

        // If arrival time is before departure time, arrival is next day
        if ($arrivalDateTime->lt($departureDateTime)) {
            $arrivalDateTime->addDay();
        }

        // Determine flight status based on date
        $now = Carbon::now();
        $status = FlightStatus::SCHEDULED;

        if ($departureDateTime->lt($now) && $arrivalDateTime->gt($now)) {
            $status = FlightStatus::ACTIVE;
        } elseif ($arrivalDateTime->lt($now)) {
            $status = FlightStatus::LANDED;
        }

        Flight::create([
            'schedule_id' => $schedule->id,
            'airline_id' => $schedule->airline_id,
            'aircraft_instance_id' => $schedule->aircraft_instance_id,
            'origin_airport_id' => $schedule->origin_airport_id,
            'destination_airport_id' => $schedule->destination_airport_id,
            'flight_number' => $schedule->flight_number,
            'departure_time' => $departureDateTime,
            'arrival_time' => $arrivalDateTime,
            'status' => $status,
            'gate' => $this->generateGate(),
            'terminal' => $this->generateTerminal($schedule->origin_airport_id),
        ]);
    }

    private function generateGate(): string
    {
        $gates = [
            'A1',
            'A2',
            'A3',
            'A4',
            'A5',
            'B1',
            'B2',
            'B3',
            'B4',
            'B5',
            'C1',
            'C2',
            'C3',
            'D1',
            'D2',
            'E1',
            'E2'
        ];

        return $gates[array_rand($gates)];
    }

    private function generateTerminal(?int $airportId): string
    {
        $terminals = ['1', '2', '3'];
        return $terminals[array_rand($terminals)];
    }
}
