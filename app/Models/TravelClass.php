<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TravelClass extends Model
{
    protected $fillable = ['name', 'code', 'price_multiplier', 'baggage_allowance_kg', 'amenities', 'priority_boarding'];
    public function seatMaps() { return $this->hasMany(SeatMap::class); }
}