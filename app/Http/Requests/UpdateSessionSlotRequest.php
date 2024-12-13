<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSessionSlotRequest extends FormRequest
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
            'slots' => 'required|array',
            'slots.*.slot_id' => 'required|integer|exists:slots,id',
            'slots.*.status' => 'required|string|in:available,booked',
        ];
    }

    public function messages(): array
    {
        return [
            'slots.required' => 'Slots data is required.',
            'slots.array' => 'Slots must be an array.',
            'slots.*.slot_id.required' => 'Each slot must have a slot ID.',
            'slots.*.slot_id.exists' => 'The provided slot ID does not exist.',
            'slots.*.status.required' => 'Each slot must have a status.',
            'slots.*.status.in' => 'Status must be either "available" or "booked".',
        ];
    }
}
