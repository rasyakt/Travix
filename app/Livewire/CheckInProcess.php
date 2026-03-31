<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Models\CheckIn;
use App\Models\BoardingPass;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CheckInProcess extends Component
{
    public $booking;
    public $passengers = [];
    public $selectedPassengers = [];
    public $canCheckIn = false;
    public $checkInBlockedReason = null;

    public function mount($bookingId)
    {
        $this->booking = Booking::with([
            'flights.schedule.originAirport',
            'flights.schedule.destinationAirport',
            'passengers.seatAssignment.seatMap',
            'passengers.boardingPass',
            'payment',
        ])->findOrFail($bookingId);

        $this->passengers = $this->booking->passengers;

        $this->checkInBlockedReason = $this->booking->check_in_blocked_reason;
        $this->canCheckIn = $this->checkInBlockedReason === null;
    }

    public function togglePassenger($passengerId)
    {
        if (in_array($passengerId, $this->selectedPassengers)) {
            $this->selectedPassengers = array_diff($this->selectedPassengers, [$passengerId]);
        } else {
            $this->selectedPassengers[] = $passengerId;
        }
    }

    public function processCheckIn()
    {
        if (empty($this->selectedPassengers)) {
            session()->flash('error', 'Please select at least one passenger to check in.');
            return;
        }

        if (!$this->canCheckIn) {
            session()->flash('error', $this->checkInBlockedReason ?? 'Check-in belum tersedia untuk booking ini.');
            return;
        }

        try {
            DB::beginTransaction();

            foreach ($this->selectedPassengers as $passengerId) {
                $passenger = $this->passengers->find($passengerId);

                if (!$passenger) {
                    continue;
                }

                // Check if already checked in
                if ($passenger->checkIn) {
                    continue;
                }

                // Check if seat is assigned
                if (!$passenger->seatAssignment) {
                    session()->flash('error', "Passenger {$passenger->first_name} {$passenger->last_name} doesn't have a seat assigned.");
                    DB::rollBack();
                    return;
                }

                // Create check-in record
                $flight = $this->booking->flights->first();
                // Note: The unique index on booking_id/flight_id suggests one check-in record per flight per booking
                $checkIn = CheckIn::firstOrCreate(
                    [
                        'booking_id' => $this->booking->id,
                        'flight_id' => $flight->id,
                    ],
                    [
                        'checked_in_at' => now(),
                        'check_in_method' => 'online',
                        'status' => 'completed',
                    ]
                );

                // Generate boarding pass
                $boardingPassData = [
                    'booking_code' => $this->booking->booking_code,
                    'passenger_name' => $passenger->first_name . ' ' . $passenger->last_name,
                    'flight_number' => $flight->flight_number,
                    'seat_number' => $passenger->seatAssignment->seatMap->seat_number,
                    'departure_time' => $flight->departure_datetime->format('Y-m-d H:i'),
                    'origin' => $flight->schedule->originAirport->iata_code,
                    'destination' => $flight->schedule->destinationAirport->iata_code,
                ];

                // Generate QR code
                $qrCode = base64_encode(QrCode::format('png')->size(200)->generate(json_encode($boardingPassData)));

                BoardingPass::create([
                    'check_in_id' => $checkIn->id,
                    'booking_passenger_id' => $passenger->id,
                    'boarding_pass_number' => 'BP-' . strtoupper(\Illuminate\Support\Str::random(10)),
                    'seat_number' => $passenger->seatAssignment->seatMap->seat_number,
                    'barcode' => $this->booking->booking_code . '-' . $passenger->id,
                    'qr_code_data' => $qrCode,
                    'gate' => 'Gate ' . rand(1, 20),
                    'boarding_time' => $flight->departure_datetime->copy()->subMinutes(45),
                    'generated_at' => now(),
                    'status' => 'active',
                ]);
            }

            DB::commit();

            session()->flash('success', 'Check-in completed successfully!');
            return redirect()->route('booking.show', $this->booking->id);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to complete check-in. Please try again.');
            \Log::error('Check-in Error', ['message' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.check-in-process');
    }
}
