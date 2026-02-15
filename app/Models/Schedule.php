<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'airline_id', 'aircraft_id', 'origin_airport_id', 'destination_airport_id',
        'flight_number', 'departure_time', 'arrival_time', 'duration_minutes',
        'operating_days', 'valid_from', 'valid_until', 'base_price', 'is_active'
    ];
    
    protected $casts = [
        'operating_days' => 'array',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'departure_time' => 'datetime:H:i',
        'arrival_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    public function airline() { return $this->belongsTo(Airline::class); }
    public function aircraft() { return $this->belongsTo(Aircraft::class); }
    public function originAirport() { return $this->belongsTo(Airport::class, 'origin_airport_id'); }
    public function destinationAirport() { return $this->belongsTo(Airport::class, 'destination_airport_id'); }
    public function flights() { return $this->hasMany(Flight::class); }
}