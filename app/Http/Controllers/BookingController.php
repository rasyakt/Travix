<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\BookingFlight;
use App\Models\Flight;
use App\Models\Baggage;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use App\Enums\BookingStatus;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function create()
    {
        $flightId = request('flight');
        $passengers = request('passengers', 1);

        return view('bookings.create', compact('flightId', 'passengers'));
    }

    public function store(StoreBookingRequest $request)
    {
        try {
            DB::beginTransaction();

            $flight = Flight::with('schedule')->findOrFail($request->flight_id);

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'booking_code' => strtoupper(Str::random(6)),
                'contact_name' => $request->contact_name,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'total_price' => 0,
                'status' => BookingStatus::PENDING->value,
            ]);

            // Create booking-flight relation
            BookingFlight::create([
                'booking_id' => $booking->id,
                'flight_id' => $flight->id,
            ]);

            $totalPrice = 0;

            // Create passengers
            foreach ($request->passengers as $passengerData) {
                $seatPrice = $flight->seatPrices()
                    ->where('travel_class_id', $passengerData['travel_class_id'])
                    ->first();

                $price = $seatPrice?->price ?? ($flight->schedule?->base_price ?? 500000);
                $totalPrice += $price;

                BookingPassenger::create([
                    'booking_id' => $booking->id,
                    'travel_class_id' => $passengerData['travel_class_id'],
                    'title' => $passengerData['title'],
                    'first_name' => $passengerData['first_name'],
                    'last_name' => $passengerData['last_name'],
                    'date_of_birth' => $passengerData['date_of_birth'],
                    'nationality' => $passengerData['nationality'],
                    'passport_number' => $passengerData['passport_number'] ?? null,
                    'ticket_price' => $price,
                ]);
            }

            // Update total price
            $booking->update(['total_price' => $totalPrice]);

            // Create payment record
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $totalPrice,
                'status' => PaymentStatus::PENDING->value,
            ]);

            DB::commit();

            return redirect()->route('booking.seats', $booking->id)
                ->with('success', 'Booking created successfully. Please select your seats.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $booking = Booking::with([
            'flights.originAirport',
            'flights.destinationAirport',
            'flights.airline',
            'passengers.seatAssignment',
            'passengers.checkIn.boardingPass',
            'passengers.baggage',
            'payment'
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    public function payment($id)
    {
        $booking = Booking::with(['flights', 'payment'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('bookings.payment', compact('booking'));
    }

    public function processPayment(Request $request, $id)
    {
        $booking = Booking::with('payment')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'payment_method' => 'required|in:credit_card,bank_transfer,e_wallet',
        ]);

        try {
            // Mock payment processing
            $booking->payment->update([
                'payment_method' => $request->payment_method,
                'status' => PaymentStatus::PAID->value,
                'paid_at' => now(),
            ]);

            $booking->update([
                'status' => 'confirmed',
            ]);

            return redirect()->route('booking.show', $booking->id)
                ->with('success', 'Payment successful! Your booking is confirmed.');

        } catch (\Exception $e) {
            return back()->with('error', 'Payment failed. Please try again.');
        }
    }

    public function selectSeats($id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        return view('bookings.seats', compact('booking'));
    }

    public function checkIn($id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        return view('bookings.checkin', compact('booking'));
    }

    public function addBaggage(Request $request, $id)
    {
        $request->validate([
            'passenger_id' => 'required|exists:booking_passengers,id',
            'weight' => 'required|numeric|min:1|max:50',
            'type' => 'required|in:checked,cabin',
        ]);

        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);

        // Calculate baggage fee (example: $10 per kg)
        $fee = $request->weight * 10;

        Baggage::create([
            'booking_passenger_id' => $request->passenger_id,
            'weight' => $request->weight,
            'type' => $request->type,
            'fee' => $fee,
        ]);

        // Update booking total
        $booking->increment('total_price', $fee);

        return back()->with('success', 'Baggage added successfully.');
    }

    public function boardingPass($id)
    {
        $booking = Booking::with([
            'passengers.checkIn.boardingPass',
            'passengers.seatAssignment',
            'flights.originAirport',
            'flights.destinationAirport',
            'flights.airline'
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('bookings.boarding-pass', compact('booking'));
    }

    public function cancel($id)
    {
        try {
            $booking = Booking::with('payment')
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            // Only allow cancellation if payment is pending or failed
            if ($booking->payment && in_array($booking->payment->status, [PaymentStatus::PAID->value, PaymentStatus::REFUNDED->value])) {
                return back()->with('error', 'Cannot cancel paid bookings. Please contact customer service.');
            }

            $booking->update([
                'status' => BookingStatus::CANCELLED->value,
            ]);

            $booking->payment?->update([
                'status' => PaymentStatus::CANCELLED->value,
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Booking cancelled successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }
}
