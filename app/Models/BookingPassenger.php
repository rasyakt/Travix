<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BookingPassenger extends Model
{
    protected $fillable = [
        'booking_id',
        'booking_flight_id',
        'title',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'nationality',
        'passport_number',
        'passport_expiry',
        'id_number',
        'passenger_type',
        'special_assistance'
    ];

    protected $casts = ['date_of_birth' => 'date', 'passport_expiry' => 'date'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function bookingFlight()
    {
        return $this->belongsTo(BookingFlight::class);
    }
    public function seatAssignments()
    {
        return $this->hasMany(SeatAssignment::class, 'booking_passenger_id');
    }
    public function seatAssignment()
    {
        return $this->hasOne(SeatAssignment::class, 'booking_passenger_id');
    }
    public function checkIn()
    {
        return $this->hasOneThrough(
            CheckIn::class,
            BoardingPass::class,
            'booking_passenger_id', // Foreign key on boarding_passes table
            'id',                   // Foreign key on check_ins table
            'id',                   // Local key on booking_passengers table
            'check_in_id'           // Local key on boarding_passes table
        );
    }
    public function boardingPass()
    {
        return $this->hasOne(BoardingPass::class, 'booking_passenger_id');
    }
    public function baggage()
    {
        return $this->hasMany(Baggage::class, 'booking_passenger_id');
    }

    public function travelClass()
    {
        return $this->hasOneThrough(TravelClass::class, BookingFlight::class, 'id', 'id', 'booking_flight_id', 'travel_class_id');
    }

    public function getTicketPriceAttribute()
    {
        return $this->bookingFlight->price_per_passenger ?? 0;
    }
}