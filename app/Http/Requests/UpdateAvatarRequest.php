<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvatarRequest extends FormRequest
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
    public function rules()
    {
        return [
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'avatar.required' => 'The avatar field is required.',
            'avatar.image' => 'The file must be an image.',
            'avatar.mimes' => 'Only jpeg, png, jpg formats are allowed.',
            'avatar.max' => 'The image size must not exceed 2MB.',
        ];
    }
}
