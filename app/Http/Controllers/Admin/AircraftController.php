<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aircraft;
use App\Models\AircraftManufacturer;
use App\Models\AircraftInstance;
use App\Models\Airline;
use Illuminate\Http\Request;

class AircraftController extends Controller
{
    public function index()
    {
        $aircraft = Aircraft::with('manufacturer')->latest()->paginate(20);
        return view('admin.aircraft.index', compact('aircraft'));
    }

    public function create()
    {
        $manufacturers = AircraftManufacturer::all();
        return view('admin.aircraft.create', compact('manufacturers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'manufacturer_id' => ['required', 'exists:aircraft_manufacturers,id'],
            'model' => ['required', 'string', 'max:255'],
            'iata_code' => ['required', 'string', 'max:10'],
            'typical_seating_capacity' => ['required', 'integer', 'min:1'],
            'max_range_km' => ['nullable', 'integer', 'min:0'],
            'cruise_speed_kmh' => ['nullable', 'numeric', 'min:0'],
            'legroom' => ['nullable', 'string', 'max:50'],
            'amenities' => ['nullable', 'json'],
        ]);

        Aircraft::create($validated);

        return redirect()->route('admin.aircraft.index')
            ->with('success', 'Tipe pesawat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $aircraft = Aircraft::findOrFail($id);
        $manufacturers = AircraftManufacturer::all();
        return view('admin.aircraft.edit', compact('aircraft', 'manufacturers'));
    }

    public function update(Request $request, $id)
    {
        $aircraft = Aircraft::findOrFail($id);

        $validated = $request->validate([
            'manufacturer_id' => ['required', 'exists:aircraft_manufacturers,id'],
            'model' => ['required', 'string', 'max:255'],
            'iata_code' => ['required', 'string', 'max:10'],
            'typical_seating_capacity' => ['required', 'integer', 'min:1'],
            'max_range_km' => ['nullable', 'integer', 'min:0'],
            'cruise_speed_kmh' => ['nullable', 'numeric', 'min:0'],
            'legroom' => ['nullable', 'string', 'max:50'],
            'amenities' => ['nullable', 'json'],
        ]);

        $aircraft->update($validated);

        return redirect()->route('admin.aircraft.index')
            ->with('success', 'Tipe pesawat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $aircraft = Aircraft::findOrFail($id);

        if ($aircraft->instances()->count() > 0) {
            return back()->with('error', 'Tipe pesawat tidak dapat dihapus karena masih memiliki instance.');
        }

        $aircraft->delete();

        return redirect()->route('admin.aircraft.index')
            ->with('success', 'Tipe pesawat berhasil dihapus.');
    }

    public function instances($id)
    {
        $aircraft = Aircraft::with('instances.airline')->findOrFail($id);
        $airlines = Airline::where('is_active', true)->get();
        return view('admin.aircraft.instances', compact('aircraft', 'airlines'));
    }

    public function storeInstance(Request $request, $aircraftId)
    {
        $validated = $request->validate([
            'airline_id' => ['required', 'exists:airlines,id'],
            'registration_number' => ['required', 'string', 'max:20', 'unique:aircraft_instances'],
            'name' => ['nullable', 'string', 'max:255'],
            'manufacture_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'is_active' => ['boolean'],
        ]);

        $validated['aircraft_id'] = $aircraftId;
        $validated['is_active'] = $request->boolean('is_active');

        AircraftInstance::create($validated);

        return back()->with('success', 'Instance pesawat berhasil ditambahkan.');
    }

    public function destroyInstance($aircraftId, $instanceId)
    {
        $instance = AircraftInstance::findOrFail($instanceId);

        if ($instance->flights()->count() > 0) {
            return back()->with('error', 'Instance tidak dapat dihapus karena masih memiliki penerbangan.');
        }

        $instance->delete();

        return back()->with('success', 'Instance pesawat berhasil dihapus.');
    }
}
