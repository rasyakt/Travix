<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id', 'payment_code', 'payment_method', 'amount', 'status',
        'midtrans_order_id', 'midtrans_transaction_id', 'midtrans_snap_token',
        'midtrans_redirect_url', 'payment_details', 'paid_at', 'expires_at', 'notes'
    ];
    
    protected $casts = [
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function booking() { return $this->belongsTo(Booking::class); }
}