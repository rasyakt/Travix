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
    public $isFullPage = false;

    // Filters
    public $filterAirlines = [];
    public $maxPrice = 0;
    public $selectedAirlines = [];

    // Daily Prices
    public $dailyPrices = [];

    public $originSuggestions = [];
    public $destinationSuggestions = [];
    public $airports = [];

    protected $queryString = [
        'origin' => ['except' => ''],
        'destination' => ['except' => ''],
        'departureDate' => ['except' => ''],
        'returnDate' => ['except' => ''],
        'tripType' => ['except' => 'one-way'],
        'seatClass' => ['except' => 'Economy'],
        'adults' => ['except' => 1],
        'children' => ['except' => 0],
        'infants' => ['except' => 0],
    ];

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

    public function selectOrigin($iataCode, $city)
    {
        $this->origin = $iataCode;
        $this->originSearch = "$city ($iataCode)";
    }

    public function selectDestination($iataCode, $city)
    {
        $this->destination = $iataCode;
        $this->destinationSearch = "$city ($iataCode)";
    }

    public function updatedTripType($value)
    {
        if ($value === 'multi-city') {
            $this->tripType = 'one-way';
            session()->flash('message', 'Mode multi-kota belum tersedia pada form pencarian ini.');
            return;
        }

        if ($value === 'round-trip' && empty($this->returnDate)) {
            $this->returnDate = now()->addDays(2)->format('Y-m-d');
        }
    }

    public function refreshOriginSuggestions($forcePopular = false)
    {
        $this->originSuggestions = $this->getAirports($forcePopular ? '' : $this->originSearch);
    }

    public function refreshDestinationSuggestions($forcePopular = false)
    {
        $this->destinationSuggestions = $this->getAirports($forcePopular ? '' : $this->destinationSearch);
    }

    public function mount()
    {
        if ($this->tripType === 'multi-city') {
            $this->tripType = 'one-way';
        }

        if (empty($this->departureDate)) {
            $this->departureDate = now()->format('Y-m-d');
        }
        $this->returnDate = now()->addDays(2)->format('Y-m-d');

        // Initial popular suggestions
        $this->originSuggestions = Airport::limit(10)->get()->toArray();
        $this->destinationSuggestions = Airport::limit(10)->get()->toArray();

        // Check if we are on the full flights page
        $this->isFullPage = request()->routeIs('flights.index');

        // Resolve names if codes are present from query string
        if (!empty($this->origin)) {
            $apt = Airport::where('iata_code', $this->origin)->first();
            if ($apt)
                $this->originSearch = "{$apt->city} ({$apt->iata_code})";
        }
        if (!empty($this->destination)) {
            $apt = Airport::where('iata_code', $this->destination)->first();
            if ($apt)
                $this->destinationSearch = "{$apt->city} ({$apt->iata_code})";
        }

        if ($this->isFullPage && !empty($this->origin) && !empty($this->destination)) {
            $this->searchFlights();
        }
    }

    public function searchFlights()
    {
        if ($this->tripType === 'multi-city') {
            session()->flash('message', 'Mode multi-kota belum tersedia pada form pencarian ini.');
            return;
        }

        // If not on full search page, redirect there with params
        if (!$this->isFullPage) {
            return redirect()->route('flights.index', [
                'origin' => $this->origin ?: ($this->originSearch ? substr($this->originSearch, -4, 3) : ''),
                'destination' => $this->destination ?: ($this->destinationSearch ? substr($this->destinationSearch, -4, 3) : ''),
                'departureDate' => $this->departureDate,
                'returnDate' => $this->tripType === 'round-trip' ? $this->returnDate : null,
                'tripType' => $this->tripType,
                'seatClass' => $this->seatClass,
                'adults' => $this->adults,
                'children' => $this->children,
                'infants' => $this->infants,
            ]);
        }

        // Auto-detect codes if not selected but validly entered
        $this->originSearch = trim($this->originSearch);
        $this->destinationSearch = trim($this->destinationSearch);

        if (empty($this->origin) && preg_match('/\(([A-Z]{3})\)/', $this->originSearch, $matches)) {
            $this->origin = $matches[1];
        } elseif (empty($this->origin) && strlen($this->originSearch) === 3) {
            $this->origin = strtoupper($this->originSearch);
        }

        if (empty($this->destination) && preg_match('/\(([A-Z]{3})\)/', $this->destinationSearch, $matches)) {
            $this->destination = $matches[1];
        } elseif (empty($this->destination) && strlen($this->destinationSearch) === 3) {
            $this->destination = strtoupper($this->destinationSearch);
        }

        $this->validate($this->getValidationRules());

        $this->searching = true;
        $this->searchResults = [];

        try {
            // Fetch daily prices (Simulated range around departure date)
            $this->generateDailyPrices();

            // Search logic... (Keep existing logic but add filtering later)
            $this->performSearch();

        } catch (\Exception $e) {
            Log::error('Flight Search Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'An error occurred while searching flights.');
        } finally {
            $this->searching = false;
        }
    }

    protected function generateDailyPrices()
    {
        $baseDate = \Carbon\Carbon::parse($this->departureDate);
        $this->dailyPrices = [];

        for ($i = -3; $i <= 3; $i++) {
            $date = $baseDate->copy()->addDays($i);
            if ($date->isPast())
                continue;

            // Simple pseudo-random price based on date and route to keep it consistent for this session
            $seed = crc32($this->origin . $this->destination . $date->toDateString());
            $price = 1200000 + ($seed % 800000);

            $this->dailyPrices[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('D, d M'),
                'price' => $price,
                'is_current' => $date->isSameDay($baseDate),
            ];
        }
    }

    public function changeDate($date)
    {
        $this->departureDate = $date;
        $this->searchFlights();
    }

    protected function performSearch()
    {
        $passengerCount = $this->getPassengerCount();
        $travelClassId = $this->getSeatClassId();

        $dbFlights = Flight::with([
            'schedule.airline',
            'schedule.originAirport',
            'schedule.destinationAirport',
            'schedule.aircraft',
            'seatPrices'
        ])
            ->whereHas('schedule.originAirport', function ($query) {
                $query->where('iata_code', strtoupper($this->origin));
            })
            ->whereHas('schedule.destinationAirport', function ($query) {
                $query->where('iata_code', strtoupper($this->destination));
            })
            ->whereDate('flight_date', $this->departureDate)
            ->available($passengerCount)
            ->where(function ($query) use ($travelClassId, $passengerCount) {
                $query->whereDoesntHave('seatPrices')
                    ->orWhereHas('seatPrices', function ($seatPrices) use ($travelClassId, $passengerCount) {
                        $seatPrices->where('travel_class_id', $travelClassId)
                            ->where('available_seats', '>=', $passengerCount);
                    });
            })
            ->get();

        if ($dbFlights->isNotEmpty()) {
            $this->searchResults = $dbFlights->map(function ($flight) {
                return $this->formatFlight($flight);
            })->toArray();
        } else {
            $serpApi = new SerpApiFlightService();
            $apiResults = $serpApi->searchFlights(
                strtoupper($this->origin),
                strtoupper($this->destination),
                $this->departureDate,
                $this->tripType === 'round-trip' ? $this->returnDate : null,
                (int) $this->adults,
                (int) $this->children,
                (int) $this->infants,
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

        // Extract filters info
        $this->filterAirlines = collect($this->searchResults)->pluck('airline')->unique()->values()->all();
        $prices = collect($this->searchResults)->pluck('price');
        $this->maxPrice = $prices->max() ?: 5000000;
    }

    protected function formatFlight($flight)
    {
        $travelClassId = $this->getSeatClassId();
        $classSeatPrice = $flight->relationLoaded('seatPrices')
            ? $flight->seatPrices->firstWhere('travel_class_id', $travelClassId)
            : null;

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
            'price' => (float) ($classSeatPrice?->price ?? $flight->getPriceForClass($travelClassId)),
            'available_seats' => $classSeatPrice?->available_seats ?? $flight->available_seats,
            'aircraft' => $flight->schedule->aircraft->model ?? 'Unknown',
            'status' => $flight->status,
            'amenities' => $flight->schedule->aircraft->amenities ?? [],
            'bookable' => true,
        ];
    }

    public function selectFlight($flightId)
    {
        $flightId = (int) $flightId;

        if ($flightId <= 0) {
            session()->flash('message', 'Penerbangan ini belum dapat dipesan langsung. Silakan pilih penerbangan lain.');
            return;
        }

        $flight = Flight::with('seatPrices')->find($flightId);

        if (!$flight) {
            session()->flash('message', 'Penerbangan tidak ditemukan atau sudah tidak tersedia. Silakan cari ulang.');
            return;
        }

        $passengerCount = $this->getPassengerCount();
        $travelClassId = $this->getSeatClassId();
        $classAvailableSeats = $flight->getAvailableSeatsForClass($travelClassId);

        if ($classAvailableSeats > 0 && $classAvailableSeats < $passengerCount) {
            session()->flash('message', 'Kursi pada kelas yang dipilih tidak mencukupi jumlah penumpang Anda.');
            return;
        }

        if ($flight->available_seats < $passengerCount) {
            session()->flash('message', 'Sisa kursi tidak mencukupi jumlah penumpang Anda.');
            return;
        }

        return redirect()->route('booking.create', [
            'flight' => $flightId,
            'passengers' => $passengerCount
        ]);
    }

    public function notifyUnbookableFlight()
    {
        session()->flash('message', 'Maskapai ini berasal dari data partner API dan belum terhubung ke booking otomatis.');
    }

    protected function getValidationRules(): array
    {
        $rules = $this->rules;
        $rules['returnDate'] = $this->tripType === 'round-trip'
            ? 'required|date|after_or_equal:departureDate'
            : 'nullable|date|after_or_equal:departureDate';

        return $rules;
    }

    protected function getPassengerCount(): int
    {
        return max(1, (int) $this->adults + (int) $this->children + (int) $this->infants);
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
        // Apply filters in memory for now
        $filteredResults = collect($this->searchResults);

        if (!empty($this->selectedAirlines)) {
            $filteredResults = $filteredResults->whereIn('airline', $this->selectedAirlines);
        }

        return view('livewire.flight-search', [
            'flights' => $filteredResults->all()
        ]);
    }
}
