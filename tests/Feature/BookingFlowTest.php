<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\BookingController;
use App\Models\Booking;
use App\Models\BookingFlight;
use App\Models\BookingPassenger;
use App\Models\Flight;
use App\Models\Payment;
use App\Models\SeatAssignment;
use App\Models\SeatMap;
use App\Models\TravelClass;
use App\Models\User;
use App\Services\SeatAssignmentService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_authenticated_user_can_create_pending_booking_from_http_flow(): void
    {
        $user = User::factory()->create();
        $travelClass = TravelClass::where('code', 'Y')->firstOrFail();
        $flight = $this->findFlightForTravelClass($travelClass->id, 2);

        $response = $this->actingAs($user)->post(route('booking.store'), [
            'flight_id' => $flight->id,
            'contact_name' => 'Test Booker',
            'contact_email' => 'booker@example.com',
            'contact_phone' => '081234567890',
            'passengers' => [
                [
                    'title' => 'Mr',
                    'first_name' => 'Test1',
                    'last_name' => 'Passenger',
                    'date_of_birth' => now()->subYears(21)->format('Y-m-d'),
                    'nationality' => 'ID',
                    'passport_number' => 'P0000001',
                    'travel_class_id' => $travelClass->id,
                ],
                [
                    'title' => 'Mrs',
                    'first_name' => 'Test2',
                    'last_name' => 'Passenger',
                    'date_of_birth' => now()->subYears(22)->format('Y-m-d'),
                    'nationality' => 'ID',
                    'passport_number' => 'P0000002',
                    'travel_class_id' => $travelClass->id,
                ],
            ],
        ]);

        $response->assertRedirect();

        $booking = Booking::with(['payment', 'bookingFlights', 'passengers'])->latest('id')->firstOrFail();
        $this->assertSame($user->id, $booking->user_id);
        $this->assertSame(BookingStatus::PENDING->value, $booking->status);
        $this->assertSame(PaymentStatus::PENDING->value, $booking->payment?->status);
        $this->assertCount(2, $booking->passengers);
    }

    public function test_authenticated_user_can_assign_seats_and_complete_dummy_payment(): void
    {
        $user = User::factory()->create();
        $travelClass = TravelClass::where('code', 'Y')->firstOrFail();
        $flight = $this->findFlightForTravelClass($travelClass->id, 2);
        $booking = $this->createPendingBooking($user->id, $flight->id, $travelClass->id, 2);

        $initialTotalSeats = (int) $flight->available_seats;
        $initialClassSeats = (int) $flight->seatPrices()->where('travel_class_id', $travelClass->id)->value('available_seats');

        $airlineId = $flight->schedule->airline_id;
        $seatIds = SeatMap::where('aircraft_id', $flight->schedule->aircraft_id)
            ->where('travel_class_id', $travelClass->id)
            ->where('airline_id', $airlineId)
            ->orderBy('row_number')
            ->orderBy('column_letter')
            ->limit(2)
            ->pluck('id')
            ->all();
        if (count($seatIds) < 2) {
            $seatIds = SeatMap::where('aircraft_id', $flight->schedule->aircraft_id)
                ->where('travel_class_id', $travelClass->id)
                ->whereNull('airline_id')
                ->orderBy('row_number')
                ->orderBy('column_letter')
                ->limit(2)
                ->pluck('id')
                ->all();
        }

        app(SeatAssignmentService::class)->assignSeats($booking, $travelClass->id, $seatIds);

        $booking->refresh();
        $booking->load(['payment', 'passengers.seatAssignment', 'bookingFlights']);

        $this->assertCount(2, $booking->passengers->filter(fn ($passenger) => $passenger->seatAssignment));
        $this->assertSame((float) $booking->base_fare, (float) $booking->total_amount);

        Auth::login($user);

        $request = Request::create(route('booking.payment.process', $booking->id), 'POST', [
            'payment_method' => 'bank_transfer',
        ]);
        $request->setLaravelSession($this->app['session.store']);

        $paymentResponse = app(BookingController::class)->processPayment($request, $booking->id);

        $this->assertSame(302, $paymentResponse->getStatusCode());
        $this->assertSame(route('booking.show', $booking->id), $paymentResponse->getTargetUrl());

        $booking->refresh();
        $booking->load('payment');
        $flight->refresh();
        $seatPrice = $flight->seatPrices()->where('travel_class_id', $travelClass->id)->firstOrFail();

        $this->assertSame(BookingStatus::CONFIRMED->value, $booking->status);
        $this->assertSame(PaymentStatus::SUCCESS->value, $booking->payment?->status);
        $this->assertSame('dummy', $booking->payment?->payment_details['provider'] ?? null);
        $this->assertTrue((bool) ($booking->payment?->payment_details['inventory_reserved'] ?? false));
    }

    public function test_expired_pending_booking_cleanup_cancels_booking_and_releases_seats(): void
    {
        $user = User::factory()->create();
        $travelClass = TravelClass::where('code', 'Y')->firstOrFail();
        $flight = $this->findFlightForTravelClass($travelClass->id, 1);
        $booking = $this->createPendingBooking($user->id, $flight->id, $travelClass->id, 1);
        $passengerId = $booking->passengers->firstOrFail()->id;

        $airlineId = $flight->schedule->airline_id;
        $seat = SeatMap::where('aircraft_id', $flight->schedule->aircraft_id)
            ->where('travel_class_id', $travelClass->id)
            ->where(function ($q) use ($airlineId) {
                $q->where('airline_id', $airlineId)->orWhereNull('airline_id');
            })
            ->orderBy('row_number')
            ->orderBy('column_letter')
            ->firstOrFail();

        SeatAssignment::create([
            'booking_passenger_id' => $passengerId,
            'flight_id' => $flight->id,
            'seat_map_id' => $seat->id,
            'seat_number' => $seat->seat_number,
            'assigned_at' => now()->subMinutes(30),
        ]);

        $booking->payment()->update([
            'status' => PaymentStatus::PENDING->value,
            'payment_details' => [
                'provider' => 'dummy',
            ],
        ]);

        $booking->update([
            'expires_at' => now()->subMinute(),
        ]);

        $expired = $booking->fresh(['payment', 'passengers'])->expirePendingReservation();

        $booking->refresh();
        $booking->load('payment');

        $this->assertTrue($expired);
        $this->assertSame(BookingStatus::CANCELLED->value, $booking->status);
        $this->assertSame(PaymentStatus::EXPIRED->value, $booking->payment?->status);
        $this->assertDatabaseMissing('seat_assignments', [
            'booking_passenger_id' => $passengerId,
            'flight_id' => $flight->id,
        ]);
    }

    private function findFlightForTravelClass(int $travelClassId, int $passengerCount): Flight
    {
        return Flight::with(['schedule', 'seatPrices'])
            ->where('status', 'scheduled')
            ->where('available_seats', '>=', $passengerCount)
            ->whereHas('seatPrices', function ($query) use ($travelClassId, $passengerCount) {
                $query->where('travel_class_id', $travelClassId)
                    ->where('available_seats', '>=', $passengerCount);
            })
            ->orderBy('departure_datetime')
            ->firstOrFail();
    }

    private function createPendingBooking(int $userId, int $flightId, int $travelClassId, int $passengerCount): Booking
    {
        $flight = Flight::with('schedule')->findOrFail($flightId);
        $pricePerPassenger = (float) $flight->seatPrices()->where('travel_class_id', $travelClassId)->value('price');
        $totalAmount = $pricePerPassenger * $passengerCount;

        $booking = Booking::create([
            'user_id' => $userId,
            'contact_name' => 'Expired Booker',
            'contact_email' => 'expired@example.com',
            'contact_phone' => '081111111111',
            'total_amount' => $totalAmount,
            'base_fare' => $totalAmount,
            'total_passengers' => $passengerCount,
            'status' => BookingStatus::PENDING->value,
        ]);

        $bookingFlight = BookingFlight::create([
            'booking_id' => $booking->id,
            'flight_id' => $flight->id,
            'travel_class_id' => $travelClassId,
            'passenger_count' => $passengerCount,
            'price_per_passenger' => $pricePerPassenger,
            'total_price' => $totalAmount,
            'sequence' => 1,
        ]);

        for ($index = 1; $index <= $passengerCount; $index++) {
            BookingPassenger::create([
                'booking_id' => $booking->id,
                'booking_flight_id' => $bookingFlight->id,
                'title' => $index === 1 ? 'Mr' : 'Mrs',
                'first_name' => 'Passenger' . $index,
                'last_name' => 'Test',
                'date_of_birth' => now()->subYears(28 + $index)->toDateString(),
                'nationality' => 'ID',
                'passport_number' => 'EXP000' . $index,
            ]);
        }

        Payment::create([
            'booking_id' => $booking->id,
            'payment_code' => 'PAY-EXPIRED01',
            'amount' => $totalAmount,
            'status' => PaymentStatus::PENDING->value,
        ]);

        return $booking->fresh(['payment', 'passengers', 'bookingFlights']);
    }
}