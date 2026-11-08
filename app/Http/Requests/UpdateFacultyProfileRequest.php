<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFacultyProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $facultyProfile = $this->route('faculty_profile') ?? $this->route('profile');
        $profileId = is_object($facultyProfile) ? $facultyProfile->id : $facultyProfile;
        $userId = is_object($facultyProfile) ? $facultyProfile->user_id : null;

        return [
            // Personal Information
            'prefix' => ['nullable', 'string', 'in:Dr.,Prof.,Engr.,Atty.,Mr.,Ms.,Mrs.'],
            'surname' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'extension_name' => ['nullable', 'string', 'max:20'],
            'civil_status' => ['required', Rule::in(['single', 'married', 'widowed', 'separated'])],
            'gender' => ['required', Rule::in(['male', 'female', 'lgbtqia+'])],
            'birth_date' => ['required', 'date', 'before:today'],
            'birth_place' => ['required', 'string', 'max:255'],
            'home_address' => ['nullable', 'string', 'max:500'],
            'address_province' => ['nullable', 'string', 'max:100'],
            'address_city' => ['nullable', 'string', 'max:100'],
            'address_barangay' => ['nullable', 'string', 'max:100'],
            'address_street' => ['nullable', 'string', 'max:255'],
            'id_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'phone_number' => ['required', 'string', 'max:20'],

            // Professional Information
            'employee_id' => [
                'required',
                'string',
                'max:50',
                Rule::unique('faculty_profiles', 'employee_id')->ignore($profileId),
                Rule::unique('users', 'employee_id')->ignore($userId),
            ],
            'date_of_service' => ['nullable', 'date'],
            'academic_rank_id' => ['required', 'exists:academic_ranks,id'],
            'college_id' => ['required', 'exists:colleges,id'],
            'campus_id' => ['required', 'exists:campuses,id'],

            // Membership
            'membership_status' => ['required', Rule::in(['active', 'inactive', 'retired', 'deceased'])],
            'membership_date' => ['required', 'date'],

            // User account (optional for update)
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],

            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'surname.required' => 'The surname field is required.',
            'first_name.required' => 'The first name field is required.',
            'employee_id.required' => 'The employee ID field is required.',
            'employee_id.unique' => 'This employee ID is already registered.',
            'campus_id.required' => 'Please select a campus.',
            'email.unique' => 'This email address is already registered.',
            'id_picture.max' => 'The ID picture must not exceed 2MB.',
        ];
    }
}
