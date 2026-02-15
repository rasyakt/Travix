<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AircraftInstance extends Model
{
    protected $fillable = ['airline_id', 'aircraft_id', 'registration_number', 'name', 'manufacture_year', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function airline() { return $this->belongsTo(Airline::class); }
    public function aircraft() { return $this->belongsTo(Aircraft::class); }
}