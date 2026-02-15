<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AircraftManufacturer extends Model
{
    protected $fillable = ['name', 'country'];
    public function aircraft() { return $this->hasMany(Aircraft::class, 'manufacturer_id'); }
}