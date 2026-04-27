<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Enums\PaymentStatus;
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
        Booking::with(['payment', 'passengers.seatAssignment'])
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->get()
            ->each(function (Booking $booking) {
                $booking->expirePendingReservation();
            });

        $allBookings = Booking::with([
            'flights.schedule.originAirport',
            'flights.schedule.destinationAirport',
            'flights.schedule.airline',
            'passengers',
            'payment'
        ])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $this->bookings = $allBookings;

        // Separate upcoming and past flights
        $this->upcomingFlights = $allBookings->filter(function ($booking) {
            $departureDate = $this->getBookingDepartureDate($booking);
            return $departureDate && $departureDate->isFuture();
        })->values();

        $this->pastFlights = $allBookings->filter(function ($booking) {
            $departureDate = $this->getBookingDepartureDate($booking);
            return $departureDate && $departureDate->isPast();
        })->values();
    }

    /**
     * Get departure date for both database and API bookings
     */
    protected function getBookingDepartureDate($booking)
    {
        // Check if it's a database flight booking
        $flight = $booking->flights->first();
        if ($flight) {
            return $flight->departure_datetime;
        }

        // Check if it's an API booking
        if ($booking->payment && isset($booking->payment->payment_details['flight_data'])) {
            $flightData = $booking->payment->payment_details['flight_data'];
            $departureTime = $flightData['departure_time'] ?? null;
            
            if ($departureTime) {
                try {
                    return \Carbon\Carbon::parse($departureTime);
                } catch (\Exception $e) {
                    \Log::warning('Failed to parse API flight departure time', [
                        'booking_id' => $booking->id,
                        'departure_time' => $departureTime
                    ]);
                }
            }
        }

        return null;
    }

    public function cancelBooking($bookingId)
    {
        try {
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Check if booking can be cancelled (e.g., at least 24 hours before departure)
            $flight = $booking->flights->first();
            $hoursUntilDeparture = $flight ? now()->diffInHours($flight->departure_datetime, false) : null;

            if (!is_null($hoursUntilDeparture) && $hoursUntilDeparture < 24) {
                session()->flash('error', 'Cannot cancel booking less than 24 hours before departure.');
                return;
            }

            $booking->update(['status' => 'cancelled']);

            // Update payment status if needed
            if ($booking->payment) {
                $booking->payment->update(['status' => PaymentStatus::CANCELLED->value]);
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
