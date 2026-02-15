<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Models\SeatMap;
use App\Models\SeatAssignment;
use App\Models\TravelClass;
use App\Models\FlightSeatPrice;
use Illuminate\Support\Facades\DB;

class SeatSelection extends Component
{
    public $booking;
    public $flight;
    public $seatMap = [];
    public $selectedSeats = [];
    public $passengers = [];
    public $travelClasses = [];
    public $selectedTravelClassId;
    public $pricePerSeat = 0;

    public function mount($bookingId)
    {
        $this->booking = Booking::with(['passengers'])->findOrFail($bookingId);
        $this->flight = $this->booking->flight;
        $this->passengers = $this->booking->passengers;

        if (!$this->flight) {
            session()->flash('error', 'No flight found for this booking.');
            return redirect()->route('dashboard');
        }

        // Load available travel classes with pricing
        $this->loadTravelClasses();

        // Set default class if not selected
        if (!$this->selectedTravelClassId && !empty($this->travelClasses)) {
            $this->selectedTravelClassId = $this->travelClasses[0]['id'];
        }
    }

    public function loadTravelClasses()
    {
        $aircraftId = $this->flight->schedule->aircraft_id;

        // Get flight seat prices
        $flightSeatPrices = FlightSeatPrice::where('flight_id', $this->flight->id)
            ->with('travelClass')
            ->get();

        $this->travelClasses = $flightSeatPrices->map(function ($fsp) {
            return [
                'id' => $fsp->travel_class_id,
                'name' => $fsp->travelClass->name,
                'code' => $fsp->travelClass->code,
                'price' => $fsp->price,
                'available_seats' => $fsp->available_seats,
            ];
        })->toArray();
    }

    public function updatedSelectedTravelClassId()
    {
        // Clear selected seats when class changes
        $this->selectedSeats = [];
        $this->loadSeatMap();
        $this->calculatePrice();
    }

    public function loadSeatMap()
    {
        if (!$this->selectedTravelClassId) {
            return;
        }

        $aircraftId = $this->flight->schedule->aircraft_id;

        // Get seats for this aircraft and travel class
        $seats = SeatMap::where('aircraft_id', $aircraftId)
            ->where('travel_class_id', $this->selectedTravelClassId)
            ->orderBy('row_number')
            ->orderBy('column_letter')
            ->get();

        // Get occupied seats for this flight
        $occupiedSeats = SeatAssignment::whereHas('bookingPassenger.booking', function ($query) {
            $query->where('flight_id', $this->flight->id);
        })
            ->pluck('seat_map_id')
            ->toArray();

        // Organize seats by row
        $this->seatMap = $seats->groupBy('row_number')->map(function ($rowSeats) use ($occupiedSeats) {
            return $rowSeats->map(function ($seat) use ($occupiedSeats) {
                return [
                    'id' => $seat->id,
                    'number' => $seat->seat_number,
                    'row' => $seat->row_number,
                    'column' => $seat->column_letter,
                    'position' => $seat->position,
                    'is_exit_row' => $seat->is_exit_row,
                    'extra_price' => $seat->extra_price,
                    'is_occupied' => in_array($seat->id, $occupiedSeats),
                    'is_available' => $seat->is_available && !in_array($seat->id, $occupiedSeats),
                ];
            })->values();
        })->toArray();
    }

    public function selectSeat($seatId)
    {
        // Find the seat
        $seat = null;
        foreach ($this->seatMap as $row) {
            foreach ($row as $s) {
                if ($s['id'] == $seatId) {
                    $seat = $s;
                    break 2;
                }
            }
        }

        if (!$seat || !$seat['is_available']) {
            session()->flash('error', 'This seat is not available.');
            return;
        }

        // Check if already selected
        if (in_array($seatId, $this->selectedSeats)) {
            // Deselect
            $this->selectedSeats = array_values(array_diff($this->selectedSeats, [$seatId]));
        } else {
            // Check if we need more seats
            if (count($this->selectedSeats) >= count($this->passengers)) {
                session()->flash('error', 'All passengers already have seats assigned.');
                return;
            }

            $this->selectedSeats[] = $seatId;
        }

        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        if (empty($this->travelClasses) || !$this->selectedTravelClassId) {
            $this->pricePerSeat = 0;
            return;
        }

        $selectedClass = collect($this->travelClasses)->firstWhere('id', $this->selectedTravelClassId);
        $this->pricePerSeat = $selectedClass['price'] ?? 0;
    }

    public function confirmSeats()
    {
        if (count($this->selectedSeats) !== count($this->passengers)) {
            session()->flash('error', 'Please select seats for all ' . count($this->passengers) . ' passenger(s).');
            return;
        }

        try {
            DB::beginTransaction();

            foreach ($this->selectedSeats as $index => $seatId) {
                $passenger = $this->passengers[$index];

                SeatAssignment::create([
                    'booking_passenger_id' => $passenger->id,
                    'seat_map_id' => $seatId,
                ]);
            }

            DB::commit();

            session()->flash('success', 'Seats assigned successfully!');
            return redirect()->route('booking.payment', $this->booking->id);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to assign seats. Please try again.');
            \Log::error('Seat Assignment Error', ['message' => $e->getMessage()]);
        }
    }

    public function render()
    {
        if ($this->selectedTravelClassId && empty($this->seatMap)) {
            $this->loadSeatMap();
        }

        return view('livewire.seat-selection');
    }
}
