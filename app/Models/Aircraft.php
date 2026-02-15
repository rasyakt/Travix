<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aircraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'manufacturer_id',
        'model',
        'iata_code',
        'typical_seating_capacity',
        'max_range_km',
        'cruise_speed_kmh',
        'legroom',
        'amenities',
    ];

    protected $casts = [
        'typical_seating_capacity' => 'integer',
        'max_range_km' => 'integer',
        'cruise_speed_kmh' => 'decimal:2',
        'amenities' => 'array',
    ];

    // Relationships
    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(AircraftManufacturer::class, 'manufacturer_id');
    }

    public function instances(): HasMany
    {
        return $this->hasMany(AircraftInstance::class);
    }

    public function seatMaps(): HasMany
    {
        return $this->hasMany(SeatMap::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    // Scopes
    public function scopeByManufacturer($query, $manufacturerId)
    {
        return $query->where('manufacturer_id', $manufacturerId);
    }

    public function scopeByCapacity($query, $minCapacity, $maxCapacity = null)
    {
        $query->where('typical_seating_capacity', '>=', $minCapacity);

        if ($maxCapacity) {
            $query->where('typical_seating_capacity', '<=', $maxCapacity);
        }

        return $query;
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->manufacturer->name} {$this->model}";
    }

    public function getDisplayNameAttribute()
    {
        return "{$this->model} ({$this->iata_code})";
    }

    // Methods
    public function getTotalSeats($travelClassId = null)
    {
        $query = $this->seatMaps();

        if ($travelClassId) {
            $query->where('travel_class_id', $travelClassId);
        }

        return $query->count();
    }
}