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
        $flightId = (int) request('flight');
        $passengers = max(1, (int) request('passengers', 1));

        if ($flightId <= 0) {
            return redirect()->route('flights.index')
                ->with('error', 'Penerbangan tidak valid. Silakan pilih penerbangan yang tersedia.');
        }

        $flight = Flight::find($flightId);

        if (!$flight) {
            return redirect()->route('flights.index')
                ->with('error', 'Penerbangan tidak ditemukan atau sudah tidak tersedia.');
        }

        return view('bookings.create', compact('flightId', 'passengers'));
    }

    public function store(StoreBookingRequest $request)
    {
        try {
            DB::beginTransaction();

            $flight = Flight::with('schedule')->findOrFail($request->flight_id);
            $travelClassId = $request->passengers[0]['travel_class_id'] ?? 1; // Fallback to first passenger's class

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'contact_name' => $request->contact_name,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'total_amount' => 0,
                'base_fare' => 0,
                'total_passengers' => count($request->passengers),
                'status' => BookingStatus::PENDING->value,
            ]);

            $totalAmount = 0;
            $seatPrice = $flight->seatPrices()
                ->where('travel_class_id', $travelClassId)
                ->first();
            $pricePerPax = $seatPrice?->price ?? ($flight->schedule?->base_price ?? 500000);
            $totalAmount = $pricePerPax * count($request->passengers);

            // Create booking-flight relation
            $bookingFlight = BookingFlight::create([
                'booking_id' => $booking->id,
                'flight_id' => $flight->id,
                'travel_class_id' => $travelClassId,
                'passenger_count' => count($request->passengers),
                'price_per_passenger' => $pricePerPax,
                'total_price' => $totalAmount,
                'sequence' => 1,
            ]);

            // Create passengers
            foreach ($request->passengers as $passengerData) {
                BookingPassenger::create([
                    'booking_id' => $booking->id,
                    'booking_flight_id' => $bookingFlight->id,
                    'title' => $passengerData['title'] ?? 'Mr',
                    'first_name' => $passengerData['first_name'],
                    'last_name' => $passengerData['last_name'],
                    'date_of_birth' => $passengerData['date_of_birth'],
                    'nationality' => $passengerData['nationality'],
                    'passport_number' => $passengerData['passport_number'] ?? null,
                ]);
            }

            // Update total amount
            $booking->update([
                'total_amount' => $totalAmount,
                'base_fare' => $totalAmount
            ]);

            // Create payment record
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $totalAmount,
                'status' => PaymentStatus::PENDING->value,
            ]);

            DB::commit();

            // Store booking ID in session for guest users to allow access to show/payment/seats
            if (!Auth::check()) {
                session()->put('guest_booking_ids', array_merge(session()->get('guest_booking_ids', []), [$booking->id]));
            }

            return redirect()->route('booking.seats', $booking->id)
                ->with('success', 'Booking created successfully. Please select your seats.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $query = Booking::with([
            'flights.schedule.originAirport',
            'flights.schedule.destinationAirport',
            'flights.schedule.airline',
            'passengers.seatAssignment',
            'passengers.boardingPass.checkIn',
            'passengers.baggage',
            'payment'
        ]);

        if (Auth::check()) {
            $booking = $query->where('user_id', Auth::id())->findOrFail($id);
        } else {
            // Check if this booking ID is in the guest session
            if (!in_array($id, session()->get('guest_booking_ids', []))) {
                return redirect()->route('login')->with('info', 'Please login to view your booking.');
            }
            $booking = $query->findOrFail($id);
        }

        return view('bookings.show', compact('booking'));
    }

    public function payment($id)
    {
        $query = Booking::with(['flights', 'payment']);

        if (Auth::check()) {
            $booking = $query->where('user_id', Auth::id())->findOrFail($id);
        } else {
            if (!in_array($id, session()->get('guest_booking_ids', []))) {
                return redirect()->route('login')->with('info', 'Please login to proceed with payment.');
            }
            $booking = $query->findOrFail($id);
        }

        return view('bookings.payment', compact('booking'));
    }

    public function processPayment(Request $request, $id)
    {
        // processPayment is still protected by 'auth' middleware in routes/web.php
        // but it's good to be explicit here
        $booking = Booking::with('payment')
            ->where('id', $id)
            ->where(function ($query) use ($id) {
                $query->where('user_id', Auth::id());

                if (in_array($id, session()->get('guest_booking_ids', []))) {
                    $query->orWhereNull('user_id');
                }
            })
            ->firstOrFail();

        if (is_null($booking->user_id)) {
            $booking->update(['user_id' => Auth::id()]);
            $guestBookingIds = session()->get('guest_booking_ids', []);
            session()->put('guest_booking_ids', array_values(array_filter($guestBookingIds, fn($bookingId) => (int) $bookingId !== (int) $id)));
        }

        $request->validate([
            'payment_method' => 'required|in:credit_card,bank_transfer,e_wallet',
        ]);

        try {
            if (!$booking->payment) {
                $booking->payment()->create([
                    'amount' => $booking->total_amount,
                    'status' => PaymentStatus::PENDING->value,
                    'payment_code' => 'PAY-' . strtoupper(Str::random(10)),
                ]);
                $booking->refresh();
            }

            // Mock payment processing
            $booking->payment->update([
                'payment_method' => $request->payment_method,
                'status' => PaymentStatus::SUCCESS->value,
                'paid_at' => now(),
            ]);

            $booking->update([
                'status' => 'confirmed',
            ]);

            return redirect()->route('booking.show', $booking->id)
                ->with('success', 'Payment successful! Your booking is confirmed.');

        } catch (\Exception $e) {
            \Log::error('Payment Processing Error', [
                'booking_id' => $id,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);
            return back()->with('error', 'Payment failed. Please try again.');
        }
    }

    public function refund($id)
    {
        try {
            $booking = Booking::with(['payment', 'bookingFlights.flight', 'passengers.checkIn'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            if (!$booking->payment || $booking->payment->status !== PaymentStatus::SUCCESS->value) {
                return back()->with('error', 'Refund hanya tersedia untuk booking yang sudah dibayar.');
            }

            if (!$booking->is_refundable) {
                return back()->with('error', 'Refund hanya dapat diproses paling lambat 24 jam sebelum keberangkatan.');
            }

            if ($booking->passengers->contains(fn($passenger) => $passenger->checkIn)) {
                return back()->with('error', 'Refund tidak dapat diproses karena ada penumpang yang sudah check-in.');
            }

            $existingPaymentDetails = $booking->payment->payment_details ?? [];
            if (!empty($existingPaymentDetails['refund'])) {
                return back()->with('info', 'Refund untuk booking ini sudah pernah diproses.');
            }

            DB::transaction(function () use ($booking, $existingPaymentDetails) {
                foreach ($booking->bookingFlights as $bookingFlight) {
                    if ($bookingFlight->flight) {
                        $bookingFlight->flight->increaseAvailableSeats(
                            (int) ($bookingFlight->passenger_count ?? 1),
                            $bookingFlight->travel_class_id
                        );
                    }
                }

                $refundAmount = (float) round((float) $booking->total_amount * 0.9, 2);
                $paymentDetails = $existingPaymentDetails;
                $paymentDetails['refund'] = [
                    'requested_at' => now()->toDateTimeString(),
                    'amount' => $refundAmount,
                    'currency' => 'IDR',
                    'policy' => '90_percent_refund',
                    'reason' => 'user_requested',
                ];

                $booking->payment->update([
                    'status' => PaymentStatus::CANCELLED->value,
                    'payment_details' => $paymentDetails,
                    'notes' => 'Refund approved via self-service flow.',
                ]);

                $booking->update([
                    'status' => BookingStatus::CANCELLED->value,
                    'expires_at' => now(),
                ]);
            });

            return redirect()->route('booking.show', $booking->id)
                ->with('success', 'Refund berhasil diproses. Dana akan dikembalikan sesuai kebijakan refund.');

        } catch (\Exception $e) {
            \Log::error('Refund Processing Error', [
                'booking_id' => $id,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', 'Refund gagal diproses. Silakan coba lagi.');
        }
    }

    public function selectSeats($id)
    {
        if (Auth::check()) {
            $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        } else {
            if (!in_array($id, session()->get('guest_booking_ids', []))) {
                return redirect()->route('login')->with('info', 'Please login to select seats.');
            }
            $booking = Booking::findOrFail($id);
        }
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

        // Calculate baggage fee (25.000 IDR per kg)
        $fee = $request->weight * 25000;

        Baggage::create([
            'booking_passenger_id' => $request->passenger_id,
            'weight' => $request->weight,
            'type' => $request->type,
            'fee' => $fee,
        ]);

        // Update booking total
        $booking->increment('total_amount', $fee);

        return back()->with('success', 'Baggage added successfully.');
    }

    public function boardingPass($id)
    {
        $booking = Booking::with([
            'passengers.checkIn.boardingPass',
            'passengers.seatAssignment',
            'flights.schedule.originAirport',
            'flights.schedule.destinationAirport',
            'flights.schedule.airline'
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
            if ($booking->payment && $booking->payment->status === PaymentStatus::SUCCESS->value) {
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
