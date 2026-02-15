<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Models\SeatMap;
use App\Models\SeatAssignment;
use App\Models\BookingPassenger;
use App\Enums\SeatStatus;
use Illuminate\Support\Facades\DB;

class SeatSelection extends Component
{
    public $booking;
    public $flight;
    public $seatMap = [];
    public $selectedSeats = [];
    public $passengers = [];
    public $currentPassengerIndex = 0;

    public function mount($bookingId)
    {
        $this->booking = Booking::with(['flights', 'passengers'])->findOrFail($bookingId);
        $this->flight = $this->booking->flights->first();
        $this->passengers = $this->booking->passengers;

        if (!$this->flight) {
            session()->flash('error', 'No flight found for this booking.');
            return redirect()->route('dashboard');
        }

        // Load seat map
        $this->loadSeatMap();
    }

    public function loadSeatMap()
    {
        $aircraftInstance = $this->flight->aircraftInstance;
        
        if (!$aircraftInstance) {
            $this->seatMap = $this->generateDefaultSeatMap();
            return;
        }

        // Get seat map from database
        $seats = SeatMap::where('aircraft_instance_id', $aircraftInstance->id)
            ->orderBy('row_number')
            ->orderBy('seat_letter')
            ->get();

        if ($seats->isEmpty()) {
            $this->seatMap = $this->generateDefaultSeatMap();
            return;
        }

        // Get occupied seats for this flight
        $occupiedSeats = SeatAssignment::whereHas('bookingPassenger.booking.flights', function($query) {
            $query->where('flights.id', $this->flight->id);
        })->pluck('seat_number')->toArray();

        // Organize seats by row
        $this->seatMap = $seats->groupBy('row_number')->map(function($rowSeats) use ($occupiedSeats) {
            return $rowSeats->map(function($seat) use ($occupiedSeats) {
                $seatNumber = $seat->row_number . $seat->seat_letter;
                return [
                    'id' => $seat->id,
                    'number' => $seatNumber,
                    'letter' => $seat->seat_letter,
                    'status' => in_array($seatNumber, $occupiedSeats) ? 
                        SeatStatus::OCCUPIED->value : 
                        $seat->status,
                    'travel_class' => $seat->travelClass->name ?? 'Economy',
                ];
            });
        })->toArray();
    }

    protected function generateDefaultSeatMap()
    {
        // Generate a simple 30-row, 6-seat (A-F) configuration
        $map = [];
        for ($row = 1; $row <= 30; $row++) {
            $seats = [];
            foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $letter) {
                $seats[] = [
                    'number' => $row . $letter,
                    'letter' => $letter,
                    'status' => SeatStatus::AVAILABLE->value,
                    'travel_class' => $row <= 5 ? 'Business' : 'Economy',
                ];
            }
            $map[$row] = $seats;
        }
        return $map;
    }

    public function selectSeat($seatNumber)
    {
        // Check if seat is available
        $isOccupied = false;
        foreach ($this->seatMap as $row) {
            foreach ($row as $seat) {
                if ($seat['number'] === $seatNumber && $seat['status'] === SeatStatus::OCCUPIED->value) {
                    $isOccupied = true;
                    break 2;
                }
            }
        }

        if ($isOccupied) {
            session()->flash('error', 'This seat is already occupied.');
            return;
        }

        // Check if we need more seats
        if (count($this->selectedSeats) >= count($this->passengers)) {
            session()->flash('error', 'All passengers already have seats assigned.');
            return;
        }

        // Add seat to selection
        if (!in_array($seatNumber, $this->selectedSeats)) {
            $this->selectedSeats[] = $seatNumber;
            $this->currentPassengerIndex = min($this->currentPassengerIndex + 1, count($this->passengers) - 1);
        }
    }

    public function removeSeat($index)
    {
        unset($this->selectedSeats[$index]);
        $this->selectedSeats = array_values($this->selectedSeats);
        $this->currentPassengerIndex = max(0, count($this->selectedSeats) - 1);
    }

    public function confirmSeats()
    {
        if (count($this->selectedSeats) !== count($this->passengers)) {
            session()->flash('error', 'Please select seats for all passengers.');
            return;
        }

        try {
            DB::beginTransaction();

            foreach ($this->selectedSeats as $index => $seatNumber) {
                $passenger = $this->passengers[$index];
                
                SeatAssignment::create([
                    'booking_passenger_id' => $passenger->id,
                    'seat_number' => $seatNumber,
                    'assigned_at' => now(),
                ]);
            }

            DB::commit();

            session()->flash('success', 'Seats assigned successfully!');
            return redirect()->route('booking.show', $this->booking->id);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to assign seats. Please try again.');
            \Log::error('Seat Assignment Error', ['message' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.seat-selection');
    }
}
