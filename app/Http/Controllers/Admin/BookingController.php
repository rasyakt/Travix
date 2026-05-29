<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with([
            'user',
            'flights.schedule.originAirport',
            'flights.schedule.destinationAirport',
            'payment'
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%")
                  ->orWhere('contact_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bookings = $query->latest()->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with([
            'user',
            'flights.schedule.originAirport',
            'flights.schedule.destinationAirport',
            'flights.schedule.airline',
            'passengers.seatAssignment.seatMap',
            'passengers.boardingPass',
            'passengers.baggage',
            'payment'
        ])->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            $booking->passengers->each(function ($passenger) {
                $passenger->seatAssignment()?->delete();
                $passenger->checkIn()?->delete();
                $passenger->baggage()->delete();
            });

            $booking->passengers()->delete();
            $booking->payment?->delete();
            $booking->delete();

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus booking: ' . $e->getMessage());
        }
    }
}
