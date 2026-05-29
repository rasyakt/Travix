<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Schedule;
use App\Models\AircraftInstance;
use App\Models\FlightSeatPrice;
use App\Models\TravelClass;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index(Request $request)
    {
        $query = Flight::with([
            'schedule.originAirport',
            'schedule.destinationAirport',
            'schedule.airline',
            'aircraftInstance.aircraft',
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('flight_number', 'like', "%{$search}%")
                  ->orWhereHas('schedule.airline', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('airline_id')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('airline_id', $request->airline_id);
            });
        }

        $flights = $query->latest('flight_date')->paginate(20);

        return view('admin.flights.index', compact('flights'));
    }

    public function create()
    {
        $schedules = Schedule::with('airline', 'originAirport', 'destinationAirport')
            ->where('is_active', true)
            ->get();
        $aircraftInstances = AircraftInstance::with('airline', 'aircraft')->where('is_active', true)->get();
        return view('admin.flights.create', compact('schedules', 'aircraftInstances'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'aircraft_instance_id' => 'nullable|exists:aircraft_instances,id',
            'flight_date' => 'required|date',
            'departure_datetime' => 'required|date',
            'arrival_datetime' => 'required|date|after:departure_datetime',
            'status' => 'required|in:scheduled,active,cancelled,delayed',
            'available_seats' => 'required|integer|min:0',
            'current_price' => 'required|numeric|min:0',
            'gate' => 'nullable|string|max:10',
            'terminal' => 'nullable|string|max:10',
        ]);

        $schedule = Schedule::findOrFail($validated['schedule_id']);
        $validated['flight_number'] = $schedule->flight_number . '-' . now()->format('dmy');

        $flight = Flight::create($validated);

        return redirect()->route('admin.flights.index')
            ->with('success', 'Flight berhasil dibuat.');
    }

    public function show($id)
    {
        $flight = Flight::with([
            'schedule.originAirport',
            'schedule.destinationAirport',
            'schedule.airline',
            'aircraftInstance.aircraft.manufacturer',
            'seatPrices.travelClass',
            'statusLogs',
            'bookingFlights.booking.user',
        ])->findOrFail($id);

        return view('admin.flights.show', compact('flight'));
    }

    public function edit($id)
    {
        $flight = Flight::findOrFail($id);
        $schedules = Schedule::with('airline', 'originAirport', 'destinationAirport')
            ->where('is_active', true)
            ->get();
        $aircraftInstances = AircraftInstance::with('airline', 'aircraft')
            ->where('is_active', true)
            ->get();

        return view('admin.flights.edit', compact('flight', 'schedules', 'aircraftInstances'));
    }

    public function update(Request $request, $id)
    {
        $flight = Flight::findOrFail($id);

        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'aircraft_instance_id' => 'nullable|exists:aircraft_instances,id',
            'flight_date' => 'required|date',
            'departure_datetime' => 'required|date',
            'arrival_datetime' => 'required|date|after:departure_datetime',
            'status' => 'required|in:scheduled,active,boarding,departed,in_air,landed,arrived,delayed,cancelled',
            'available_seats' => 'required|integer|min:0',
            'current_price' => 'required|numeric|min:0',
            'gate' => 'nullable|string|max:10',
            'terminal' => 'nullable|string|max:10',
        ]);

        $flight->update($validated);

        return redirect()->route('admin.flights.index')
            ->with('success', 'Flight berhasil diperbarui.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:scheduled,active,boarding,departed,in_air,landed,arrived,delayed,cancelled',
        ]);

        $flight = Flight::findOrFail($id);
        $oldStatus = $flight->status;

        $flight->update(['status' => $request->status]);

        \App\Models\FlightStatusLog::create([
            'flight_id' => $flight->id,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'changed_at' => now(),
            'source' => 'admin_manual',
        ]);

        return back()->with('success', 'Status penerbangan berhasil diperbarui.');
    }

    public function updatePrices(Request $request, $id)
    {
        $request->validate([
            'prices' => 'required|array',
            'prices.*.travel_class_id' => 'required|exists:travel_classes,id',
            'prices.*.price' => 'required|numeric|min:0',
            'prices.*.available_seats' => 'required|integer|min:0',
        ]);

        $flight = Flight::findOrFail($id);

        foreach ($request->prices as $priceData) {
            FlightSeatPrice::updateOrCreate(
                [
                    'flight_id' => $flight->id,
                    'travel_class_id' => $priceData['travel_class_id'],
                ],
                [
                    'price' => $priceData['price'],
                    'available_seats' => $priceData['available_seats'],
                    'total_seats' => $priceData['available_seats'],
                ]
            );
        }

        return back()->with('success', 'Harga kursi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $flight = Flight::findOrFail($id);
        $flight->delete();

        return redirect()->route('admin.flights.index')
            ->with('success', 'Flight berhasil dihapus.');
    }
}
