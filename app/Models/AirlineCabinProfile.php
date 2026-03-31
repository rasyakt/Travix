<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AirlineCabinProfile extends Model
{
    protected $fillable = [
        'airline_id',
        'aircraft_id',
        'travel_class_id',
        'start_row',
        'end_row',
        'columns',
        'layout_code',
        'exit_rows',
        'extra_price_exit',
    ];

    protected $casts = [
        'columns' => 'array',
        'exit_rows' => 'array',
        'extra_price_exit' => 'decimal:2',
    ];

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class);
    }

    public function aircraft(): BelongsTo
    {
        return $this->belongsTo(Aircraft::class);
    }

    public function travelClass(): BelongsTo
    {
        return $this->belongsTo(TravelClass::class);
    }
}
