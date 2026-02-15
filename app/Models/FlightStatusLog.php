<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FlightStatusLog extends Model
{
    protected $fillable = [
        'flight_id',
        'old_status',
        'new_status',
        'source',
        'raw_data',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
        'raw_data' => 'array',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }
}