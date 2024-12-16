<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer',
            'age_rating' => 'required|nullable|integer|max:23|min:1',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
            'release_date' => 'nullable|date',
            'poster' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.max' => 'The title must not exceed 255 characters.',
            'description.required' => 'The description field is required.',
            'duration.required' => 'The duration field is required.',
            'duration.integer' => 'The duration must be an integer.',
            'age_rating.required' => 'The age rating field is required.',
            'age_rating.integer' => 'Age must be integer.',
            'age_rating.min' => 'Age must not be less than 1.',
            'age_rating.max' => 'Age must not exceed 23.',
            'release_date.date' => 'The release date must be a valid date.',
            'poster.max' => 'The poster must not exceed 2048 bytes.',
            'genres.*.exists' => 'One or more selected genres do not exist.',
        ];
    }
}
