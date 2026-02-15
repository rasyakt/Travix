<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SeatAssignment extends Model
{
    protected $fillable = [
        'booking_passenger_id', 'flight_id', 'seat_map_id',
        'seat_number', 'extra_fee', 'assignment_type', 'assigned_at'
    ];
    
    protected $casts = ['assigned_at' => 'datetime'];

    public function passenger() { return $this->belongsTo(BookingPassenger::class, 'booking_passenger_id'); }
    public function flight() { return $this->belongsTo(Flight::class); }
    public function seatMap() { return $this->belongsTo(SeatMap::class); }
}