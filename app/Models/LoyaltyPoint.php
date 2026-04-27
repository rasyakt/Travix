<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'points_earned',
        'points_used',
        'transaction_type',
        'description',
        'expires_at',
    ];

    protected $casts = [
        'points_earned' => 'integer',
        'points_used' => 'integer',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Calculate points earned from booking
     */
    public static function calculatePoints(float $amount, string $userTier = 'silver'): int
    {
        $basePoints = (int) floor($amount / 10000); // 1 point per 10,000 IDR

        $multiplier = match($userTier) {
            'platinum' => 3.0,
            'gold' => 2.0,
            'silver' => 1.5,
            default => 1.0,
        };

        return (int) ($basePoints * $multiplier);
    }

    /**
     * Get user's total available points
     */
    public static function getUserBalance(int $userId): int
    {
        $earned = self::where('user_id', $userId)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->sum('points_earned');

        $used = self::where('user_id', $userId)->sum('points_used');

        return max(0, $earned - $used);
    }

    /**
     * Redeem points for discount
     */
    public static function redeemPoints(int $userId, int $points, int $bookingId): bool
    {
        $balance = self::getUserBalance($userId);

        if ($balance < $points) {
            return false;
        }

        self::create([
            'user_id' => $userId,
            'booking_id' => $bookingId,
            'points_earned' => 0,
            'points_used' => $points,
            'transaction_type' => 'redemption',
            'description' => "Redeemed {$points} points for booking discount",
        ]);

        return true;
    }

    /**
     * Convert points to IDR value
     */
    public static function pointsToIDR(int $points): float
    {
        return $points * 100; // 1 point = 100 IDR
    }

    /**
     * Get loyalty tiers
     */
    public static function getTiers(): array
    {
        return [
            'bronze' => [
                'name' => 'Bronze',
                'min_points' => 0,
                'benefits' => ['1x points', 'Standard support'],
                'color' => '#CD7F32',
            ],
            'silver' => [
                'name' => 'Silver',
                'min_points' => 1000,
                'benefits' => ['1.5x points', 'Priority check-in', 'Free seat selection'],
                'color' => '#C0C0C0',
            ],
            'gold' => [
                'name' => 'Gold',
                'min_points' => 5000,
                'benefits' => ['2x points', 'Lounge access', 'Extra baggage', 'Priority boarding'],
                'color' => '#FFD700',
            ],
            'platinum' => [
                'name' => 'Platinum',
                'min_points' => 15000,
                'benefits' => ['3x points', 'Complimentary upgrades', 'Concierge service', 'All Gold benefits'],
                'color' => '#E5E4E2',
            ],
        ];
    }
}
