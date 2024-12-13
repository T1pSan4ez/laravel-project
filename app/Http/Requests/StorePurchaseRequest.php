<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
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
            'session_id' => 'required|integer|exists:sessions,id',
            'purchase_code' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'email' => 'required|email|max:255',
            'items' => 'required|array',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.slot_id' => 'nullable|integer',
            'items.*.product_id' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'session_id.required' => 'Session ID is required.',
            'session_id.exists' => 'The session does not exist.',
            'purchase_code.required' => 'Purchase code is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'items.required' => 'At least one item is required.',
            'items.*.item_name.required' => 'Item name is required for each item.',
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
            'items.*.price.required' => 'Price is required for each item.',
            'items.*.price.min' => 'Price must be at least 0.',
        ];
    }
}
