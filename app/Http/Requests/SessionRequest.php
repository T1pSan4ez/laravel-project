<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionRequest extends FormRequest
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
            'movie_id' => 'required|exists:movies,id',
            'hall_id' => 'required|exists:halls,id',
            'start_time' => 'required|date|after:now',
            'technical_break' => 'integer|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'movie_id.required' => 'Movie selection is required.',
            'movie_id.exists' => 'The selected movie does not exist.',
            'hall_id.required' => 'The hall is required.',
            'hall_id.exists' => 'The selected hall does not exist.',
            'start_time.required' => 'Start time is required.',
            'technical_break.min' => 'Technical break must be at least 10 minutes.',
        ];
    }

}
