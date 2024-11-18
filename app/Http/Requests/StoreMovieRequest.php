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
            'age_rating' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
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
            'release_date.date' => 'The release date must be a valid date.',
        ];
    }
}
