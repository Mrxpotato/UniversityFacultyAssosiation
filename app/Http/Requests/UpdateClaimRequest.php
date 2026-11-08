<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClaimRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $claim = $this->route('claim');

        // Officers can update any claim
        if ($user->isOfficer()) {
            return true;
        }

        // Faculty can only update their own pending/remanded claims
        return $user->facultyProfile && $claim
            && $user->facultyProfile->id === $claim->faculty_profile_id
            && in_array($claim->status, ['pending', 'remanded']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'claim_type_id' => ['sometimes', 'exists:claim_types,id'],
            'beneficiary_id' => ['nullable', 'exists:beneficiaries,id'],
            'amount' => ['sometimes', 'numeric', 'min:0', 'max:9999999999.99'],
            'description' => ['nullable', 'string', 'max:1000'],
            'supporting_documents' => ['nullable', 'array'],
            'supporting_documents.*' => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'date_applied' => ['sometimes', 'date'],
            'date_approved' => ['nullable', 'date'],
            'date_released' => ['nullable', 'date'],
            'status' => [
                'sometimes',
                Rule::in(['pending', 'under_review', 'approved', 'rejected', 'released', 'remanded']),
            ],
            'is_remanded' => ['boolean'],
            'remand_reason' => ['nullable', 'string', 'max:1000'],
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'claim_type_id.exists' => 'Please select a valid claim type.',
            'amount.numeric' => 'The amount must be a valid number.',
            'amount.min' => 'The amount must be at least 0.',
            'supporting_documents.*.max' => 'Each document must not exceed 5MB.',
            'status.in' => 'Please select a valid status.',
        ];
    }
}
