<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBeneficiaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        // Officers can manage any beneficiary
        if ($user->isOfficer()) {
            return true;
        }

        // Faculty can only add beneficiaries to their own profile
        $profileId = $this->input('faculty_profile_id');

        return $user->facultyProfile && (int) $user->facultyProfile->id === (int) $profileId;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'faculty_profile_id' => ['required', 'exists:faculty_profiles,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'relationship' => [
                'required',
                Rule::in([
                    'mother', 'father', 'mother-in-law', 'father-in-law',
                    'brother', 'sister', 'wife', 'husband', 'son', 'daughter', 'other',
                ]),
            ],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'pse' => ['nullable', 'string', 'max:50'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'is_primary' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'The beneficiary\'s full name is required.',
            'relationship.required' => 'Please select the relationship.',
            'relationship.in' => 'Please select a valid relationship.',
        ];
    }
}
