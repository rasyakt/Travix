<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BoardingPass extends Model
{
    protected $fillable = [
        'check_in_id', 'booking_passenger_id', 'boarding_pass_number',
        'seat_number', 'gate', 'boarding_time', 'qr_code_path',
        'qr_code_data', 'barcode', 'status', 'generated_at'
    ];
    
    protected $casts = ['boarding_time' => 'datetime:H:i', 'generated_at' => 'datetime'];

    public function checkIn() { return $this->belongsTo(CheckIn::class); }
    public function passenger() { return $this->belongsTo(BookingPassenger::class, 'booking_passenger_id'); }
}