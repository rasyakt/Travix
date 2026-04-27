<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelInsurance extends Model
{
    protected $fillable = [
        'booking_id',
        'insurance_provider',
        'policy_number',
        'coverage_type',
        'coverage_amount',
        'premium_amount',
        'start_date',
        'end_date',
        'beneficiary_name',
        'beneficiary_relationship',
        'policy_details',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'coverage_amount' => 'decimal:2',
        'premium_amount' => 'decimal:2',
        'policy_details' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get available insurance plans
     */
    public static function getAvailablePlans(): array
    {
        return [
            'basic' => [
                'name' => 'Basic Protection',
                'coverage_amount' => 100000000, // 100 juta
                'premium_percentage' => 2, // 2% of ticket price
                'benefits' => [
                    'Trip cancellation',
                    'Medical emergency',
                    'Baggage loss (up to 5 juta)',
                    'Flight delay (> 6 hours)',
                ],
            ],
            'standard' => [
                'name' => 'Standard Protection',
                'coverage_amount' => 250000000, // 250 juta
                'premium_percentage' => 3.5,
                'benefits' => [
                    'Trip cancellation',
                    'Medical emergency',
                    'Baggage loss (up to 10 juta)',
                    'Flight delay (> 3 hours)',
                    'Personal accident',
                    'Travel document loss',
                ],
            ],
            'premium' => [
                'name' => 'Premium Protection',
                'coverage_amount' => 500000000, // 500 juta
                'premium_percentage' => 5,
                'benefits' => [
                    'Trip cancellation',
                    'Medical emergency',
                    'Baggage loss (up to 20 juta)',
                    'Flight delay (> 2 hours)',
                    'Personal accident',
                    'Travel document loss',
                    'Trip interruption',
                    'Emergency evacuation',
                    '24/7 assistance',
                ],
            ],
        ];
    }

    /**
     * Calculate premium based on ticket price
     */
    public static function calculatePremium(float $ticketPrice, string $planType): float
    {
        $plans = self::getAvailablePlans();
        
        if (!isset($plans[$planType])) {
            return 0;
        }

        $percentage = $plans[$planType]['premium_percentage'];
        return round($ticketPrice * ($percentage / 100), 0);
    }
}
