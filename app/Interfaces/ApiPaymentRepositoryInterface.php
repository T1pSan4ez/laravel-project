<?php

namespace App\Interfaces;

use App\Models\Payment;

interface ApiPaymentRepositoryInterface
{
    public function createPayment(array $data): Payment;
}
