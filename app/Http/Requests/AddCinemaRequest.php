<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCinemaRequest extends FormRequest
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
            'city_id' => 'required|exists:cities,id',
            'cinema_name' => 'required|string|max:255',
            'cinema_address' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'city_id.required' => 'City selection is required.',
            'city_id.exists' => 'The selected city does not exist.',
            'cinema_name.required' => 'Cinema name is required.',
            'cinema_name.max' => 'Cinema name must not be longer than 255 characters.',
            'cinema_address.required' => 'Cinema address is required.',
            'cinema_address.max' => 'Cinema address must not be longer than 255 characters.',
        ];
    }
}
