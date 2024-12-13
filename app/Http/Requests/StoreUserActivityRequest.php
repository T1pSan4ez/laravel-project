<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserActivityRequest extends FormRequest
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
            'action' => 'required|in:view,booking',
        ];
    }

    public function messages(): array
    {
        return [
            'movie_id.required' => 'Movie ID is required.',
            'movie_id.exists' => 'The selected movie does not exist.',
            'action.required' => 'Action is required.',
            'action.in' => 'Action must be either "view" or "booking".',
        ];
    }
}
