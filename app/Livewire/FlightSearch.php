<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\SerpApiFlightService;
use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Support\Facades\Log;

class FlightSearch extends Component
{
    public $origin = '';
    public $destination = '';
    public $originSearch = '';
    public $destinationSearch = '';

    public $departureDate = '';
    public $returnDate = '';
    public $tripType = 'one-way'; // 'one-way', 'round-trip', 'multi-city'
    public $seatClass = 'Economy';
    public $adults = 1;
    public $children = 0;
    public $infants = 0;

    public $minimal = false;
    public $searchResults = [];
    public $searching = false;

    public $originSuggestions = [];
    public $destinationSuggestions = [];
    public $airports = [];

    protected $rules = [
        'origin' => 'required|string|size:3',
        'destination' => 'required|string|size:3|different:origin',
        'departureDate' => 'required|date|after_or_equal:today',
        'returnDate' => 'nullable|date|after_or_equal:departureDate',
        'adults' => 'required|integer|min:1|max:9',
        'children' => 'required|integer|min:0|max:9',
        'infants' => 'required|integer|min:0|max:9',
    ];

    protected $messages = [
        'origin.required' => 'Asal tidak boleh kosong',
        'origin.size' => 'Kode bandara harus 3 karakter',
        'destination.required' => 'Tujuan tidak boleh kosong',
        'destination.size' => 'Kode bandara harus 3 karakter',
        'destination.different' => 'Tujuan harus berbeda dari asal',
        'departureDate.required' => 'Pilih tanggal keberangkatan',
        'departureDate.after_or_equal' => 'Harus hari ini atau setelahnya',
        'returnDate.after_or_equal' => 'Harus setelah tanggal berangkat',
    ];

    public function updatedOriginSearch($query)
    {
        $this->originSuggestions = $this->getAirports($query);
    }

    public function updatedDestinationSearch($query)
    {
        $this->destinationSuggestions = $this->getAirports($query);
    }

    protected function getAirports($query)
    {
        if (strlen($query) < 1) {
            return Airport::limit(10)->get()->toArray();
        }

        $keywords = array_filter(explode(' ', $query));
        $dbQuery = Airport::query();

        foreach ($keywords as $keyword) {
            $keyword = strtolower($keyword);
            $dbQuery->where(function ($q) use ($keyword) {
                $q->whereRaw('LOWER(iata_code) ILIKE ?', ["%{$keyword}%"])
                    ->orWhereRaw('LOWER(city) ILIKE ?', ["%{$keyword}%"])
                    ->orWhereRaw('LOWER(name) ILIKE ?', ["%{$keyword}%"])
                    ->orWhereRaw('LOWER(country) ILIKE ?', ["%{$keyword}%"]);
            });
        }

        return $dbQuery->limit(10)->get()->toArray();
    }

    public function selectOrigin($iataCode, $name)
    {
        $this->origin = $iataCode;
        $this->originSearch = "$name ($iataCode)";
        // Don't clear suggestions, just let Alpine hide the dropdown
    }

    public function selectDestination($iataCode, $name)
    {
        $this->destination = $iataCode;
        $this->destinationSearch = "$name ($iataCode)";
        // Don't clear suggestions
    }

    public function refreshOriginSuggestions()
    {
        $this->originSuggestions = $this->getAirports($this->originSearch);
    }

    public function refreshDestinationSuggestions()
    {
        $this->destinationSuggestions = $this->getAirports($this->destinationSearch);
    }

    public function mount()
    {
        $this->departureDate = now()->format('Y-m-d');
        $this->returnDate = now()->addDays(2)->format('Y-m-d');

        // Initial popular suggestions
        $this->originSuggestions = Airport::limit(10)->get()->toArray();
        $this->destinationSuggestions = Airport::limit(10)->get()->toArray();

        $this->airports = Airport::select('iata_code', 'name', 'city', 'country')
            ->orderBy('name')
            ->limit(100)
            ->get();
    }

    public function searchFlights()
    {
        // Auto-detect codes if not selected but validly entered
        if (empty($this->origin) && preg_match('/\(([A-Z]{3})\)$/', $this->originSearch, $matches)) {
            $this->origin = $matches[1];
        } elseif (empty($this->origin) && strlen($this->originSearch) === 3) {
            $this->origin = strtoupper($this->originSearch);
        }

        if (empty($this->destination) && preg_match('/\(([A-Z]{3})\)$/', $this->destinationSearch, $matches)) {
            $this->destination = $matches[1];
        } elseif (empty($this->destination) && strlen($this->destinationSearch) === 3) {
            $this->destination = strtoupper($this->destinationSearch);
        }

        $this->validate();

        $this->searching = true;
        $this->searchResults = [];

        try {
            Log::info('Flight Search Started', [
                'origin' => $this->origin,
                'destination' => $this->destination,
                'date' => $this->departureDate
            ]);

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
                // If no results in database, try SerpApi (Google Flights)
                $serpApi = new SerpApiFlightService();
                $apiResults = $serpApi->searchFlights(
                    strtoupper($this->origin),
                    strtoupper($this->destination),
                    $this->departureDate,
                    $this->tripType === 'round-trip' ? $this->returnDate : null,
                    (int) $this->adults,
                    (int) $this->children,
                    (int) $this->infants, // Simplified: assuming on lap for infants
                    0,
                    $this->getSeatClassId()
                );

                if (!empty($apiResults)) {
                    $totalPassengers = (int) $this->adults + (int) $this->children + (int) $this->infants;
                    $this->searchResults = array_map(function ($flight) use ($totalPassengers) {
                        $flight['price'] = $totalPassengers > 0 ? ($flight['price'] / $totalPassengers) : $flight['price'];
                        return $flight;
                    }, $apiResults);
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
            'passengers' => $this->adults + $this->children + $this->infants
        ]);
    }

    protected function getSeatClassId(): int
    {
        return match ($this->seatClass) {
            'Economy' => 1,
            'Premium Economy' => 2,
            'Business' => 3,
            'First Class' => 4,
            default => 1,
        };
    }

    public function render()
    {
        return view('livewire.flight-search');
    }
}
