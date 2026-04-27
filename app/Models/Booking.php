<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'trip_type',
        'status',
        'total_amount',
        'base_fare',
        'taxes_fees',
        'baggage_fee',
        'seat_fee',
        'total_passengers',
        'contact_name',
        'contact_email',
        'contact_phone',
        'booking_date',
        'expires_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'base_fare' => 'decimal:2',
        'taxes_fees' => 'decimal:2',
        'baggage_fee' => 'decimal:2',
        'seat_fee' => 'decimal:2',
        'total_passengers' => 'integer',
        'booking_date' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = (string) Str::uuid();
            }

            if (empty($booking->booking_date)) {
                $booking->booking_date = now();
            }

            // Set expiry time (60 minutes from creation)
            if (empty($booking->expires_at) && $booking->status === 'pending') {
                $booking->expires_at = now()->addMinutes(60);
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookingFlights(): HasMany
    {
        return $this->hasMany(BookingFlight::class)->orderBy('sequence');
    }

    public function flights(): BelongsToMany
    {
        return $this->belongsToMany(Flight::class, 'booking_flights')
            ->withPivot(['travel_class_id', 'sequence', 'segment_type', 'passenger_count', 'price_per_passenger', 'total_price'])
            ->withTimestamps();
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(BookingPassenger::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }

    public function baggage(): HasManyThrough
    {
        return $this->hasManyThrough(Baggage::class, BookingPassenger::class);
    }

    public function seatAssignments(): HasManyThrough
    {
        return $this->hasManyThrough(SeatAssignment::class, BookingPassenger::class, 'booking_id', 'booking_passenger_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'pending')
            ->where('expires_at', '<', now());
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByBookingCode($query, $code)
    {
        return $query->where('booking_code', $code);
    }

    // Accessors
    public function getFlightAttribute()
    {
        return $this->flights->first();
    }

    public function getDisplayBookingCodeAttribute(): string
    {
        $normalizedCode = strtoupper(str_replace('-', '', (string) $this->booking_code));

        if ($normalizedCode === '') {
            return 'TRV-UNKNOWN';
        }

        return 'TRV-' . substr($normalizedCode, 0, 8);
    }

    public function getIsExpiredAttribute()
    {
        if ($this->status !== 'pending') {
            return false;
        }

        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getIsPaidAttribute()
    {
        return $this->payment && $this->payment->status === 'success';
    }

    public function getIsCheckedInAttribute()
    {
        return $this->checkIns()->where('status', 'completed')->exists();
    }

    public function getIsRefundableAttribute()
    {
        if ($this->status === 'cancelled') {
            return false;
        }

        // Check if first flight is more than 24 hours away
        $firstFlight = $this->relationLoaded('flights')
            ? $this->flights->first()
            : $this->bookingFlights()->with('flight')->first()?->flight;

        if (!$firstFlight) {
            return false;
        }

        $hoursUntilDeparture = now()->diffInHours($firstFlight->departure_datetime, false);

        return $hoursUntilDeparture > 24;
    }

    public function getTotalPriceWithFeesAttribute()
    {
        return $this->base_fare +
            $this->taxes_fees +
            $this->baggage_fee +
            $this->seat_fee;
    }

    // Methods
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']) &&
            !$this->is_expired;
    }

    public function canCheckIn()
    {
        return $this->check_in_blocked_reason === null;
    }

    public function getCheckInBlockedReasonAttribute(): ?string
    {
        if ($this->status !== 'confirmed') {
            return 'Check-in hanya tersedia untuk booking berstatus confirmed.';
        }

        if (!$this->is_paid) {
            return 'Check-in hanya tersedia untuk booking yang sudah dibayar.';
        }

        $firstFlight = $this->relationLoaded('flights')
            ? $this->flights->first()
            : $this->bookingFlights()->with('flight')->first()?->flight;

        if (!$firstFlight) {
            return 'Data jadwal penerbangan tidak ditemukan.';
        }

        $hoursUntilDeparture = now()->diffInHours($firstFlight->departure_datetime, false);

        if ($hoursUntilDeparture > 24) {
            return 'Online check-in baru tersedia 24 jam sebelum keberangkatan.';
        }

        if ($hoursUntilDeparture < 3) {
            return 'Online check-in ditutup 3 jam sebelum keberangkatan.';
        }

        return null;
    }

    public function calculateTotalAmount()
    {
        $base = $this->bookingFlights()->sum('total_price');
        $taxes = $base * 0.10; // 10% tax
        $baggage = $this->passengers->sum(function ($passenger) {
            return $passenger->baggage->sum('fee');
        });
        $seats = $this->passengers->sum(function ($passenger) {
            return $passenger->seatAssignments->sum('extra_fee');
        });

        $this->update([
            'base_fare' => $base,
            'taxes_fees' => $taxes,
            'baggage_fee' => $baggage,
            'seat_fee' => $seats,
            'total_amount' => $base + $taxes + $baggage + $seats,
        ]);

        return $this->total_amount;
    }

    public function markAsExpired()
    {
        return $this->expirePendingReservation();
    }

    public function expirePendingReservation(): bool
    {
        if (!$this->is_expired) {
            return false;
        }

        $paymentDetails = $this->payment?->payment_details ?? [];
        $paymentDetails['expired_at'] = now()->toDateTimeString();
        $paymentDetails['expired_reason'] = 'payment_window_elapsed';

        // FIX: Release seats back to inventory before deleting assignments
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
                    FlightSeatPrice::where('flight_id', $flight->id)
                        ->where('travel_class_id', $travelClassId)
                        ->increment('available_seats', $count);
                }
                
                // Restore Flight.available_seats
                $flight->increment('available_seats', $seatAssignments->count());
                
                $paymentDetails['seats_released'] = $seatAssignments->count();
                $paymentDetails['seats_released_at'] = now()->toDateTimeString();
            }
        }

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

    public function cancel()
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->update(['status' => 'cancelled']);

        // Cancel payment if exists
        if ($this->payment && $this->payment->status === 'pending') {
            $this->payment->update(['status' => 'cancelled']);
        }

        return true;
    }
}