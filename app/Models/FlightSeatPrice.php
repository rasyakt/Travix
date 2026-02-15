<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FlightSeatPrice extends Model
{
    protected $fillable = [
        'flight_id', 'travel_class_id', 'price',
        'available_seats', 'total_seats'
    ];

    public function flight() { return $this->belongsTo(Flight::class); }
    public function travelClass() { return $this->belongsTo(TravelClass::class); }
}