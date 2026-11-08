<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTrainingSeminarRequest extends FormRequest
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
        $training = $this->route('training_seminar') ?? $this->route('training');
        $trainingId = is_object($training) ? $training->id : $training;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('trainings_seminars', 'slug')->ignore($trainingId),
            ],
            'description' => ['nullable', 'string'],
            'venue' => ['nullable', 'string', 'max:255'],
            'facilitator' => ['nullable', 'string', 'max:255'],
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'type' => [
                'required',
                Rule::in(['training', 'seminar', 'workshop', 'conference', 'webinar', 'other']),
            ],
            'campus_id' => ['nullable', 'exists:campuses,id'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'registration_fee' => ['nullable', 'numeric', 'min:0'],
            'status' => [
                'nullable',
                Rule::in(['upcoming', 'ongoing', 'completed', 'cancelled']),
            ],
            'is_published' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The training/seminar title is required.',
            'start_datetime.required' => 'The start date and time is required.',
            'end_datetime.required' => 'The end date and time is required.',
            'end_datetime.after' => 'The end date must be after the start date.',
            'type.required' => 'Please select a type.',
            'type.in' => 'Please select a valid type.',
        ];
    }
}
