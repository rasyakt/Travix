<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'flight_id' => 'required|exists:flights,id',
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',

            'passengers' => 'required|array|min:1|max:9',
            'passengers.*.title' => 'required|in:Mr,Mrs,Ms,Miss',
            'passengers.*.first_name' => 'required|string|max:100',
            'passengers.*.last_name' => 'required|string|max:100',
            'passengers.*.date_of_birth' => 'required|date|before:today',
            'passengers.*.nationality' => 'required|string|size:2',
            'passengers.*.passport_number' => 'nullable|string|max:50',
            'passengers.*.travel_class_id' => 'required|exists:travel_classes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'passengers.*.title.required' => 'Passenger title is required',
            'passengers.*.first_name.required' => 'Passenger first name is required',
            'passengers.*.last_name.required' => 'Passenger last name is required',
            'passengers.*.date_of_birth.required' => 'Passenger date of birth is required',
            'passengers.*.date_of_birth.before' => 'Date of birth must be in the past',
            'passengers.*.nationality.required' => 'Passenger nationality is required',
            'passengers.*.travel_class_id.required' => 'Travel class is required',
        ];
    }
}
