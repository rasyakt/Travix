<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Airport extends Model
{
    use HasFactory;

    protected $fillable = [
        'iata_code',
        'icao_code',
        'name',
        'city',
        'country',
        'latitude',
        'longitude',
        'timezone',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function departureSchedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'origin_airport_id');
    }

    public function arrivalSchedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'destination_airport_id');
    }

    public function departureFlights()
    {
        return $this->hasManyThrough(
            Flight::class,
            Schedule::class,
            'origin_airport_id',
            'schedule_id'
        );
    }

    public function arrivalFlights()
    {
        return $this->hasManyThrough(
            Flight::class,
            Schedule::class,
            'destination_airport_id',
            'schedule_id'
        );
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->name} ({$this->iata_code})";
    }

    public function getLocationAttribute()
    {
        return "{$this->city}, {$this->country}";
    }
}