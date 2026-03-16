<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Flight;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\TravelClass;
use App\Models\Payment;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\BookingFlight;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BookingForm extends Component
{
    public $flight;
    public $travelClasses = [];
    public $selectedClassId;
    public $passengers = [];
    public $numberOfPassengers = 1;
    public $totalPrice = 0;
    public $contactName = '';
    public $contactEmail = '';
    public $contactPhone = '';

    protected $rules = [
        'selectedClassId' => 'required|exists:travel_classes,id',
        'passengers.*.first_name' => 'required|string|max:255',
        'passengers.*.last_name' => 'required|string|max:255',
        'passengers.*.date_of_birth' => 'required|date|before:today',
        'passengers.*.passport_number' => 'nullable|string|max:50',
        'passengers.*.nationality' => 'required|string|max:100',
        'contactName' => 'required|string|max:255',
        'contactEmail' => 'required|email',
        'contactPhone' => 'required|string|max:20',
    ];

    public function mount($flightId, $passengerCount = 1)
    {
        $this->flight = Flight::with(['schedule.airline', 'schedule.originAirport', 'schedule.destinationAirport', 'seatPrices.travelClass'])
            ->findOrFail($flightId);

        $this->numberOfPassengers = max(1, min(9, $passengerCount));
        $this->contactName = auth()->user()->name ?? '';
        $this->contactEmail = auth()->user()->email ?? '';

        // Initialize passenger array
        for ($i = 0; $i < $this->numberOfPassengers; $i++) {
            $this->passengers[] = [
                'first_name' => '',
                'last_name' => '',
                'date_of_birth' => '',
                'passport_number' => '',
                'nationality' => '',
            ];
        }

        // Load available travel classes for this flight
        $this->travelClasses = $this->flight->seatPrices()
            ->with('travelClass')
            ->get()
            ->map(function ($price) {
                return [
                    'id' => $price->travel_class_id,
                    'name' => $price->travelClass->name,
                    'price' => $price->price,
                    'available_seats' => $price->available_seats,
                ];
            });

        if ($this->travelClasses->isNotEmpty()) {
            $this->selectedClassId = $this->travelClasses->first()['id'];
            $this->calculateTotal();
        }
    }

    public function updatedSelectedClassId()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $selectedClass = $this->travelClasses->firstWhere('id', $this->selectedClassId);
        if ($selectedClass) {
            $this->totalPrice = $selectedClass['price'] * $this->numberOfPassengers;
        }
    }

    public function createBooking()
    {
        $this->validate();
        $transactionStarted = false;

        try {
            $selectedClass = $this->travelClasses->firstWhere('id', $this->selectedClassId);

            if (!$selectedClass) {
                throw ValidationException::withMessages([
                    'selectedClassId' => 'Pilih kelas penerbangan yang tersedia.',
                ]);
            }

            if ((int) $selectedClass['available_seats'] < $this->numberOfPassengers) {
                throw ValidationException::withMessages([
                    'selectedClassId' => 'Kursi tersedia untuk kelas ini tidak cukup untuk jumlah penumpang Anda.',
                ]);
            }

            DB::beginTransaction();
            $transactionStarted = true;

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'status' => BookingStatus::PENDING->value,
                'total_amount' => $this->totalPrice,
                'base_fare' => $this->totalPrice, // Simplified for now
                'total_passengers' => $this->numberOfPassengers,
                'contact_name' => $this->contactName,
                'contact_email' => $this->contactEmail,
                'contact_phone' => $this->contactPhone,
            ]);

            // Attach flight to booking via BookingFlight
            $bookingFlight = BookingFlight::create([
                'booking_id' => $booking->id,
                'flight_id' => $this->flight->id,
                'travel_class_id' => $this->selectedClassId,
                'passenger_count' => $this->numberOfPassengers,
                'price_per_passenger' => $this->totalPrice / $this->numberOfPassengers,
                'total_price' => $this->totalPrice,
                'sequence' => 1,
            ]);

            // Create passengers
            foreach ($this->passengers as $passengerData) {
                BookingPassenger::create([
                    'booking_id' => $booking->id,
                    'booking_flight_id' => $bookingFlight->id,
                    'first_name' => $passengerData['first_name'],
                    'last_name' => $passengerData['last_name'],
                    'date_of_birth' => $passengerData['date_of_birth'],
                    'passport_number' => $passengerData['passport_number'],
                    'nationality' => $passengerData['nationality'],
                ]);
            }

            // Create payment record
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $this->totalPrice,
                'payment_method' => null, // Set during payment process
                'status' => PaymentStatus::PENDING->value,
                'payment_code' => 'PAY-' . strtoupper(Str::random(10)),
            ]);

            DB::commit();

            if (!Auth::check()) {
                $guestBookingIds = session()->get('guest_booking_ids', []);
                session()->put('guest_booking_ids', array_values(array_unique([...$guestBookingIds, $booking->id])));
            }

            session()->flash('success', 'Booking created successfully!');
            return redirect()->route('booking.payment', $booking->id);

        } catch (ValidationException $e) {
            if ($transactionStarted) {
                DB::rollBack();
            }
            throw $e;

        } catch (\Exception $e) {
            if ($transactionStarted) {
                DB::rollBack();
            }
            session()->flash('error', 'Failed to create booking. Please try again.');
            \Log::error('Booking Creation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.booking-form');
    }
}
