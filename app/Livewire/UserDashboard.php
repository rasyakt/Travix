<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class UserDashboard extends Component
{
    public $bookings = [];
    public $upcomingFlights = [];
    public $pastFlights = [];

    public function mount()
    {
        $this->loadBookings();
    }

    public function loadBookings()
    {
        $allBookings = Booking::with(['flights.originAirport', 'flights.destinationAirport', 'flights.airline', 'payment'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $this->bookings = $allBookings;

        // Separate upcoming and past flights
        $this->upcomingFlights = $allBookings->filter(function($booking) {
            $flight = $booking->flights->first();
            return $flight && $flight->departure_time->isFuture();
        });

        $this->pastFlights = $allBookings->filter(function($booking) {
            $flight = $booking->flights->first();
            return $flight && $flight->departure_time->isPast();
        });
    }

    public function cancelBooking($bookingId)
    {
        try {
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Check if booking can be cancelled (e.g., at least 24 hours before departure)
            $flight = $booking->flights->first();
            if ($flight && $flight->departure_time->diffInHours(now()) < 24) {
                session()->flash('error', 'Cannot cancel booking less than 24 hours before departure.');
                return;
            }

            $booking->update(['status' => 'cancelled']);
            
            // Update payment status if needed
            if ($booking->payment) {
                $booking->payment->update(['status' => 'refunded']);
            }

            session()->flash('success', 'Booking cancelled successfully.');
            $this->loadBookings();

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to cancel booking.');
            \Log::error('Cancel Booking Error', ['message' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.user-dashboard');
    }
}
