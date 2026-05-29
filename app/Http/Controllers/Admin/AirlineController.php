<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use Illuminate\Http\Request;

class AirlineController extends Controller
{
    public function index()
    {
        $airlines = Airline::withCount('schedules', 'aircraftInstances')->latest()->paginate(20);
        return view('admin.airlines.index', compact('airlines'));
    }

    public function create()
    {
        return view('admin.airlines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'iata_code' => ['required', 'string', 'size:2', 'unique:airlines'],
            'icao_code' => ['required', 'string', 'size:3', 'unique:airlines'],
            'country' => ['required', 'string', 'max:100'],
            'logo_url' => ['nullable', 'url'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Airline::create($validated);

        return redirect()->route('admin.airlines.index')
            ->with('success', 'Maskapai berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $airline = Airline::findOrFail($id);
        return view('admin.airlines.edit', compact('airline'));
    }

    public function update(Request $request, $id)
    {
        $airline = Airline::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'iata_code' => ['required', 'string', 'size:2', 'unique:airlines,iata_code,' . $id],
            'icao_code' => ['required', 'string', 'size:3', 'unique:airlines,icao_code,' . $id],
            'country' => ['required', 'string', 'max:100'],
            'logo_url' => ['nullable', 'url'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $airline->update($validated);

        return redirect()->route('admin.airlines.index')
            ->with('success', 'Maskapai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $airline = Airline::findOrFail($id);

        if ($airline->schedules()->count() > 0) {
            return back()->with('error', 'Maskapai tidak dapat dihapus karena masih memiliki jadwal penerbangan.');
        }

        $airline->delete();

        return redirect()->route('admin.airlines.index')
            ->with('success', 'Maskapai berhasil dihapus.');
    }
}
