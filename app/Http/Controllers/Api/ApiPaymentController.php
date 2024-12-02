<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiPaymentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'slot_ids' => 'nullable|array',
            'slot_ids.*' => 'exists:slots,id',
            'total_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payment = Payment::create([
            'user_id' => $request->user_id,
            'product_ids' => $request->product_ids,
            'slot_ids' => $request->slot_ids,
            'total_amount' => $request->total_amount,
        ]);

        return response()->json([
            'message' => 'Payment created successfully',
            'payment' => $payment,
        ], 201);
    }
}
