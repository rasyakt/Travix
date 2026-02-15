<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BookingFlight extends Model
{
    protected $fillable = [
        'booking_id', 'flight_id', 'travel_class_id', 'sequence',
        'segment_type', 'passenger_count', 'price_per_passenger', 'total_price'
    ];

    public function booking() { return $this->belongsTo(Booking::class); }
    public function flight() { return $this->belongsTo(Flight::class); }
    public function travelClass() { return $this->belongsTo(TravelClass::class); }
    public function passengers() { return $this->hasMany(BookingPassenger::class); }
}