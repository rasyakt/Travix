# Critical Fixes Implementation Guide

## Fix #1: Inventory Leak pada Booking Expiry

### Problem:
Ketika booking expired, seats dihapus tetapi `Flight.available_seats` tidak dikembalikan.

### Solution:
Update `Booking::expirePendingReservation()` method:

```php
// app/Models/Booking.php
public function expirePendingReservation(): bool
{
    if (!$this->is_expired) {
        return false;
    }

    $paymentDetails = $this->payment?->payment_details ?? [];
    $paymentDetails['expired_at'] = now()->toDateTimeString();
    $paymentDetails['expired_reason'] = 'payment_window_elapsed';

    // 🔧 FIX: Release seats back to inventory
    $seatAssignments = $this->seatAssignments()->with('seatMap')->get();
    
    if ($seatAssignments->isNotEmpty()) {
        $flight = $this->flights->first();
        
        if ($flight) {
            // Group by travel class to update FlightSeatPrice
            $seatsByClass = $seatAssignments->groupBy(function($assignment) {
                return $assignment->seatMap->travel_class_id;
            });
            
            foreach ($seatsByClass as $travelClassId => $seats) {
                $count = $seats->count();
                
                // Restore FlightSeatPrice.available_seats
                \App\Models\FlightSeatPrice::where('flight_id', $flight->id)
                    ->where('travel_class_id', $travelClassId)
                    ->increment('available_seats', $count);
            }
            
            // Restore Flight.available_seats
            $flight->increment('available_seats', $seatAssignments->count());
            
            $paymentDetails['seats_released'] = $seatAssignments->count();
            $paymentDetails['seats_released_at'] = now()->toDateTimeString();
        }
    }

    // Delete seat assignments
    $this->seatAssignments()->delete();

    if ($this->payment && in_array($this->payment->status, ['pending', 'processing', 'failed'], true)) {
        $this->payment->update([
            'status' => PaymentStatus::EXPIRED->value,
            'expires_at' => now(),
            'payment_details' => $paymentDetails,
        ]);
    }

    $this->update([
        'status' => 'cancelled',
    ]);

    return true;
}
```

---

## Fix #2: Race Condition pada Payment Processing

### Problem:
Inventory reservation terjadi setelah payment creation, bisa menyebabkan overbooking.

### Solution:
Add validation dan lock di `BookingController::processPayment()`:

```php
// app/Http/Controllers/BookingController.php
public function processPayment(Request $request, $id)
{
    $request->validate([
        'payment_method' => 'required|in:credit_card,bank_transfer,e_wallet',
    ]);

    try {
        return DB::transaction(function () use ($request, $id) {
            // 🔧 FIX: Lock booking AND flight for update
            $booking = Booking::with([
                'flights',
                'passengers.seatAssignment.seatMap',
                'payment',
                'bookingFlights'
            ])
            ->where('id', $id)
            ->lockForUpdate()
            ->firstOrFail();

            // Check authorization
            if (Auth::check() && $booking->user_id !== Auth::id()) {
                abort(403);
            }

            if (!Auth::check() && !in_array($id, session()->get('guest_booking_ids', []))) {
                return redirect()->route('login')
                    ->with('info', 'Please login to complete payment.');
            }

            // Check expiry
            if ($booking->expirePendingReservation()) {
                return redirect()->route('booking.show', $booking->id)
                    ->with('info', 'Booking expired. Please create a new booking.');
            }

            // 🔧 FIX: Validate inventory BEFORE payment
            $isApiBooking = $booking->flights->isEmpty() && 
                           $booking->payment && 
                           isset($booking->payment->payment_details['source']) && 
                           $booking->payment->payment_details['source'] === 'api_partner';

            if (!$isApiBooking) {
                // Validate all passengers have seats
                if ($booking->passengers->contains(fn($passenger) => !$passenger->seatAssignment)) {
                    return redirect()->route('booking.seats', $booking->id)
                        ->with('error', 'Pilih kursi untuk semua penumpang sebelum melanjutkan pembayaran.');
                }

                // 🔧 FIX: Lock flight and validate available seats
                $flight = Flight::where('id', $booking->flights->first()->id)
                    ->lockForUpdate()
                    ->first();

                $requiredSeats = $booking->passengers->count();
                
                if ($flight->available_seats < $requiredSeats) {
                    // Release current seat assignments
                    $booking->seatAssignments()->delete();
                    
                    return redirect()->route('booking.seats', $booking->id)
                        ->with('error', 'Kursi tidak tersedia lagi. Silakan pilih kursi lain.');
                }
            }

            // Continue with payment processing...
            // (rest of the method)
        });
    } catch (\Exception $e) {
        \Log::error('Payment Processing Error', [
            'booking_id' => $id,
            'error' => $e->getMessage()
        ]);
        
        return redirect()->route('booking.payment', $id)
            ->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
    }
}
```

