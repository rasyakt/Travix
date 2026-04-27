<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\Payment;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApiFlightBookingForm extends Component
{
    public $flightData;
    public $searchParams;
    public $numberOfPassengers = 1;
    public $passengers = [];
    public $totalPrice = 0;
    public $contactName = '';
    public $contactEmail = '';
    public $contactPhone = '';

    protected $rules = [
        'passengers.*.title' => 'required|in:Mr,Mrs,Ms,Miss',
        'passengers.*.first_name' => 'required|string|max:255',
        'passengers.*.last_name' => 'required|string|max:255',
        'passengers.*.date_of_birth' => 'required|date|before:today',
        'passengers.*.passport_number' => 'nullable|string|max:50',
        'passengers.*.nationality' => 'required|string|max:100',
        'passengers.*.passenger_type' => 'required|in:adult,child,infant',
        'contactName' => 'required|string|max:255',
        'contactEmail' => 'required|email',
        'contactPhone' => 'required|string|max:20',
    ];

    protected $messages = [
        'passengers.*.title.required' => 'Pilih gelar untuk penumpang',
        'passengers.*.first_name.required' => 'Nama depan wajib diisi',
        'passengers.*.last_name.required' => 'Nama belakang wajib diisi',
        'passengers.*.date_of_birth.required' => 'Tanggal lahir wajib diisi',
        'passengers.*.date_of_birth.before' => 'Tanggal lahir harus sebelum hari ini',
        'passengers.*.nationality.required' => 'Kewarganegaraan wajib diisi',
        'contactName.required' => 'Nama kontak wajib diisi',
        'contactEmail.required' => 'Email kontak wajib diisi',
        'contactEmail.email' => 'Format email tidak valid',
        'contactPhone.required' => 'Nomor telepon wajib diisi',
    ];

    public function mount()
    {
        $apiBookingData = session('api_flight_booking');

        if (!$apiBookingData) {
            session()->flash('error', 'Data penerbangan tidak ditemukan. Silakan cari ulang.');
            return redirect()->route('flights.index');
        }

        // Check if booking data is expired (older than 30 minutes)
        $createdAt = $apiBookingData['created_at'] ?? null;
        if ($createdAt && now()->diffInMinutes($createdAt) > 30) {
            session()->forget('api_flight_booking');
            session()->flash('error', 'Data penerbangan sudah kadaluarsa. Silakan cari ulang untuk harga terbaru.');
            return redirect()->route('flights.index');
        }

        $this->flightData = $apiBookingData['flight_data'];
        $this->searchParams = $apiBookingData['search_params'];
        $this->numberOfPassengers = $apiBookingData['passenger_count'];

        // Validate passenger count
        if ($this->numberOfPassengers < 1 || $this->numberOfPassengers > 9) {
            session()->flash('error', 'Jumlah penumpang tidak valid.');
            return redirect()->route('flights.index');
        }

        $this->contactName = auth()->user()->name ?? '';
        $this->contactEmail = auth()->user()->email ?? '';

        // Initialize passenger array with proper types
        $adults = $this->searchParams['adults'] ?? $this->numberOfPassengers;
        $children = $this->searchParams['children'] ?? 0;
        $infants = $this->searchParams['infants'] ?? 0;

        $passengerIndex = 0;
        
        // Add adults
        for ($i = 0; $i < $adults; $i++) {
            $this->passengers[$passengerIndex++] = [
                'title' => 'Mr',
                'first_name' => '',
                'last_name' => '',
                'date_of_birth' => '',
                'passport_number' => '',
                'nationality' => 'Indonesia',
                'passenger_type' => 'adult',
            ];
        }
        
        // Add children
        for ($i = 0; $i < $children; $i++) {
            $this->passengers[$passengerIndex++] = [
                'title' => 'Miss',
                'first_name' => '',
                'last_name' => '',
                'date_of_birth' => '',
                'passport_number' => '',
                'nationality' => 'Indonesia',
                'passenger_type' => 'child',
            ];
        }
        
        // Add infants
        for ($i = 0; $i < $infants; $i++) {
            $this->passengers[$passengerIndex++] = [
                'title' => 'Miss',
                'first_name' => '',
                'last_name' => '',
                'date_of_birth' => '',
                'passport_number' => '',
                'nationality' => 'Indonesia',
                'passenger_type' => 'infant',
            ];
        }

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $pricePerPassenger = $this->flightData['price'] ?? 0;
        $this->totalPrice = $pricePerPassenger * $this->numberOfPassengers;
    }

    public function createBooking()
    {
        $this->validate();
        
        // Additional validation for passenger types
        $this->validatePassengerTypes();
        
        $transactionStarted = false;

        try {
            DB::beginTransaction();
            $transactionStarted = true;

            // Validate infant must have adult
            $infantCount = collect($this->passengers)->where('passenger_type', 'infant')->count();
            $adultCount = collect($this->passengers)->where('passenger_type', 'adult')->count();
            
            if ($infantCount > 0 && $adultCount === 0) {
                throw new \Exception('Bayi harus ditemani oleh minimal 1 orang dewasa.');
            }
            
            if ($infantCount > $adultCount) {
                throw new \Exception('Jumlah bayi tidak boleh melebihi jumlah orang dewasa.');
            }

            // Set booking expiry (30 minutes from now)
            $expiresAt = now()->addMinutes(30);

            // Create booking with API flight metadata
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'status' => BookingStatus::PENDING->value,
                'total_amount' => $this->totalPrice,
                'base_fare' => $this->totalPrice,
                'total_passengers' => $this->numberOfPassengers,
                'contact_name' => $this->contactName,
                'contact_email' => $this->contactEmail,
                'contact_phone' => $this->contactPhone,
                'expires_at' => $expiresAt,
            ]);

            // Store API flight data in booking metadata with complete information
            $apiFlightMetadata = [
                'source' => 'api_partner',
                'engine' => $this->flightData['engine'] ?? 'serpapi_google_flights',
                'flight_data' => array_merge($this->flightData, [
                    // Ensure these fields exist for dashboard display
                    'origin_code' => $this->flightData['origin_code'] ?? $this->searchParams['origin'] ?? 'N/A',
                    'destination_code' => $this->flightData['destination_code'] ?? $this->searchParams['destination'] ?? 'N/A',
                    'origin_city' => $this->flightData['origin_city'] ?? $this->searchParams['origin'] ?? 'Unknown',
                    'destination_city' => $this->flightData['destination_city'] ?? $this->searchParams['destination'] ?? 'Unknown',
                    'airline' => $this->flightData['airline'] ?? 'Partner Airline',
                    'airline_logo' => $this->flightData['airline_logo'] ?? null,
                    'departure_time' => $this->flightData['departure_time'] ?? now()->toDateTimeString(),
                    'arrival_time' => $this->flightData['arrival_time'] ?? now()->addHours(2)->toDateTimeString(),
                ]),
                'search_params' => $this->searchParams,
                'booking_reference' => 'API-' . strtoupper(Str::random(8)),
                'created_at' => now()->toDateTimeString(),
                'price_locked_at' => now()->toDateTimeString(),
                'price_locked_amount' => $this->totalPrice,
                'cancellation_policy' => [
                    'refundable' => true,
                    'refund_percentage' => 90,
                    'cancellation_deadline_hours' => 24,
                    'cancellation_fee' => $this->totalPrice * 0.1,
                ],
                'baggage_info' => [
                    'cabin' => $this->flightData['baggage_cabin'] ?? '7 kg',
                    'checked' => $this->flightData['baggage_checked'] ?? '20 kg',
                ],
            ];

            // Create passengers with API booking metadata (no booking_flight_id needed)
            foreach ($this->passengers as $index => $passengerData) {
                BookingPassenger::create([
                    'booking_id' => $booking->id,
                    'booking_flight_id' => null, // Nullable for API bookings
                    'title' => $passengerData['title'],
                    'first_name' => $passengerData['first_name'],
                    'last_name' => $passengerData['last_name'],
                    'date_of_birth' => $passengerData['date_of_birth'],
                    'passport_number' => $passengerData['passport_number'] ?: null,
                    'nationality' => $passengerData['nationality'],
                    'passenger_type' => $passengerData['passenger_type'] ?? 'adult',
                ]);
            }

            // Create payment record with API metadata
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $this->totalPrice,
                'payment_method' => null,
                'status' => PaymentStatus::PENDING->value,
                'payment_code' => 'PAY-API-' . strtoupper(Str::random(10)),
                'payment_details' => $apiFlightMetadata,
            ]);

            DB::commit();

            // Store booking ID for guest users
            if (!Auth::check()) {
                $guestBookingIds = session()->get('guest_booking_ids', []);
                session()->put('guest_booking_ids', array_values(array_unique([...$guestBookingIds, $booking->id])));
            }

            // Clear API flight data from session
            session()->forget('api_flight_booking');

            session()->flash('success', 'Booking berhasil dibuat! Selesaikan pembayaran dalam 30 menit.');
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
            
            // Cleanup session on error
            session()->forget('api_flight_booking');
            
            \Log::error('API Flight Booking Creation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'flight_data' => $this->flightData ?? null,
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            session()->flash('error', 'Gagal membuat booking: ' . $e->getMessage());
        }
    }

    protected function validatePassengerTypes()
    {
        foreach ($this->passengers as $index => $passenger) {
            $dob = \Carbon\Carbon::parse($passenger['date_of_birth']);
            $age = $dob->age;
            $type = $passenger['passenger_type'];

            // Validate age matches passenger type
            if ($type === 'infant' && $age >= 2) {
                throw ValidationException::withMessages([
                    "passengers.{$index}.date_of_birth" => 'Bayi harus berusia di bawah 2 tahun.'
                ]);
            }

            if ($type === 'child' && ($age < 2 || $age >= 12)) {
                throw ValidationException::withMessages([
                    "passengers.{$index}.date_of_birth" => 'Anak harus berusia 2-11 tahun.'
                ]);
            }

            if ($type === 'adult' && $age < 12) {
                throw ValidationException::withMessages([
                    "passengers.{$index}.date_of_birth" => 'Dewasa harus berusia minimal 12 tahun.'
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.api-flight-booking-form');
    }
}
