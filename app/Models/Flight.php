<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'aircraft_instance_id',
        'flight_number',
        'flight_date',
        'departure_datetime',
        'arrival_datetime',
        'status',
        'available_seats',
        'current_price',
        'gate',
        'terminal',
    ];

    protected $casts = [
        'flight_date' => 'date',
        'departure_datetime' => 'datetime',
        'arrival_datetime' => 'datetime',
        'available_seats' => 'integer',
        'current_price' => 'decimal:2',
    ];

    // Relationships
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function aircraftInstance(): BelongsTo
    {
        return $this->belongsTo(AircraftInstance::class);
    }

    public function seatPrices(): HasMany
    {
        return $this->hasMany(FlightSeatPrice::class);
    }

    public function bookingFlights(): HasMany
    {
        return $this->hasMany(BookingFlight::class);
    }

    public function seatAssignments(): HasMany
    {
        return $this->hasMany(SeatAssignment::class);
    }

    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(FlightStatusLog::class)->orderBy('logged_at', 'desc');
    }

    public function baggage(): HasMany
    {
        return $this->hasMany(Baggage::class);
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled', 'arrived']);
    }

    public function scopeDeparting($query)
    {
        return $query->where('departure_datetime', '>', now());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('flight_date', today());
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('flight_date', $date);
    }

    public function scopeByRoute($query, $originId, $destinationId)
    {
        return $query->whereHas('schedule', function ($q) use ($originId, $destinationId) {
            $q->where('origin_airport_id', $originId)
              ->where('destination_airport_id', $destinationId);
        });
    }

    public function scopeAvailable($query, $seatsNeeded = 1)
    {
        return $query->where('available_seats', '>=', $seatsNeeded)
                     ->where('status', 'scheduled');
    }

    // Accessors
    public function getIsDepartedAttribute()
    {
        return $this->departure_datetime->isPast();
    }

    public function getIsArrivedAttribute()
    {
        return $this->arrival_datetime->isPast();
    }

    public function getIsDelayedAttribute()
    {
        return $this->status === 'delayed';
    }

    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }

    public function getDurationInMinutesAttribute()
    {
        return $this->departure_datetime->diffInMinutes($this->arrival_datetime);
    }

    public function getDurationFormattedAttribute()
    {
        $minutes = $this->duration_in_minutes;
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        return sprintf('%dh %dm', $hours, $mins);
    }

    public function getOriginAttribute()
    {
        return $this->schedule->originAirport;
    }

    public function getDestinationAttribute()
    {
        return $this->schedule->destinationAirport;
    }

    public function getAirlineAttribute()
    {
        return $this->schedule->airline;
    }

    public function getOccupancyRateAttribute()
    {
        $totalSeats = $this->aircraftInstance?->aircraft->typical_seating_capacity ?? 0;
        
        if ($totalSeats === 0) {
            return 0;
        }

        $occupiedSeats = $totalSeats - $this->available_seats;
        
        return round(($occupiedSeats / $totalSeats) * 100, 2);
    }

    // Methods
    public function updateStatus($newStatus, $reason = null)
    {
        $oldStatus = $this->status;

        if ($oldStatus === $newStatus) {
            return false;
        }

        $this->update(['status' => $newStatus]);

        // Log status change
        $this->statusLogs()->create([
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'old_departure_time' => $this->departure_datetime,
            'new_departure_time' => $this->departure_datetime,
            'old_arrival_time' => $this->arrival_datetime,
            'new_arrival_time' => $this->arrival_datetime,
            'reason' => $reason,
            'updated_by' => 'system',
            'logged_at' => now(),
        ]);

        return true;
    }

    public function delay($minutes, $reason = null)
    {
        $oldDeparture = $this->departure_datetime;
        $oldArrival = $this->arrival_datetime;

        $newDeparture = $oldDeparture->addMinutes($minutes);
        $newArrival = $oldArrival->addMinutes($minutes);

        $this->update([
            'departure_datetime' => $newDeparture,
            'arrival_datetime' => $newArrival,
            'status' => 'delayed',
        ]);

        // Log the delay
        $this->statusLogs()->create([
            'old_status' => $this->getOriginal('status'),
            'new_status' => 'delayed',
            'old_departure_time' => $oldDeparture,
            'new_departure_time' => $newDeparture,
            'old_arrival_time' => $oldArrival,
            'new_arrival_time' => $newArrival,
            'reason' => $reason ?? "Delayed by {$minutes} minutes",
            'updated_by' => 'system',
            'logged_at' => now(),
        ]);

        return true;
    }

    public function getPriceForClass($travelClassId)
    {
        $seatPrice = $this->seatPrices()
            ->where('travel_class_id', $travelClassId)
            ->first();

        if ($seatPrice) {
            return $seatPrice->price;
        }

        // Fallback to base price with multiplier
        $travelClass = TravelClass::find($travelClassId);
        
        return $this->current_price * ($travelClass->price_multiplier ?? 1);
    }

    public function getAvailableSeatsForClass($travelClassId)
    {
        $seatPrice = $this->seatPrices()
            ->where('travel_class_id', $travelClassId)
            ->first();

        return $seatPrice?->available_seats ?? 0;
    }

    public function decreaseAvailableSeats($count = 1, $travelClassId = null)
    {
        if ($this->available_seats < $count) {
            return false;
        }

        $this->decrement('available_seats', $count);

        if ($travelClassId) {
            $seatPrice = $this->seatPrices()
                ->where('travel_class_id', $travelClassId)
                ->first();

            if ($seatPrice instanceof FlightSeatPrice && $seatPrice->available_seats >= $count) {
                $seatPrice->decrement('available_seats', $count);
            }
        }

        return true;
    }

    public function increaseAvailableSeats($count = 1, $travelClassId = null)
    {
        $this->increment('available_seats', $count);

        if ($travelClassId) {
            $seatPrice = $this->seatPrices()
                ->where('travel_class_id', $travelClassId)
                ->first();

            if ($seatPrice instanceof FlightSeatPrice) {
                $seatPrice->increment('available_seats', $count);
            }
        }

        return true;
    }

    public function canCheckIn()
    {
        if ($this->status !== 'scheduled') {
            return false;
        }

        $hoursUntilDeparture = now()->diffInHours($this->departure_datetime, false);
        
        // Check-in window: 24 hours to 3 hours before departure
        return $hoursUntilDeparture <= 24 && $hoursUntilDeparture >= 3;
    }
}