---

## Fix #3: Passenger Count Validation

### Problem:
Tidak ada validasi bahwa jumlah passenger sama dengan yang dideklarasikan.

### Solution:
Add validation di `StoreBookingRequest`:

```php
// app/Http/Requests/StoreBookingRequest.php
public function rules()
{
    return [
        'flight_id' => 'required|exists:flights,id',
        'contact_name' => 'required|string|max:255',
        'contact_email' => 'required|email|max:255',
        'contact_phone' => 'required|string|max:20',
        'passengers' => 'required|array|min:1|max:9',
        'passengers.*.title' => 'required|in:Mr,Mrs,Ms,Miss,Dr',
        'passengers.*.first_name' => 'required|string|max:255',
        'passengers.*.last_name' => 'required|string|max:255',
        'passengers.*.date_of_birth' => 'required|date|before:today',
        'passengers.*.nationality' => 'required|string|size:2',
        'passengers.*.passport_number' => 'nullable|string|max:50',
        'passengers.*.travel_class_id' => 'required|exists:travel_classes,id',
    ];
}

// 🔧 FIX: Add custom validation
public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $passengers = $this->input('passengers', []);
        
        // Validate passenger count
        if (count($passengers) < 1 || count($passengers) > 9) {
            $validator->errors()->add('passengers', 'Jumlah penumpang harus antara 1-9 orang.');
        }
        
        // 🔧 FIX: Check for duplicate passenger names
        $names = [];
        foreach ($passengers as $index => $passenger) {
            $fullName = ($passenger['first_name'] ?? '') . ' ' . ($passenger['last_name'] ?? '');
            $dob = $passenger['date_of_birth'] ?? '';
            $key = strtolower($fullName) . '|' . $dob;
            
            if (in_array($key, $names)) {
                $validator->errors()->add(
                    "passengers.{$index}", 
                    "Penumpang dengan nama dan tanggal lahir yang sama sudah ada."
                );
            }
            
            $names[] = $key;
        }
        
        // Validate all passengers have same travel class (for now)
        $travelClasses = array_unique(array_column($passengers, 'travel_class_id'));
        if (count($travelClasses) > 1) {
            $validator->errors()->add('passengers', 'Semua penumpang harus memilih kelas perjalanan yang sama.');
        }
    });
}
```

---

## Fix #4: API Booking Seat Validation

### Problem:
Validasi seat assignment tidak check apakah booking dari API atau database.

### Solution:
Update validation di `BookingController::processPayment()`:

```php
// 🔧 FIX: Check if API booking before validating seats
$isApiBooking = $booking->flights->isEmpty() && 
               $booking->payment && 
               isset($booking->payment->payment_details['source']) && 
               $booking->payment->payment_details['source'] === 'api_partner';

// Only validate seats for database flights
if (!$isApiBooking) {
    if ($booking->passengers->contains(fn($passenger) => !$passenger->seatAssignment)) {
        return redirect()->route('booking->seats', $booking->id)
            ->with('error', 'Pilih kursi untuk semua penumpang sebelum melanjutkan pembayaran.');
    }
}
```

---

## Fix #5: Calculate Seat Extra Fees

### Problem:
Premium seat extra fees tidak dihitung.

### Solution:
Update `SeatAssignmentService::assignSeats()`:

```php
// app/Services/SeatAssignmentService.php
public function assignSeats(Booking $booking, int $travelClassId, array $selectedSeatIds): void
{
    DB::transaction(function () use ($booking, $travelClassId, $selectedSeatIds) {
        // ... existing validation code ...

        // 🔧 FIX: Calculate total with extra fees
        $totalSeatFee = 0;
        
        foreach ($passengerIds as $index => $passengerId) {
            $seatMapId = $selectedSeatIds[$index];
            $seatMap = $seatMapById->get($seatMapId);
            
            if (!$seatMap) {
                throw new RuntimeException("Seat map {$seatMapId} not found.");
            }

            // Delete existing assignment
            SeatAssignment::where('booking_passenger_id', $passengerId)->delete();

            // Create new assignment
            SeatAssignment::create([
                'booking_passenger_id' => $passengerId,
                'flight_id' => $flight->id,
                'seat_map_id' => $seatMapId,
                'extra_fee' => $seatMap->extra_price ?? 0, // 🔧 FIX: Store extra fee
                'assigned_at' => now(),
            ]);
            
            // 🔧 FIX: Add to total
            $totalSeatFee += ($seatMap->extra_price ?? 0);
        }

        // Update booking totals
        $baseFare = $bookingFlight->total_price;
        $taxesFees = $baseFare * 0.10;
        
        $lockedBooking->update([
            'base_fare' => $baseFare,
            'taxes_fees' => $taxesFees,
            'seat_fee' => $totalSeatFee, // 🔧 FIX: Update seat fee
            'total_amount' => $baseFare + $taxesFees + $totalSeatFee,
        ]);

        // Update payment amount if exists
        if ($lockedBooking->payment && 
            in_array($lockedBooking->payment->status, ['pending', 'failed', 'processing'])) {
            $lockedBooking->payment->update([
                'amount' => $lockedBooking->total_amount,
            ]);
        }
    });
}
```

