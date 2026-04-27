<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialAssistance extends Model
{
    protected $fillable = [
        'booking_passenger_id',
        'assistance_type',
        'wheelchair_type',
        'medical_condition',
        'service_animal',
        'special_equipment',
        'additional_notes',
        'approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'service_animal' => 'boolean',
        'special_equipment' => 'array',
        'approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function passenger()
    {
        return $this->belongsTo(BookingPassenger::class, 'booking_passenger_id');
    }

    /**
     * Get available assistance types
     */
    public static function getAssistanceTypes(): array
    {
        return [
            'wheelchair' => [
                'code' => 'WCHR',
                'name' => 'Wheelchair Assistance',
                'description' => 'Passenger can walk but needs wheelchair for long distances',
                'icon' => '♿',
                'requires_approval' => false,
                'advance_notice_hours' => 48,
            ],
            'wheelchair_stairs' => [
                'code' => 'WCHS',
                'name' => 'Wheelchair - Cannot Climb Stairs',
                'description' => 'Passenger cannot climb stairs but can walk short distances',
                'icon' => '♿',
                'requires_approval' => false,
                'advance_notice_hours' => 48,
            ],
            'wheelchair_immobile' => [
                'code' => 'WCHC',
                'name' => 'Wheelchair - Completely Immobile',
                'description' => 'Passenger cannot walk at all',
                'icon' => '♿',
                'requires_approval' => true,
                'advance_notice_hours' => 72,
            ],
            'blind' => [
                'code' => 'BLND',
                'name' => 'Blind Passenger',
                'description' => 'Visually impaired passenger',
                'icon' => '👁️',
                'requires_approval' => false,
                'advance_notice_hours' => 48,
            ],
            'deaf' => [
                'code' => 'DEAF',
                'name' => 'Deaf Passenger',
                'description' => 'Hearing impaired passenger',
                'icon' => '👂',
                'requires_approval' => false,
                'advance_notice_hours' => 24,
            ],
            'oxygen' => [
                'code' => 'OXYG',
                'name' => 'Oxygen Required',
                'description' => 'Passenger requires oxygen during flight',
                'icon' => '🫁',
                'requires_approval' => true,
                'advance_notice_hours' => 96,
            ],
            'stretcher' => [
                'code' => 'STCR',
                'name' => 'Stretcher Required',
                'description' => 'Passenger must travel on stretcher',
                'icon' => '🛏️',
                'requires_approval' => true,
                'advance_notice_hours' => 96,
            ],
            'service_animal' => [
                'code' => 'SVAN',
                'name' => 'Service Animal',
                'description' => 'Traveling with service/guide dog',
                'icon' => '🦮',
                'requires_approval' => true,
                'advance_notice_hours' => 48,
            ],
            'unaccompanied_minor' => [
                'code' => 'UMNR',
                'name' => 'Unaccompanied Minor',
                'description' => 'Child traveling alone (5-11 years)',
                'icon' => '👶',
                'requires_approval' => true,
                'advance_notice_hours' => 48,
            ],
            'elderly' => [
                'code' => 'ELDP',
                'name' => 'Elderly Passenger',
                'description' => 'Senior citizen requiring assistance',
                'icon' => '👴',
                'requires_approval' => false,
                'advance_notice_hours' => 24,
            ],
            'pregnant' => [
                'code' => 'PREG',
                'name' => 'Pregnant Passenger',
                'description' => 'Pregnant passenger (requires medical clearance after 28 weeks)',
                'icon' => '🤰',
                'requires_approval' => true,
                'advance_notice_hours' => 72,
            ],
            'medical' => [
                'code' => 'MEDA',
                'name' => 'Medical Assistance',
                'description' => 'Passenger with medical condition requiring special care',
                'icon' => '🏥',
                'requires_approval' => true,
                'advance_notice_hours' => 96,
            ],
        ];
    }

    /**
     * Get wheelchair types
     */
    public static function getWheelchairTypes(): array
    {
        return [
            'manual' => 'Manual Wheelchair',
            'electric' => 'Electric Wheelchair',
            'airline_provided' => 'Airline Provided',
        ];
    }

    /**
     * Get special equipment options
     */
    public static function getSpecialEquipment(): array
    {
        return [
            'crutches' => 'Crutches',
            'walker' => 'Walker',
            'cane' => 'Walking Cane',
            'portable_oxygen' => 'Portable Oxygen Concentrator',
            'cpap' => 'CPAP Machine',
            'nebulizer' => 'Nebulizer',
            'insulin_pump' => 'Insulin Pump',
            'epipen' => 'EpiPen',
        ];
    }

    /**
     * Check if assistance requires advance notice
     */
    public static function requiresAdvanceNotice(string $assistanceCode): int
    {
        $types = self::getAssistanceTypes();
        
        foreach ($types as $type) {
            if ($type['code'] === $assistanceCode) {
                return $type['advance_notice_hours'];
            }
        }

        return 24; // Default 24 hours
    }

    /**
     * Check if assistance requires approval
     */
    public static function requiresApproval(string $assistanceCode): bool
    {
        $types = self::getAssistanceTypes();
        
        foreach ($types as $type) {
            if ($type['code'] === $assistanceCode) {
                return $type['requires_approval'];
            }
        }

        return false;
    }

    /**
     * Validate if request meets advance notice requirement
     */
    public function meetsAdvanceNotice(): bool
    {
        $requiredHours = self::requiresAdvanceNotice($this->assistance_type);
        $hoursUntilFlight = now()->diffInHours($this->passenger->bookingFlight->flight->departure_datetime ?? now()->addDays(7));
        
        return $hoursUntilFlight >= $requiredHours;
    }
}
