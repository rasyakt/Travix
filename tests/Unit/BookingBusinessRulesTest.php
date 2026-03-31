<?php

namespace Tests\Unit;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class BookingBusinessRulesTest extends TestCase
{
    public function test_store_booking_request_allows_guest_booking_flow(): void
    {
        $request = new StoreBookingRequest();

        $this->assertTrue($request->authorize());
    }

    public function test_booking_can_checkin_when_confirmed_paid_and_within_24_to_3_hours(): void
    {
        $booking = $this->makeBookingForCheckInWindow('confirmed', 'success', now()->addHours(8));

        $this->assertTrue($booking->canCheckIn());
        $this->assertNull($booking->check_in_blocked_reason);
    }

    public function test_booking_cannot_checkin_when_unpaid(): void
    {
        $booking = $this->makeBookingForCheckInWindow('confirmed', 'pending', now()->addHours(8));

        $this->assertFalse($booking->canCheckIn());
        $this->assertSame('Check-in hanya tersedia untuk booking yang sudah dibayar.', $booking->check_in_blocked_reason);
    }

    public function test_booking_cannot_checkin_when_less_than_three_hours_before_departure(): void
    {
        $booking = $this->makeBookingForCheckInWindow('confirmed', 'success', now()->addHours(2));

        $this->assertFalse($booking->canCheckIn());
        $this->assertSame('Online check-in ditutup 3 jam sebelum keberangkatan.', $booking->check_in_blocked_reason);
    }

    public function test_booking_cannot_checkin_for_departed_flights(): void
    {
        $booking = $this->makeBookingForCheckInWindow('confirmed', 'success', now()->subHours(10));

        $this->assertFalse($booking->canCheckIn());
        $this->assertSame('Online check-in ditutup 3 jam sebelum keberangkatan.', $booking->check_in_blocked_reason);
    }

    private function makeBookingForCheckInWindow(string $bookingStatus, string $paymentStatus, Carbon $departure): Booking
    {
        $booking = new Booking([
            'status' => $bookingStatus,
        ]);

        $booking->setRelation('payment', new Payment(['status' => $paymentStatus]));

        $flight = new Flight([
            'departure_datetime' => $departure,
            'status' => 'scheduled',
        ]);

        $booking->setRelation('flights', new Collection([$flight]));

        return $booking;
    }
}
