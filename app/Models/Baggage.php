<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Baggage extends Model
{
    protected $fillable = [
        'booking_passenger_id', 'flight_id', 'baggage_type',
        'weight_kg', 'fee', 'tag_number', 'status'
    ];

    public function passenger() { return $this->belongsTo(BookingPassenger::class, 'booking_passenger_id'); }
    public function flight() { return $this->belongsTo(Flight::class); }
}