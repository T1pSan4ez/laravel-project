<?php

namespace App\Repositories;

use App\Models\Payment;

class ApiPaymentRepository implements ApiPaymentRepositoryInterface
{
    public function createPayment(array $data): Payment
    {
        return Payment::create([
            'user_id' => $data['user_id'],
            'product_ids' => $data['product_ids'],
            'slot_ids' => $data['slot_ids'],
            'total_amount' => $data['total_amount'],
        ]);
    }
}
