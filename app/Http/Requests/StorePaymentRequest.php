<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'slot_ids' => 'nullable|array',
            'slot_ids.*' => 'exists:slots,id',
            'total_amount' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required.',
            'user_id.exists' => 'User not found.',
            'product_ids.array' => 'Product IDs must be an array.',
            'product_ids.*.exists' => 'One or more products are invalid.',
            'slot_ids.array' => 'Slot IDs must be an array.',
            'slot_ids.*.exists' => 'One or more slots are invalid.',
            'total_amount.required' => 'Total amount is required.',
            'total_amount.numeric' => 'Total amount must be a number.',
            'total_amount.min' => 'Total amount cannot be negative.',
        ];
    }
}
