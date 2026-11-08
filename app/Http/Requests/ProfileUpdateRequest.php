<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Personal Information
            'surname' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'extension_name' => ['nullable', 'string', 'max:10'],
            'civil_status' => ['required', 'in:single,married,widowed,separated'],
            'gender' => ['required', 'in:male,female,lgbtqia+'],
            'birth_date' => ['required', 'date', 'before:today'],
            'birth_place' => ['required', 'string', 'max:255'],
            'home_address' => ['required', 'string'],
            'address_province' => ['nullable', 'string', 'max:255'],
            'address_city' => ['nullable', 'string', 'max:255'],
            'address_barangay' => ['nullable', 'string', 'max:255'],
            'address_street' => ['nullable', 'string', 'max:500'],
            'phone_number' => ['required', 'string', 'max:20'],
            'id_picture' => ['nullable', 'image', 'max:2048'], // 2MB max
        ];
    }
}
