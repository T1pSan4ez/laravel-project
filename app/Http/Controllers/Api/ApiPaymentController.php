<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Interfaces\ApiPaymentRepositoryInterface;

class ApiPaymentController extends Controller
{
    protected $repository;

    public function __construct(ApiPaymentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function store(StorePaymentRequest $request)
    {
        $payment = $this->repository->createPayment($request->validated());

        return response()->json([
            'message' => 'Payment created successfully',
            'payment' => $payment,
        ], 201);
    }
}
