<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Services\AviationStackService;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    protected $aviationStack;

    public function __construct(AviationStackService $aviationStack)
    {
        $this->aviationStack = $aviationStack;
    }

    public function index()
    {
        return view('flights.index');
    }

    public function show($id)
    {
        $flight = Flight::with([
            'schedule.airline',
            'schedule.originAirport',
            'schedule.destinationAirport',
            'schedule.aircraft',
            'flightSeatPrices.travelClass',
            'statusLogs' => function ($query) {
                $query->orderBy('changed_at', 'desc');
            }
        ])->findOrFail($id);

        $flightSeatPrices = $flight->flightSeatPrices;

        return view('flights.show', compact('flight', 'flightSeatPrices'));
    }

    public function status($id)
    {
        $flight = Flight::with(['airline', 'originAirport', 'destinationAirport'])
            ->findOrFail($id);

        // Try to get real-time status from API
        $apiStatus = $this->aviationStack->getFlightStatus($flight->flight_number);

        return view('flights.status', compact('flight', 'apiStatus'));
    }

    public function updateStatus($id)
    {
        $flight = Flight::findOrFail($id);

        // Get real-time status from API
        $apiData = $this->aviationStack->getFlightStatus($flight->flight_number);

        if (!empty($apiData)) {
            // Update flight status from API
            $this->aviationStack->syncFlightToDatabase($apiData);

            return back()->with('success', 'Flight status updated successfully');
        }

        return back()->with('error', 'Unable to fetch flight status from API');
    }
}
