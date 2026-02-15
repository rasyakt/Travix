<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BookingPassenger extends Model
{
    protected $fillable = [
        'booking_id', 'booking_flight_id', 'title', 'first_name', 'last_name',
        'date_of_birth', 'gender', 'nationality', 'passport_number',
        'passport_expiry', 'id_number', 'passenger_type', 'special_assistance'
    ];
    
    protected $casts = ['date_of_birth' => 'date', 'passport_expiry' => 'date'];

    public function booking() { return $this->belongsTo(Booking::class); }
    public function bookingFlight() { return $this->belongsTo(BookingFlight::class); }
    public function seatAssignments() { return $this->hasMany(SeatAssignment::class); }
    public function baggage() { return $this->hasMany(Baggage::class); }
}