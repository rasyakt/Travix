<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeatAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'seats' => 'required|array',
            'seats.*.passenger_id' => 'required|exists:booking_passengers,id',
            'seats.*.seat_number' => 'required|string|max:10',
            'seats.*.seat_row' => 'required|integer|min:1',
            'seats.*.seat_column' => 'required|string|size:1',
        ];
    }

    public function messages(): array
    {
        return [
            'seats.*.passenger_id.required' => 'Passenger ID is required',
            'seats.*.seat_number.required' => 'Seat number is required',
            'seats.*.seat_row.required' => 'Seat row is required',
            'seats.*.seat_column.required' => 'Seat column is required',
        ];
    }
}
