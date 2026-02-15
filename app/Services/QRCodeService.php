<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Generate QR Code for boarding pass
     */
    public function generateBoardingPassQR(array $data): string
    {
        $qrData = json_encode([
            'booking_code' => $data['booking_code'],
            'passenger_name' => $data['passenger_name'],
            'flight_number' => $data['flight_number'],
            'seat_number' => $data['seat_number'],
            'boarding_time' => $data['boarding_time'],
            'gate' => $data['gate'],
        ]);

        $qrCode = QrCode::size(300)
            ->format('png')
            ->generate($qrData);

        $filename = 'boarding_passes/' . $data['booking_code'] . '_' . str_replace(' ', '_', $data['passenger_name']) . '.png';

        Storage::disk('public')->put($filename, $qrCode);

        return $filename;
    }

    /**
     * Generate QR Code and return as base64
     */
    public function generateAsBase64(array $data): string
    {
        $qrData = json_encode([
            'booking_code' => $data['booking_code'],
            'passenger_name' => $data['passenger_name'],
            'flight_number' => $data['flight_number'],
            'seat_number' => $data['seat_number'],
        ]);

        return base64_encode(
            QrCode::size(300)
                ->format('png')
                ->generate($qrData)
        );
    }
}
