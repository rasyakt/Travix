<?php

namespace App\Enums;

enum FlightStatus: string
{
    case SCHEDULED = 'scheduled';
    case ACTIVE = 'active';
    case LANDED = 'landed';
    case CANCELLED = 'cancelled';
    case DELAYED = 'delayed';

    public function label(): string
    {
        return match($this) {
            self::SCHEDULED => 'Scheduled',
            self::ACTIVE => 'Active',
            self::LANDED => 'Landed',
            self::CANCELLED => 'Cancelled',
            self::DELAYED => 'Delayed',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::SCHEDULED => 'blue',
            self::ACTIVE => 'green',
            self::LANDED => 'gray',
            self::CANCELLED => 'red',
            self::DELAYED => 'yellow',
        };
    }
}
