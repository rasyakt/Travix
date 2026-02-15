<?php

namespace App\Enums;

enum SeatStatus: string
{
    case AVAILABLE = 'available';
    case OCCUPIED = 'occupied';
    case BLOCKED = 'blocked';

    public function label(): string
    {
        return match($this) {
            self::AVAILABLE => 'Available',
            self::OCCUPIED => 'Occupied',
            self::BLOCKED => 'Blocked',
        };
    }
}
