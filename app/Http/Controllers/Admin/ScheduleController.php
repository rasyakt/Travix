<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Aircraft;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with([
            'airline',
            'originAirport',
            'destinationAirport',
            'aircraft',
        ])->latest()->paginate(20);

        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $airlines = Airline::where('is_active', true)->get();
        $airports = Airport::where('is_active', true)->get();
        $aircraft = Aircraft::all();

        return view('admin.schedules.create', compact('airlines', 'airports', 'aircraft'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'airline_id' => ['required', 'exists:airlines,id'],
            'aircraft_id' => ['required', 'exists:aircraft,id'],
            'origin_airport_id' => ['required', 'exists:airports,id', 'different:destination_airport_id'],
            'destination_airport_id' => ['required', 'exists:airports,id', 'different:origin_airport_id'],
            'flight_number' => ['required', 'string', 'max:10', 'unique:schedules'],
            'departure_time' => ['required', 'date_format:H:i'],
            'arrival_time' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'operating_days' => ['required', 'array'],
            'operating_days.*' => ['in:0,1,2,3,4,5,6'],
            'valid_from' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after_or_equal:valid_from'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['operating_days'] = $request->operating_days;
        $validated['is_active'] = $request->boolean('is_active');

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal penerbangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $airlines = Airline::where('is_active', true)->get();
        $airports = Airport::where('is_active', true)->get();
        $aircraft = Aircraft::all();

        return view('admin.schedules.edit', compact('schedule', 'airlines', 'airports', 'aircraft'));
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'airline_id' => ['required', 'exists:airlines,id'],
            'aircraft_id' => ['required', 'exists:aircraft,id'],
            'origin_airport_id' => ['required', 'exists:airports,id', 'different:destination_airport_id'],
            'destination_airport_id' => ['required', 'exists:airports,id', 'different:origin_airport_id'],
            'flight_number' => ['required', 'string', 'max:10', 'unique:schedules,flight_number,' . $id],
            'departure_time' => ['required', 'date_format:H:i'],
            'arrival_time' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'operating_days' => ['required', 'array'],
            'operating_days.*' => ['in:0,1,2,3,4,5,6'],
            'valid_from' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after_or_equal:valid_from'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['operating_days'] = $request->operating_days;
        $validated['is_active'] = $request->boolean('is_active');

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal penerbangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);

        if ($schedule->flights()->count() > 0) {
            return back()->with('error', 'Jadwal tidak dapat dihapus karena masih memiliki penerbangan terkait.');
        }

        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal penerbangan berhasil dihapus.');
    }
}
