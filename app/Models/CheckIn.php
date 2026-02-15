<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    protected $fillable = [
        'booking_id', 'flight_id', 'checked_in_at',
        'check_in_method', 'baggage_checked', 'status'
    ];
    
    protected $casts = [
        'checked_in_at' => 'datetime',
        'baggage_checked' => 'boolean',
    ];

    public function booking() { return $this->belongsTo(Booking::class); }
    public function flight() { return $this->belongsTo(Flight::class); }
    public function boardingPasses() { return $this->hasMany(BoardingPass::class); }
}