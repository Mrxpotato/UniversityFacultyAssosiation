<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransparencyPostRequest extends FormRequest
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
        $post = $this->route('transparency_post') ?? $this->route('post');
        $postId = is_object($post) ? $post->id : $post;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('transparency_posts', 'slug')->ignore($postId),
            ],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'type' => [
                'required',
                Rule::in(['news', 'announcement', 'resolution', 'allegation', 'release', 'other']),
            ],
            'visibility' => [
                'required',
                Rule::in(['public', 'members_only']),
            ],
            'campus_id' => ['nullable', 'exists:campuses,id'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The post title is required.',
            'content.required' => 'The post content is required.',
            'type.required' => 'Please select a post type.',
            'type.in' => 'Please select a valid post type.',
            'visibility.required' => 'Please select the visibility.',
            'featured_image.max' => 'The featured image must not exceed 5MB.',
        ];
    }
}
