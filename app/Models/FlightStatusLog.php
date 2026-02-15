<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FlightStatusLog extends Model
{
    protected $fillable = [
        'flight_id', 'old_status', 'new_status',
        'old_departure_time', 'new_departure_time',
        'old_arrival_time', 'new_arrival_time',
        'reason', 'updated_by', 'logged_at'
    ];
    
    protected $casts = [
        'old_departure_time' => 'datetime',
        'new_departure_time' => 'datetime',
        'old_arrival_time' => 'datetime',
        'new_arrival_time' => 'datetime',
        'logged_at' => 'datetime',
    ];

    public function flight() { return $this->belongsTo(Flight::class); }
}