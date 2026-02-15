<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SeatMap extends Model
{
    protected $fillable = [
        'aircraft_id', 'travel_class_id', 'seat_number', 'row_number',
        'column_letter', 'position', 'is_exit_row', 'is_available', 'extra_price'
    ];
    
    protected $casts = ['is_exit_row' => 'boolean', 'is_available' => 'boolean'];

    public function aircraft() { return $this->belongsTo(Aircraft::class); }
    public function travelClass() { return $this->belongsTo(TravelClass::class); }
    public function assignments() { return $this->hasMany(SeatAssignment::class); }
}