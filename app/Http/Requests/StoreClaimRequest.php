<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClaimRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        // Officers can submit claims for any faculty member
        if ($user->isOfficer()) {
            return true;
        }

        // Faculty can only submit claims for their own profile
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
            'claim_type_id' => ['required', 'exists:claim_types,id'],
            'beneficiary_id' => ['nullable', 'exists:beneficiaries,id'],
            'amount' => ['required', 'numeric', 'min:0', 'max:9999999999.99', function ($attribute, $value, $fail) {
                $claimTypeId = $this->input('claim_type_id');
                if ($claimTypeId) {
                    $claimType = \App\Models\ClaimType::find($claimTypeId);
                    if ($claimType && $claimType->max_amount && $value > $claimType->max_amount) {
                        $fail("The amount must not exceed the maximum of \u20b1".number_format($claimType->max_amount, 2).' for this claim type.');
                    }
                }
            }],
            'description' => ['nullable', 'string', 'max:1000'],
            'supporting_documents' => ['nullable', 'array'],
            'supporting_documents.*' => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'date_applied' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'faculty_profile_id.required' => 'Please select a faculty member.',
            'claim_type_id.required' => 'Please select a claim type.',
            'amount.required' => 'Please enter the claim amount.',
            'amount.numeric' => 'The amount must be a valid number.',
            'amount.min' => 'The amount must be at least 0.',
            'date_applied.required' => 'The application date is required.',
            'supporting_documents.*.max' => 'Each document must not exceed 5MB.',
        ];
    }
}
