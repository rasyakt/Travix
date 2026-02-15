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
            $dbFlights = Flight::with(['airline', 'originAirport', 'destinationAirport', 'aircraftInstance.aircraft'])
                ->where('origin_airport_id', function($query) {
                    $query->select('id')
                        ->from('airports')
                        ->where('iata_code', strtoupper($this->origin))
                        ->limit(1);
                })
                ->where('destination_airport_id', function($query) {
                    $query->select('id')
                        ->from('airports')
                        ->where('iata_code', strtoupper($this->destination))
                        ->limit(1);
                })
                ->whereDate('departure_time', $this->departureDate)
                ->where('status', '!=', 'cancelled')
                ->get();

            if ($dbFlights->isNotEmpty()) {
                $this->searchResults = $dbFlights->map(function($flight) {
                    return [
                        'id' => $flight->id,
                        'flight_number' => $flight->flight_number,
                        'airline' => $flight->airline->name ?? 'Unknown',
                        'airline_logo' => $flight->airline->logo_url ?? null,
                        'origin' => $flight->originAirport->iata_code ?? $this->origin,
                        'origin_name' => $flight->originAirport->name ?? '',
                        'destination' => $flight->destinationAirport->iata_code ?? $this->destination,
                        'destination_name' => $flight->destinationAirport->name ?? '',
                        'departure_time' => $flight->departure_time->format('H:i'),
                        'arrival_time' => $flight->arrival_time->format('H:i'),
                        'duration' => $flight->departure_time->diffInMinutes($flight->arrival_time),
                        'price' => $flight->base_price,
                        'available_seats' => $flight->available_seats,
                        'aircraft' => $flight->aircraftInstance->aircraft->model ?? 'Unknown',
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
                    $this->searchResults = collect($apiResults)->map(function($flight) {
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
