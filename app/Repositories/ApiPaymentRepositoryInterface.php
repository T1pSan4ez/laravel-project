<?php

namespace App\Repositories;

use App\Models\Payment;

interface ApiPaymentRepositoryInterface
{
    public function createPayment(array $data): Payment;
}
