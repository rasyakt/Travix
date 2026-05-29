<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function index()
    {
        $airports = Airport::latest()->paginate(20);
        return view('admin.airports.index', compact('airports'));
    }

    public function create()
    {
        return view('admin.airports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'iata_code' => ['required', 'string', 'size:3', 'unique:airports'],
            'icao_code' => ['required', 'string', 'size:4', 'unique:airports'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Airport::create($validated);

        return redirect()->route('admin.airports.index')
            ->with('success', 'Bandara berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $airport = Airport::findOrFail($id);
        return view('admin.airports.edit', compact('airport'));
    }

    public function update(Request $request, $id)
    {
        $airport = Airport::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'iata_code' => ['required', 'string', 'size:3', 'unique:airports,iata_code,' . $id],
            'icao_code' => ['required', 'string', 'size:4', 'unique:airports,icao_code,' . $id],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $airport->update($validated);

        return redirect()->route('admin.airports.index')
            ->with('success', 'Bandara berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $airport = Airport::findOrFail($id);

        if ($airport->departureSchedules()->count() > 0 || $airport->arrivalSchedules()->count() > 0) {
            return back()->with('error', 'Bandara tidak dapat dihapus karena masih memiliki jadwal penerbangan.');
        }

        $airport->delete();

        return redirect()->route('admin.airports.index')
            ->with('success', 'Bandara berhasil dihapus.');
    }
}