---

## Fix #6: Guest Booking Session Persistence

### Problem:
Guest booking IDs di session bisa hilang.

### Solution:
Add database table untuk guest bookings:

```php
// database/migrations/2026_04_27_create_guest_bookings_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('session_id')->index();
            $table->string('email')->index();
            $table->string('booking_code')->index();
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->unique(['booking_id', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_bookings');
    }
};
```

Create model:

```php
// app/Models/GuestBooking.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestBooking extends Model
{
    protected $fillable = [
        'booking_id',
        'session_id',
        'email',
        'booking_code',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
```

Update BookingController:

```php
// Store guest booking in database
if (!Auth::check()) {
    GuestBooking::create([
        'booking_id' => $booking->id,
        'session_id' => session()->getId(),
        'email' => $booking->contact_email,
        'booking_code' => $booking->booking_code,
        'expires_at' => now()->addDays(30),
    ]);
}

// Check guest access
if (!Auth::check()) {
    $hasAccess = GuestBooking::where('booking_id', $id)
        ->where(function($q) {
            $q->where('session_id', session()->getId())
              ->orWhere('email', request()->input('email'));
        })
        ->active()
        ->exists();
        
    if (!$hasAccess) {
        return redirect()->route('login')
            ->with('info', 'Please login or enter your email to view booking.');
    }
}
```

---

## Fix #7: Refund FlightSeatPrice Inventory

### Problem:
Refund tidak mengembalikan per-class seat inventory.

### Solution:
Update `BookingController::refund()`:

```php
// 🔧 FIX: Restore per-class inventory
$seatAssignments = $booking->seatAssignments()->with('seatMap')->get();

if ($seatAssignments->isNotEmpty()) {
    $flight = $booking->flights->first();
    
    // Group by travel class
    $seatsByClass = $seatAssignments->groupBy(function($assignment) {
        return $assignment->seatMap->travel_class_id;
    });
    
    foreach ($seatsByClass as $travelClassId => $seats) {
        $count = $seats->count();
        
        // Restore FlightSeatPrice.available_seats
        FlightSeatPrice::where('flight_id', $flight->id)
            ->where('travel_class_id', $travelClassId)
            ->increment('available_seats', $count);
    }
    
    // Restore Flight.available_seats
    $flight->increment('available_seats', $seatAssignments->count());
}
```

---

## Additional Recommendations

### 1. Add Scheduled Job for Auto-Expiry
```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        Booking::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->chunk(100, function ($bookings) {
                foreach ($bookings as $booking) {
                    $booking->expirePendingReservation();
                }
            });
    })->everyMinute();
}
```

### 2. Add Logging
```php
// Add to all critical operations
\Log::info('Booking Created', ['booking_id' => $booking->id]);
\Log::info('Payment Processed', ['booking_id' => $booking->id, 'status' => $payment->status]);
\Log::info('Seats Assigned', ['booking_id' => $booking->id, 'seats' => $selectedSeatIds]);
\Log::info('Booking Refunded', ['booking_id' => $booking->id, 'amount' => $refundAmount]);
```

### 3. Add Database Indexes
```php
Schema::table('booking_passengers', function (Blueprint $table) {
    $table->index('booking_id');
});

Schema::table('seat_assignments', function (Blueprint $table) {
    $table->index('flight_id');
});

Schema::table('payments', function (Blueprint $table) {
    $table->index('status');
});
```

---

## Testing Checklist

- [ ] Test booking expiry releases inventory correctly
- [ ] Test concurrent seat selection (2 users, same seat)
- [ ] Test passenger count validation
- [ ] Test API booking flow (no seat selection)
- [ ] Test seat extra fee calculation
- [ ] Test guest booking persistence across sessions
- [ ] Test refund restores all inventory
- [ ] Test payment race condition protection
- [ ] Load test: 100 concurrent bookings
- [ ] Load test: Webhook burst (100 requests/second)
