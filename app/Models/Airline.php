<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Airline extends Model
{
    use HasFactory;

    protected $fillable = [
        'iata_code',
        'icao_code',
        'name',
        'country',
        'logo_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getLogoUrlAttribute($value): ?string
    {
        if (is_string($value) && trim($value) !== '') {
            return $value;
        }

        $iataCode = strtoupper((string) ($this->attributes['iata_code'] ?? ''));

        if ($iataCode === '') {
            return null;
        }

        return "https://images.kiwi.com/airlines/64x64/{$iataCode}.png";
    }

    public function aircraftInstances(): HasMany
    {
        return $this->hasMany(AircraftInstance::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function activeSchedules(): HasMany
    {
        return $this->hasMany(Schedule::class)->where('is_active', true);
    }

    public function cabinProfiles(): HasMany
    {
        return $this->hasMany(AirlineCabinProfile::class);
    }
}
