<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Models\SeatMap;
use App\Models\SeatAssignment;
use App\Models\TravelClass;
use App\Models\FlightSeatPrice;
use App\Services\SeatAssignmentService;
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
    public $lockedTravelClassId;
    public $pricePerSeat = 0;

    public function mount($bookingId)
    {
        $this->booking = Booking::with(['passengers', 'bookingFlights'])->findOrFail($bookingId);
        $this->flight = $this->booking->flight;
        $this->passengers = $this->booking->passengers;
        $this->lockedTravelClassId = $this->booking->bookingFlights->first()?->travel_class_id;

        if (!$this->flight) {
            session()->flash('error', 'No flight found for this booking.');
            return redirect()->route('dashboard');
        }

        // Load available travel classes with pricing
        $this->loadTravelClasses();

        // Set default class if not selected
        if ($this->lockedTravelClassId) {
            $this->selectedTravelClassId = (int) $this->lockedTravelClassId;
        } elseif (!$this->selectedTravelClassId && !empty($this->travelClasses)) {
            $this->selectedTravelClassId = $this->travelClasses[0]['id'];
        }

        $existingAssignments = SeatAssignment::where('flight_id', $this->flight->id)
            ->whereIn('booking_passenger_id', $this->passengers->pluck('id'))
            ->get()
            ->keyBy('booking_passenger_id');

        $this->selectedSeats = [];
        foreach ($this->passengers as $passenger) {
            $assigned = $existingAssignments->get($passenger->id);
            if ($assigned) {
                $this->selectedSeats[] = (int) $assigned->seat_map_id;
            }
        }
    }

    public function loadTravelClasses()
    {
        // Get flight seat prices
        $flightSeatPrices = $this->flight->seatPrices()
            ->with('travelClass')
            ->get();

        $classes = $flightSeatPrices->map(function ($fsp) {
            return [
                'id' => $fsp->travel_class_id,
                'name' => $fsp->travelClass->name,
                'code' => $fsp->travelClass->code,
                'price' => $fsp->price,
                'available_seats' => $fsp->available_seats,
            ];
        });

        if ($this->lockedTravelClassId) {
            $classes = $classes->where('id', (int) $this->lockedTravelClassId)->values();
        }

        $this->travelClasses = $classes->toArray();
    }

    public function updatedSelectedTravelClassId()
    {
        if ($this->lockedTravelClassId && (int) $this->selectedTravelClassId !== (int) $this->lockedTravelClassId) {
            $this->selectedTravelClassId = (int) $this->lockedTravelClassId;
            session()->flash('error', 'Kelas perjalanan dikunci sesuai fare yang Anda pilih sebelumnya.');
            return;
        }

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
        $airlineId = $this->flight->schedule->airline_id;

        // Prefer airline-specific seat layout; fall back to generic (airline_id = null).
        $seats = SeatMap::where('aircraft_id', $aircraftId)
            ->where('travel_class_id', $this->selectedTravelClassId)
            ->where('airline_id', $airlineId)
            ->orderBy('row_number')
            ->orderBy('column_letter')
            ->get();
        if ($seats->isEmpty()) {
            $seats = SeatMap::where('aircraft_id', $aircraftId)
                ->where('travel_class_id', $this->selectedTravelClassId)
                ->whereNull('airline_id')
                ->orderBy('row_number')
                ->orderBy('column_letter')
                ->get();
        }

        $currentPassengerIds = $this->passengers->pluck('id')->all();

        // Get occupied seats by other bookings for this flight
        $occupiedSeats = SeatAssignment::where('flight_id', $this->flight->id)
            ->whereNotIn('booking_passenger_id', $currentPassengerIds)
            ->pluck('seat_map_id')
            ->toArray();

        // Seats currently owned by this booking (can still be reselected/replaced)
        $ownedSeats = SeatAssignment::where('flight_id', $this->flight->id)
            ->whereIn('booking_passenger_id', $currentPassengerIds)
            ->pluck('seat_map_id')
            ->toArray();

        // Organize seats by row
        $this->seatMap = $seats->groupBy('row_number')->map(function ($rowSeats) use ($occupiedSeats, $ownedSeats) {
            return $rowSeats->map(function ($seat) use ($occupiedSeats, $ownedSeats) {
                $isOccupiedByOther = in_array($seat->id, $occupiedSeats);
                $isOwned = in_array($seat->id, $ownedSeats);

                return [
                    'id' => $seat->id,
                    'number' => $seat->seat_number,
                    'row' => $seat->row_number,
                    'column' => $seat->column_letter,
                    'position' => $seat->position,
                    'is_exit_row' => $seat->is_exit_row,
                    'extra_price' => $seat->extra_price,
                    'is_occupied' => $isOccupiedByOther,
                    'is_owned' => $isOwned,
                    'is_available' => $seat->is_available && !$isOccupiedByOther,
                ];
            })->values();
        })->toArray();
    }

    public function selectSeat($seatId)
    {
        $this->loadSeatMap();

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

        $currentPassengerIds = $this->passengers->pluck('id')->all();
        $takenByOtherBooking = SeatAssignment::where('flight_id', $this->flight->id)
            ->where('seat_map_id', $seatId)
            ->whereNotIn('booking_passenger_id', $currentPassengerIds)
            ->exists();

        if ($takenByOtherBooking) {
            $this->loadSeatMap();
            session()->flash('error', 'Kursi baru saja dipilih oleh pelanggan lain. Silakan pilih kursi yang tersedia.');
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

    public function refreshSeatAvailability()
    {
        $previousSelectionCount = count($this->selectedSeats);

        $this->loadSeatMap();

        $seatStateById = collect($this->seatMap)
            ->flatten(1)
            ->keyBy('id');

        $this->selectedSeats = array_values(array_filter($this->selectedSeats, function ($seatId) use ($seatStateById) {
            $seat = $seatStateById->get($seatId);

            return $seat && ($seat['is_available'] || ($seat['is_owned'] ?? false));
        }));

        if (count($this->selectedSeats) < $previousSelectionCount) {
            session()->flash('info', 'Beberapa kursi yang Anda pilih tidak lagi tersedia dan telah dilepas dari pilihan Anda.');
        }
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
        if (!$this->selectedTravelClassId) {
            session()->flash('error', 'Please choose a travel class before selecting seats.');
            return;
        }

        if (count($this->selectedSeats) !== count($this->passengers)) {
            session()->flash('error', 'Please select seats for all ' . count($this->passengers) . ' passenger(s).');
            return;
        }

        if (count(array_unique($this->selectedSeats)) !== count($this->selectedSeats)) {
            session()->flash('error', 'Duplicate seat selection detected. Please reselect your seats.');
            return;
        }

        try {
            app(SeatAssignmentService::class)->assignSeats(
                $this->booking,
                (int) $this->selectedTravelClassId,
                array_map('intval', $this->selectedSeats)
            );

            $this->loadSeatMap();
            $this->booking = Booking::with(['payment', 'bookingFlights', 'passengers'])->findOrFail($this->booking->id);

            session()->flash('success', 'Seats assigned successfully!');
            return redirect()->route('booking.payment', $this->booking->id);

        } catch (\Exception $e) {
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
