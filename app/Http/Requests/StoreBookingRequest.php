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

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $passengers = $this->input('passengers', []);
            
            // Validate passenger count
            if (count($passengers) < 1 || count($passengers) > 9) {
                $validator->errors()->add('passengers', 'Jumlah penumpang harus antara 1-9 orang.');
            }
            
            // FIX: Check for duplicate passenger names + DOB
            $passengerKeys = [];
            foreach ($passengers as $index => $passenger) {
                $firstName = strtolower(trim($passenger['first_name'] ?? ''));
                $lastName = strtolower(trim($passenger['last_name'] ?? ''));
                $dob = $passenger['date_of_birth'] ?? '';
                
                $key = $firstName . '|' . $lastName . '|' . $dob;
                
                if (in_array($key, $passengerKeys)) {
                    $validator->errors()->add(
                        "passengers.{$index}", 
                        "Penumpang dengan nama dan tanggal lahir yang sama sudah ada dalam booking ini."
                    );
                }
                
                $passengerKeys[] = $key;
            }
            
            // Validate all passengers have same travel class
            $travelClasses = array_unique(array_column($passengers, 'travel_class_id'));
            if (count($travelClasses) > 1) {
                $validator->errors()->add('passengers', 'Semua penumpang harus memilih kelas perjalanan yang sama untuk saat ini.');
            }
        });
    }
}
