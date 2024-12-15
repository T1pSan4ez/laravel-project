<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseRequest;
use App\Interfaces\PurchaseRepositoryInterface;

class PurchaseController extends Controller
{
    protected $purchaseRepository;

    public function __construct(PurchaseRepositoryInterface $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
    }

    public function store(StorePurchaseRequest $request)
    {
        $validatedData = $request->validated();

        $purchase = $this->purchaseRepository->createPurchase(
            $validatedData,
            $validatedData['items']
        );

        return response()->json([
            'message' => 'Purchase successful.',
            'purchase' => $purchase,
        ], 201);
    }
}
