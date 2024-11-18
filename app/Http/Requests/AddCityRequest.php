<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCityRequest extends FormRequest
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
            'city_name' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'city_name.required' => 'City name is required.',
            'city_name.max' => 'City name must not be longer than 255 characters.',
        ];
    }
}
