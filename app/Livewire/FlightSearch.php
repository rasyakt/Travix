<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AviationStackService;
use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Support\Facades\Log;

class FlightSearch extends Component
{
    public $origin = '';
    public $destination = '';
    public $departureDate = '';
    public $passengers = 1;

    public $minimal = false;

    public $searchResults = [];
    public $searching = false;
    public $airports = [];

    protected $rules = [
        'origin' => 'required|string|size:3',
        'destination' => 'required|string|size:3|different:origin',
        'departureDate' => 'required|date|after_or_equal:today',
        'passengers' => 'required|integer|min:1|max:9',
    ];

    protected $messages = [
        'origin.required' => 'Please enter origin airport code',
        'origin.size' => 'Airport code must be 3 characters (IATA code)',
        'destination.required' => 'Please enter destination airport code',
        'destination.size' => 'Airport code must be 3 characters (IATA code)',
        'destination.different' => 'Destination must be different from origin',
        'departureDate.required' => 'Please select departure date',
        'departureDate.after_or_equal' => 'Departure date must be today or later',
        'passengers.required' => 'Please enter number of passengers',
        'passengers.min' => 'At least 1 passenger required',
        'passengers.max' => 'Maximum 9 passengers allowed',
    ];

    public function mount()
    {
        $this->departureDate = now()->format('Y-m-d');
        $this->airports = Airport::select('iata_code', 'name', 'city', 'country')
            ->orderBy('name')
            ->limit(100)
            ->get();
    }

    public function searchFlights()
    {
        $this->validate();

        $this->searching = true;
        $this->searchResults = [];

        try {
            // Search from database first
            $dbFlights = Flight::with([
                'schedule.airline',
                'schedule.originAirport',
                'schedule.destinationAirport',
                'schedule.aircraft'
            ])
                ->whereHas('schedule.originAirport', function ($query) {
                    $query->where('iata_code', strtoupper($this->origin));
                })
                ->whereHas('schedule.destinationAirport', function ($query) {
                    $query->where('iata_code', strtoupper($this->destination));
                })
                ->whereDate('departure_datetime', $this->departureDate)
                ->where('status', '!=', 'cancelled')
                ->get();

            if ($dbFlights->isNotEmpty()) {
                $this->searchResults = $dbFlights->map(function ($flight) {
                    return [
                        'id' => $flight->id,
                        'flight_number' => $flight->flight_number,
                        'airline' => $flight->schedule->airline->name ?? 'Unknown',
                        'airline_logo' => $flight->schedule->airline->logo_url ?? null,
                        'origin' => $flight->schedule->originAirport->iata_code ?? $this->origin,
                        'origin_name' => $flight->schedule->originAirport->name ?? '',
                        'destination' => $flight->schedule->destinationAirport->iata_code ?? $this->destination,
                        'destination_name' => $flight->schedule->destinationAirport->name ?? '',
                        'departure_time' => $flight->departure_datetime->format('H:i'),
                        'arrival_time' => $flight->arrival_datetime->format('H:i'),
                        'duration' => $flight->departure_datetime->diffInMinutes($flight->arrival_datetime),
                        'price' => $flight->current_price,
                        'available_seats' => $flight->available_seats,
                        'aircraft' => $flight->schedule->aircraft->model ?? 'Unknown',
                        'status' => $flight->status,
                    ];
                })->toArray();
            } else {
                // If no results in database, try API (optional)
                $aviationStack = new AviationStackService();
                $apiResults = $aviationStack->searchFlights(
                    strtoupper($this->origin),
                    strtoupper($this->destination),
                    $this->departureDate
                );

                if (!empty($apiResults)) {
                    $this->searchResults = collect($apiResults)->map(function ($flight) {
                        return [
                            'flight_number' => $flight['flight']['iata'] ?? 'N/A',
                            'airline' => $flight['airline']['name'] ?? 'Unknown',
                            'origin' => $flight['departure']['iata'] ?? $this->origin,
                            'origin_name' => $flight['departure']['airport'] ?? '',
                            'destination' => $flight['arrival']['iata'] ?? $this->destination,
                            'destination_name' => $flight['arrival']['airport'] ?? '',
                            'departure_time' => isset($flight['departure']['scheduled']) ?
                                date('H:i', strtotime($flight['departure']['scheduled'])) : 'N/A',
                            'arrival_time' => isset($flight['arrival']['scheduled']) ?
                                date('H:i', strtotime($flight['arrival']['scheduled'])) : 'N/A',
                            'status' => $flight['flight_status'] ?? 'scheduled',
                            'from_api' => true,
                        ];
                    })->toArray();
                }
            }

            if (empty($this->searchResults)) {
                session()->flash('message', 'No flights found for the selected route and date.');
            }
        } catch (\Exception $e) {
            Log::error('Flight Search Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'An error occurred while searching flights. Please try again.');
        } finally {
            $this->searching = false;
        }
    }

    public function selectFlight($flightId)
    {
        return redirect()->route('booking.create', [
            'flight' => $flightId,
            'passengers' => $this->passengers
        ]);
    }

    public function render()
    {
        return view('livewire.flight-search');
    }
}
