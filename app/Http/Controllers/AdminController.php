<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Flight;
use App\Enums\FlightStatus;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $bookings = Booking::with([
            'user',
            'flights.originAirport',
            'flights.destinationAirport',
            'payment'
        ])
            ->latest()
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with([
            'user',
            'flights.originAirport',
            'flights.destinationAirport',
            'flights.airline',
            'passengers.seatAssignment',
            'passengers.boardingPass.checkIn',
            'passengers.baggage',
            'payment'
        ])->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    public function destroy($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            // Delete related records first
            $booking->passengers->each(function (\App\Models\BookingPassenger $passenger) {
                $passenger->seatAssignment()?->delete();
                $passenger->checkIn()?->delete();
                $passenger->baggage()->delete();
            });

            $booking->passengers()->delete();
            $booking->payment?->delete();
            $booking->delete();

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete booking: ' . $e->getMessage());
        }
    }

    public function updateFlightStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:scheduled,active,delayed,cancelled,landed',
        ]);

        try {
            $flight = Flight::findOrFail($id);

            $oldStatus = $flight->status;
            $newStatus = $request->status;

            $flight->update(['status' => $newStatus]);

            // Log status change
            \App\Models\FlightStatusLog::create([
                'flight_id' => $flight->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_at' => now(),
                'source' => 'admin_manual',
            ]);

            return back()->with('success', 'Flight status updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update flight status: ' . $e->getMessage());
        }
    }
}
