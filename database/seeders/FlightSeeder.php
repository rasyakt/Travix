<?php

namespace Database\Seeders;

use App\Models\SeatMap;
use App\Models\Flight;
use App\Models\Schedule;
use App\Models\AircraftInstance;
use App\Models\Aircraft;
use App\Enums\FlightStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = Schedule::with(['originAirport', 'destinationAirport', 'airline', 'aircraft'])->get();

        // Generate flights for next 30 days
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(30);

        foreach ($schedules as $schedule) {
            /** @var \App\Models\Schedule $schedule */
            $currentDate = $startDate->copy();

            // Find an instance of this aircraft for this airline
            $aircraftInstanceId = AircraftInstance::where('airline_id', $schedule->airline_id)
                ->where('aircraft_id', $schedule->aircraft_id)
                ->first()?->id;

            // Get total seats count for this aircraft
            $totalSeats = SeatMap::where('aircraft_id', $schedule->aircraft_id)->count();
            if ($totalSeats === 0) {
                $totalSeats = $schedule->aircraft?->typical_seating_capacity ?? 180;
            }

            while ($currentDate->lte($endDate)) {
                $operatingDays = $schedule->operating_days ?? [];
                $dayOfWeek = $currentDate->dayOfWeekIso; // 1=Monday, 7=Sunday

                if (in_array($dayOfWeek, $operatingDays)) {
                    $this->createFlight($schedule, $currentDate, $aircraftInstanceId, $totalSeats);
                }

                $currentDate->addDay();
            }
        }
    }

    private function createFlight(Schedule $schedule, Carbon $date, ?int $aircraftInstanceId, int $totalSeats): void
    {
        $departureDateTime = $date->copy()->setTimeFromTimeString($schedule->departure_time->format('H:i:s'));
        $arrivalDateTime = $date->copy()->setTimeFromTimeString($schedule->arrival_time->format('H:i:s'));

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

        Flight::updateOrCreate(
            [
                'schedule_id' => $schedule->id,
                'flight_date' => $date->toDateString(),
            ],
            [
                'aircraft_instance_id' => $aircraftInstanceId,
                'flight_number' => $schedule->flight_number,
                'departure_datetime' => $departureDateTime,
                'arrival_datetime' => $arrivalDateTime,
                'status' => $status,
                'available_seats' => $totalSeats,
                'current_price' => $schedule->base_price,
                'gate' => $this->generateGate(),
                'terminal' => $this->generateTerminal($schedule->origin_airport_id),
            ]
        );
    }

    private function generateGate(): string
    {
        $gates = ['A1', 'A2', 'A3', 'A4', 'A5', 'B1', 'B2', 'B3', 'B4', 'B5', 'C1', 'C2', 'C3', 'D1', 'D2', 'E1', 'E2'];
        return $gates[array_rand($gates)];
    }

    private function generateTerminal(?int $airportId): string
    {
        $terminals = ['1', '2', '3'];
        return $terminals[array_rand($terminals)];
    }
}