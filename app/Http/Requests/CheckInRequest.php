<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class CheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'booking_id' => 'required|exists:bookings,id',
            'passenger_id' => 'required|exists:booking_passengers,id',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $booking = \App\Models\Booking::find($this->booking_id);

            if ($booking && $booking->flights->isNotEmpty()) {
                $flight = $booking->flights->first();
                $departureTime = Carbon::parse($flight->departure_time);
                $now = Carbon::now();

                // Check-in must be within 24 hours before departure
                if ($departureTime->diffInHours($now) > 24) {
                    $validator->errors()->add('check_in', 'Check-in is only available 24 hours before departure');
                }

                // Cannot check-in after departure
                if ($now->gt($departureTime)) {
                    $validator->errors()->add('check_in', 'Cannot check-in after flight departure');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'booking_id.required' => 'Booking ID is required',
            'booking_id.exists' => 'Booking not found',
            'passenger_id.required' => 'Passenger ID is required',
            'passenger_id.exists' => 'Passenger not found',
        ];
    }
}
