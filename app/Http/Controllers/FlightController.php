<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
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
            'seatPrices.travelClass',
            'statusLogs' => function ($query) {
                $query->orderBy('logged_at', 'desc');
            }
        ])->findOrFail($id);

        $flightSeatPrices = $flight->seatPrices;

        return view('flights.show', compact('flight', 'flightSeatPrices'));
    }

    public function experience()
    {
        return view('experience');
    }

    public function destinations()
    {
        $airports = \App\Models\Airport::orderBy('city', 'asc')->get();
        return view('destinations', compact('airports'));
    }
}
